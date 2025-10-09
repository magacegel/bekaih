<?php

namespace App\Lib;

use App\Models\Category;
use App\Models\CompanyCertificate;
use App\Models\FormType;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\ShipTypeCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ReportGenerator
{
    public static function createPages(Report $report)
    {
        if (!is_array($report->metadata) || blank($report->metadata)) {
            $report->metadata = [];
        }

        $data = self::data($report);
        $parts = ['cover', 'general-particular', 'form1'];
        foreach ($parts as $part) {
            self::deleteFileFragment($report, $part);

            $newFile = self::generateFileFragment($report, $part, $data);

            if ($newFile) {
//                $report->metadata['fragments'][$part] = $newFile;
            }
        }
//        $report->save();
    }

    protected static function deleteFileFragment(Report $report, string $fragment): void
    {
        $existingFile = $report->metadata['fragments'][$fragment] ?? null;
        if (blank($existingFile)) {
            return;
        }

        // Delete existing cover file if it exists
        if (\Storage::disk('digitalocean')->exists($existingFile)) {
            \Storage::disk('digitalocean')->delete($existingFile);
        }

        if (isset($report->metadata['fragments'][$fragment])) {
            $metadata = $report->metadata;
            unset($metadata['fragments'][$fragment]);
            $report->metadata = $metadata;
        }
    }

    protected static function generateFileFragment(Report $report, string $fragment, array $data): string
    {
        $folderPath = 'reports/' . $report->id . '/fragments/';
        $path = $folderPath . $fragment . '.pdf';
        $pdf = Pdf::loadView('report.components.' . $fragment, $data);
        $pdf->setPaper('A4', 'portrait');
        \Storage::disk('digitalocean')->put($path, $pdf->output());


        // Get the current metadata as an array
        $metadata = is_array($report->metadata) ? $report->metadata : [];

        // Initialize fragments array if it doesn't exist
        if (!isset($metadata['fragments']) || !is_array($metadata['fragments'])) {
            $metadata['fragments'] = [];
        }

        // Update the fragments
        $metadata['fragments'][$fragment] = $path;

        // Set the metadata back to the model
        $report->metadata = $metadata;

        // Save the model
        $report->save();

        return $path;
    }


    public static function data(Report $report)
    {
        // Get required data for the TOC and other pages
        $ship_type_id = $report->ship->ship_type_id;
        $ship_type_data = ShipTypeCategory::where('ship_type_id', $ship_type_id)
            ->with('category')
            ->orderBy('order')
            ->get();

        $category_ids = $ship_type_data->pluck('category_id');

        $categories = Category::whereIn('id', $category_ids)
            ->with(['images' => function ($q) use ($report) {
                $q->where('report_id', $report->id);
            }])
            ->get();

        // Prepare data needed for all templates
        $data = [
            'report_id' => $report->id,
            'report' => $report,
            'ship' => $report->ship,
            'categories' => $categories, // Critical for the TOC template
            'plate' => get_plate_data(25),
            'total_line' => 11,
            'option_limit' => 25,
            'report_images' => ReportImage::where('report_id', $report->id)->get()->keyBy('category_id')
        ];

        $form_data_all = [];
        foreach ($categories as $category) {
            $form_type_id = ShipTypeCategory::where('category_id', $category->id)->get()->pluck('form_type_id');
            $form_type = FormType::whereIn('id', $form_type_id)->get();

            $form_type->load([
                'form' => function ($q) use ($report) {
                    $q->where('report_id', $report->id)->orderBy('name', 'ASC');
                }, 'form.form_data_one', 'form.form_data_two', 'form.report.user', 'form.report.ship'
            ]);

            $form_data_all[$category->id] = $form_type;
        }

        // Add form data to the template data
        $data['form_data_all'] = $form_data_all;

        return $data;
    }

    public static function takeFirstPageFromUploadedFile(CompanyCertificate &$certificate, UploadedFile $file, string $filePath)
    {
        if ($file->getClientMimeType() !== 'application/pdf') {
            return;
        }

        $metadata = is_array($certificate->metadata) ? $certificate->metadata : [];

        $tempPdf = null;
        $tempOutputPdf = null;

        try {
            // Create temporary file for the uploaded PDF
            $tempPdf = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
            copy($file->getRealPath(), $tempPdf);

            // Read page count using Spatie\PdfToImage
            $pdf = new \Spatie\PdfToImage\Pdf($tempPdf);
            $pageCount = $pdf->getNumberOfPages();

            if ($pageCount === 1) {
                // If only one page, use the original file as first page
                $metadata['first-page'] = $filePath;
                return;
            }

            // If multiple pages, extract only the first page using mPDF
            $tempOutputPdf = tempnam(sys_get_temp_dir(), 'output_') . '.pdf';

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'tempDir' => sys_get_temp_dir()
            ]);

            // Import the first page from the original PDF
            $pageCount = $mpdf->setSourceFile($tempPdf);
            $templateId = $mpdf->importPage(1); // Import only first page
            $mpdf->addPage();
            $mpdf->useTemplate($templateId);

            // Save the single-page PDF
            $mpdf->Output($tempOutputPdf, 'F');

            // Generate first page path
            $pathInfo = pathinfo($filePath);
            $firstPagePath = "{$pathInfo['dirname']}/{$pathInfo['filename']}_first_page.{$pathInfo['extension']}";

            // Upload the result to storage
            $disk = \Storage::disk('digitalocean');
            $disk->put($firstPagePath, file_get_contents($tempOutputPdf));

            $metadata['first-page'] = $firstPagePath;

        } catch (\Exception $e) {
            \Log::error('Error processing uploaded PDF: ' . $e->getMessage());
            // Fallback to original file if processing fails
            $metadata['first-page'] = $filePath;

        } finally {
            $certificate->metadata = $metadata;

            // Clean up temporary files
            if ($tempPdf && file_exists($tempPdf)) @unlink($tempPdf);
            if ($tempOutputPdf && file_exists($tempOutputPdf)) @unlink($tempOutputPdf);
            if (isset($pdf)) unset($pdf);
            if (isset($mpdf)) unset($mpdf);
        }
    }

    /**
     * @param string $filePath
     * @return string|null
     */
    public static function takeFirstPageFromDisk(string $filePath, string $firstPagePath = '')
    {
        if ($filePath === '[]') return null;

        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '512M');

        $pathInfo = pathinfo($filePath);
        if (strtolower($pathInfo['extension'] ?? '') !== 'pdf') {
            return null;
        }

        if (blank($firstPagePath)) {
            $firstPagePath = "{$pathInfo['dirname']}/{$pathInfo['filename']}_first_page.{$pathInfo['extension']}";
        }

        $disk = \Storage::disk('digitalocean');

        if (!$disk->exists($filePath)) {
            return null;
        }

        $tempPdf = null;
        $tempImage = null;
        $tempOutputPdf = null;

        try {
            // Create temporary files
            $tempPdf = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
            $tempImage = tempnam(sys_get_temp_dir(), 'img_') . '.jpg';
            $tempOutputPdf = tempnam(sys_get_temp_dir(), 'output_') . '.pdf';

            // Download the PDF
            $fileContents = $disk->read($filePath);
            file_put_contents($tempPdf, $fileContents);

            // Convert first page to image
            $pdf = new \Spatie\PdfToImage\Pdf($tempPdf);
            $pdf->setPage(1)
                ->setOutputFormat('jpg')
                ->saveImage($tempImage);

            // Create a new PDF with the image
            $imageSize = getimagesize($tempImage);
            $width = $imageSize[0] * 0.75; // Convert pixels to points (72 DPI)
            $height = $imageSize[1] * 0.75;

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [$width, $height],
                'tempDir' => sys_get_temp_dir(),
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
            ]);

            $mpdf->AddPageByArray([
                'margin-left' => 0,
                'margin-right' => 0,
                'margin-top' => 0,
                'margin-bottom' => 0,
                'margin-header' => 0,
                'margin-footer' => 0,
            ]);

            $mpdf->Image($tempImage, 0, 0, $width, $height, 'jpg', '', true, false);
            $mpdf->Output($tempOutputPdf, 'F');

            // Upload the result back to storage
            $disk->put($firstPagePath, file_get_contents($tempOutputPdf));

            return $disk->url($firstPagePath);

        } catch (\Exception $e) {
            \Log::error('Error processing PDF: ' . $e->getMessage());
            return null;

        } finally {
            // Clean up temporary files
            if ($tempPdf && file_exists($tempPdf)) @unlink($tempPdf);
            if ($tempImage && file_exists($tempImage)) @unlink($tempImage);
            if ($tempOutputPdf && file_exists($tempOutputPdf)) @unlink($tempOutputPdf);
            if (isset($fileContents)) unset($fileContents);
            if (isset($pdf)) unset($pdf);
            if (isset($mpdf)) unset($mpdf);
        }
    }

    public static function compileReport(Report $report)
    {
        // Skip if already compiled
        if (isset($report->metadata['compiled'])) {
            return;
        }

        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '768M');

        try {
            // Initialize mPDF
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'tempDir' => storage_path('app/temp')
            ]);

            // Helper function to add PDF from URL
            $addPdf = function ($url) use (&$mpdf) {
                if (empty($url)) return;

                try {
                    $tempPath = tempnam(sys_get_temp_dir(), 'pdf_');
                    file_put_contents($tempPath, file_get_contents($url));

                    $pageCount = $mpdf->setSourceFile($tempPath);
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $templateId = $mpdf->importPage($i);
                        $mpdf->addPage();
                        $mpdf->useTemplate($templateId);
                    }
                } catch (\Exception $e) {
                    \Log::error("Failed to add PDF from {$url}: " . $e->getMessage());
                } finally {
                    if (isset($tempPath)) @unlink($tempPath);
                }
            };

            // 1. Add cover
            if (isset($report->metadata['cover'])) {
                $coverUrl = \Storage::disk('digitalocean')->temporaryUrl(
                    $report->metadata['cover'], now()->addMinutes(30)
                );
                $addPdf($coverUrl);
            }

            // 2. Add general particulars
            if (isset($report->metadata['general-particular'])) {
                $gpUrl = \Storage::disk('digitalocean')->temporaryUrl(
                    $report->metadata['general-particular'], now()->addMinutes(30)
                );
                $addPdf($gpUrl);
            }

            // 3. Generate and add TOC
            $data = self::data($report);
            $tocHtml = view('report.components.toc', $data)->render();
            $mpdf->addPage();
            $mpdf->WriteHTML($tocHtml);

            // 4. Add company certificate
            if ($report->user->company->activeCertificate &&
                isset($report->user->company->activeCertificate->metadata['first-page'])) {
                $certUrl = \Storage::disk('digitalocean')->temporaryUrl(
                    $report->user->company->activeCertificate->metadata['first-page'],
                    now()->addMinutes(30)
                );
                $addPdf($certUrl);
            }

            // 5. Add user's latest qualification
            if ($report->user->latestQualification &&
                isset($report->user->latestQualification->metadata['first-page'])) {
                $qualUrl = \Storage::disk('digitalocean')->temporaryUrl(
                    $report->user->latestQualification->metadata['first-page'],
                    now()->addMinutes(30)
                );
                $addPdf($qualUrl);
            }

            // 6. Add equipment certifications
            foreach ($report->equipment->activeCertifications as $certification) {
                if (isset($certification->metadata['first-page'])) {
                    $certUrl = \Storage::disk('digitalocean')->temporaryUrl(
                        $certification->metadata['first-page'],
                        now()->addMinutes(30)
                    );
                    $addPdf($certUrl);
                }
            }

            // 7. Add form1
            if (isset($report->metadata['form1'])) {
                $form1Url = \Storage::disk('digitalocean')->temporaryUrl(
                    $report->metadata['form1'],
                    now()->addMinutes(30)
                );
                $addPdf($form1Url);
            }

            // Save the compiled PDF
            $outputPath = 'reports/' . $report->id . '/' . Str::slug($report->name) . '-compiled.pdf';
            $mpdf->Output(\Storage::disk('digitalocean')->path($outputPath), 'F');

            // Update report metadata
            $metadata = $report->metadata;
            $metadata['compiled'] = $outputPath;
            $report->metadata = $metadata;
            $report->save();

        } catch (\Exception $e) {
            \Log::error('Failed to compile report ' . $report->id . ': ' . $e->getMessage());
            throw $e;
        } finally {
            // Clean up
            if (isset($mpdf)) unset($mpdf);
        }
    }
}
