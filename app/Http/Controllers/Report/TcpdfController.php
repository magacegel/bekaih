<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FormType;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\ShipTypeCategory;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;

class TcpdfController extends Controller
{
    public function run($id)
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '512M');

        list($report, $data) = $this->data($id);
        /* @var Report $report */
        /* @var array $data */

        $fragments = [];
        $folderPath = 'reports/' . $report->id . '/fragments/';

        try {
            // Generate and store cover page
            $coverPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.cover', $data);
            $coverPdf->setPaper('A4', 'portrait');
            $coverPath = $folderPath . 'cover.pdf';
            \Storage::disk('digitalocean')->put($coverPath, $coverPdf->output());
            $fragments['cover'] = $coverPath;

            // Generate and store general particular page
            $gpPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.general-particular', $data);
            $gpPdf->setPaper('A4', 'portrait');
            $particularPath = $folderPath . 'particular.pdf';
            \Storage::disk('digitalocean')->put($particularPath, $gpPdf->output());
            $fragments['particular'] = $particularPath;

            // Generate and store form1 page
            $formPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.form1', $data);
            $formPdf->setPaper('A4', 'landscape');
            $form1Path = $folderPath . 'form1.pdf';
            \Storage::disk('digitalocean')->put($form1Path, $formPdf->output());
            $fragments['form1'] = $form1Path;

            // Handle company's certificate (first page only)
            $activeCertificate = $report->user->company->activeCertificate;
            if ($activeCertificate && Storage::disk('digitalocean')->exists($activeCertificate->certificate_file)) {
                // Create a temporary file for the certificate
                $tempCertFile = tempnam(sys_get_temp_dir(), 'comp_');
                file_put_contents($tempCertFile, Storage::disk('digitalocean')->get($activeCertificate->certificate_file));

                // Create a new FPDI instance for the certificate
                $certFpdi = new \setasign\Fpdi\Fpdi();

                // Set source file and get page count
                $pageCount = $certFpdi->setSourceFile($tempCertFile);

                if ($pageCount > 0) {
                    // Import only the first page
                    $templateId = $certFpdi->importPage(1);

                    // Get the size of the first page
                    $size = $certFpdi->getTemplateSize($templateId);
                    $orientation = $size['width'] > $size['height'] ? 'L' : 'P';

                    // Add a new page with the same orientation as the original
                    $certFpdi->AddPage($orientation, array($size['width'], $size['height']));
                    $certFpdi->useTemplate($templateId);

                    // Generate a unique filename for the certificate fragment
                    $certPath = $folderPath . 'company_certificate.pdf';

                    // Save only the first page to DigitalOcean Spaces
                    $tempOutput = tempnam(sys_get_temp_dir(), 'comp_out_');
                    $certFpdi->Output($tempOutput, 'F');

                    // Upload to DigitalOcean Spaces
                    Storage::disk('digitalocean')->put($certPath, file_get_contents($tempOutput));

                    // Store the path in fragments
                    $fragments['company'] = $certPath;

                    // Clean up temporary files
                    unlink($tempOutput);
                }

                // Clean up the temporary certificate file
                unlink($tempCertFile);
            }

            // Handle user's qualification certificate (first page only)
            if ($report->user->latestQualification) {
                $qualification = $report->user->latestQualification;

                if ($qualification->certificate_file && Storage::disk('digitalocean')->exists($qualification->certificate_file)) {
                    try {
                        // Create a temporary file for the qualification certificate
                        $tempQualFile = tempnam(sys_get_temp_dir(), 'qual_');
                        file_put_contents($tempQualFile, Storage::disk('digitalocean')->get($qualification->certificate_file));

                        // Create a new FPDI instance for the qualification certificate
                        $qualFpdi = new \setasign\Fpdi\Fpdi();

                        // Set source file and get page count
                        $pageCount = $qualFpdi->setSourceFile($tempQualFile);

                        if ($pageCount > 0) {
                            // Import only the first page
                            $templateId = $qualFpdi->importPage(1);

                            // Get the size of the first page
                            $size = $qualFpdi->getTemplateSize($templateId);
                            $orientation = $size['width'] > $size['height'] ? 'L' : 'P';

                            // Add a new page with the same orientation as the original
                            $qualFpdi->AddPage($orientation, array($size['width'], $size['height']));
                            $qualFpdi->useTemplate($templateId);

                            // Generate the path for the qualification fragment
                            $qualFolderPath = $folderPath . $qualification->id . '/';
                            $qualPath = $qualFolderPath . 'qualification.pdf';

                            // Save only the first page to DigitalOcean Spaces
                            $tempOutput = tempnam(sys_get_temp_dir(), 'qual_out_');
                            $qualFpdi->Output($tempOutput, 'F');

                            // Upload to DigitalOcean Spaces
                            Storage::disk('digitalocean')->put($qualPath, file_get_contents($tempOutput));

                            // Store the path in fragments
                            $fragments['qualification'] = $qualPath;

                            // Clean up temporary files
                            unlink($tempOutput);
                        }

                        // Clean up the temporary qualification file
                        unlink($tempQualFile);

                    } catch (\Exception $e) {
                        // Log any errors that occur during processing
                        \Log::error('Error processing qualification certificate: ' . $e->getMessage());
                    }
                }
            }

            // Handle equipment's certificates (first page only)
            if ($report->equipment && $report->equipment->activeCertifications) {
                $equipmentCertificates = [];

                foreach ($report->equipment->activeCertifications as $index => $certification) {
                    if ($certification->url && Storage::disk('digitalocean')->exists($certification->url)) {
                        try {
                            // Create a temporary file for the equipment certificate
                            $tempEquipFile = tempnam(sys_get_temp_dir(), 'equip_');
                            file_put_contents($tempEquipFile, Storage::disk('digitalocean')->get($certification->url));

                            // Create a new FPDI instance for the equipment certificate
                            $equipFpdi = new \setasign\Fpdi\Fpdi();

                            // Set source file and get page count
                            $pageCount = $equipFpdi->setSourceFile($tempEquipFile);

                            if ($pageCount > 0) {
                                // Import only the first page
                                $templateId = $equipFpdi->importPage(1);

                                // Get the size of the first page
                                $size = $equipFpdi->getTemplateSize($templateId);
                                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';

                                // Add a new page with the same orientation as the original
                                $equipFpdi->AddPage($orientation, array($size['width'], $size['height']));
                                $equipFpdi->useTemplate($templateId);

                                // Generate the path for the equipment certificate fragment
                                $equipPath = $folderPath . 'equipment_' . ($index + 1) . '.pdf';

                                // Save only the first page to DigitalOcean Spaces
                                $tempOutput = tempnam(sys_get_temp_dir(), 'equip_out_');
                                $equipFpdi->Output($tempOutput, 'F');

                                // Upload to DigitalOcean Spaces
                                Storage::disk('digitalocean')->put($equipPath, file_get_contents($tempOutput));

                                // Add to equipment certificates array
                                $equipmentCertificates[] = $equipPath;

                                // Clean up temporary files
                                unlink($tempOutput);
                            }

                            // Clean up the temporary equipment certificate file
                            unlink($tempEquipFile);

                        } catch (\Exception $e) {
                            // Log any errors that occur during processing
                            \Log::error('Error processing equipment certificate: ' . $e->getMessage());
                        }
                    }
                }

                // Store all equipment certificate paths in fragments if any were processed
                if (!empty($equipmentCertificates)) {
                    $fragments['equipments'] = $equipmentCertificates;
                }
            }

            // Update report metadata with fragment paths
            $metadata = $report->metadata ?? [];
            $metadata['fragments'] = $fragments;
            $report->metadata = $metadata;
            $report->save();
        } catch (\Exception $e) {
            \Log::error('Error generating PDF fragments: ' . $e->getMessage());
            throw $e;
        }

        return response()->json($metadata);
    }

    public function data($id)
    {
        $report_id = base64_decode($id);
        if (!$report_id || !is_numeric($report_id)) {
            abort(403, 'Akses tidak sah');
        }

        // origin: $report = Report::where('id', $report_id)->with('ship')->first();
        $report = Report::where('id', $report_id)
            ->with([
                'ship',
                'user:id,company_id',
                'user.company:id,company_certificate_id',
                'user.company.activeCertificate:id,certificate_file',
                'user.latestQualification:id,certificate_file',
                'equipment.activeCertifications:id,url'
            ])
            ->first();

        if (!$report) {
            abort(404, 'Data tidak ditemukan.');
        }

        if ($report->status == 'deleted') {
            abort(400, 'Report has been deleted');
        }

        // Get required data for the TOC and other pages
        $ship_type_id = $report->ship->ship_type_id;
        $ship_type_data = ShipTypeCategory::where('ship_type_id', $ship_type_id)
            ->with('category')
            ->orderBy('order')
            ->get();

        $category_ids = $ship_type_data->pluck('category_id');

        $categories = Category::whereIn('id', $category_ids)
            ->with(['images' => function ($q) use ($report_id) {
                $q->where('report_id', $report_id);
            }])
            ->get();

        // Prepare data needed for all templates
        $data = [
            'report_id' => $report_id,
            'report' => $report,
            'ship' => $report->ship,
            'categories' => $categories, // Critical for the TOC template
            'plate' => get_plate_data(25),
            'total_line' => 11,
            'option_limit' => 25,
            'report_images' => ReportImage::where('report_id', $report_id)->get()->keyBy('category_id')
        ];

        $form_data_all = [];
        foreach ($categories as $category) {
            $form_type_id = ShipTypeCategory::where('category_id', $category->id)->get()->pluck('form_type_id');
            $form_type = FormType::whereIn('id', $form_type_id)->get();

            $form_type->load([
                'form' => function ($q) use ($report_id) {
                    $q->where('report_id', $report_id)->orderBy('name', 'ASC');
                }, 'form.form_data_one', 'form.form_data_two', 'form.report.user', 'form.report.ship'
            ]);

            $form_data_all[$category->id] = $form_type;
        }

        // Add form data to the template data
        $data['form_data_all'] = $form_data_all;

        return [$report, $data];
    }

}
