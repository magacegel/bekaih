<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certification;
use App\Models\FormType;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\Ship;
use App\Models\ShipTypeCategory;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;

class ReportOptimizedController extends Controller
{
    public function render($id)
    {

        // Mengubah batas waktu eksekusi menjadi 3600 detik (1 jam)
        ini_set('max_execution_time', 3600);

        // Mengubah batas memori menjadi 512 MB
        ini_set('memory_limit', '512M');

        try {
            // STEP 1: Query all necessary data with minimal relations
            $report_id = base64_decode($id);
            if (!$report_id || !is_numeric($report_id)) {
                return response('Invalid report ID', 400);
            }

            // Load essential relationships
            $report = Report::where('id', $report_id)
                ->with([
                    'ship:id,name,ship_type_id',
                    'user:id,company_id',
                    'user.company:id,company_certificate_id',
                    'user.company.activeCertificate:id,certificate_file',
                    'user.latestQualification:id,certificate_file',
                    'equipment.activeCertifications:id,url'
                ])
                ->first();

            if (!$report) {
                return response('Report not found', 404);
            }

            if ($report->status == 'deleted') {
                return response('Report has been deleted', 400);
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
                'report' => $report,
                'ship' => $report->ship,
                'report_id' => $report_id,
                'categories' => $categories, // Critical for the TOC template
                'plate' => get_plate_data(25),
                'total_line' => 11,
                'option_limit' => 25,
                'report_images' => ReportImage::where('report_id', $report_id)->get()->keyBy('category_id')
            ];

            // Get form data for each category
            $form_data_all = [];
            foreach ($categories as $category) {
                $form_type_ids = ShipTypeCategory::where('category_id', $category->id)
                    ->pluck('form_type_id');

                $form_types = FormType::whereIn('id', $form_type_ids)
                    ->with([
                        'form' => function ($q) use ($report_id) {
                            $q->where('report_id', $report_id)->orderBy('name', 'ASC');
                        },
                        'form.form_data_one',
                        'form.form_data_two',
                        'form.report.user',
                        'form.report.ship'
                    ])
                    ->get();

                $form_data_all[$category->id] = $form_types;
            }

            // Add form data to the template data
            $data['form_data_all'] = $form_data_all;

            // Array to store temporary file paths
            $fragments = [];
            $tempFiles = []; // For cleanup

            // STEP 2: Generate basic pages and save to temporary files

            // Cover page (portrait)
            \Log::info('Generating cover page');
            $coverPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.cover', $data);
            $coverPdf->setPaper('A4', 'portrait');
            $coverTempFile = tempnam(sys_get_temp_dir(), 'cover_');
            file_put_contents($coverTempFile, $coverPdf->output());
            $fragments[] = $coverTempFile;
            $tempFiles[] = $coverTempFile;

            // Free memory
            $coverPdf = null;
            unset($coverPdf);

            // General Particular page (portrait)
            \Log::info('Generating general particular page');
            $gpPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.general-particular', $data);
            $gpPdf->setPaper('A4', 'portrait');
            $gpTempFile = tempnam(sys_get_temp_dir(), 'gp_');
            file_put_contents($gpTempFile, $gpPdf->output());
            $fragments[] = $gpTempFile;
            $tempFiles[] = $gpTempFile;

            // Free memory
            $gpPdf = null;
            unset($gpPdf);

            // TOC page (portrait)
            \Log::info('Generating TOC page');
            $tocPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.toc', $data);
            $tocPdf->setPaper('A4', 'portrait');
            $tocTempFile = tempnam(sys_get_temp_dir(), 'toc_');
            file_put_contents($tocTempFile, $tocPdf->output());
            $fragments[] = $tocTempFile;
            $tempFiles[] = $tocTempFile;

            // Free memory
            $tocPdf = null;
            unset($tocPdf);

            // STEP 3: Process company certificate if available
            if (isset($report->user->company->activeCertificate) &&
                $report->user->company->activeCertificate &&
                $report->user->company->activeCertificate->certificate_file) {

                \Log::info('Processing company certificate');
                $certificateFile = $report->user->company->activeCertificate->certificate_file;

                try {
                    // Get temporary URL
                    $certificateUrl = Storage::disk('digitalocean')
                        ->temporaryUrl($certificateFile, now()->addMinutes(5));

                    // Download certificate to temp file
                    $certTempFile = tempnam(sys_get_temp_dir(), 'cert_');
                    $tempFiles[] = $certTempFile;

                    $client = new \GuzzleHttp\Client([
                        'timeout' => 30,
                        'connect_timeout' => 30
                    ]);

                    $response = $client->get($certificateUrl, [
                        'sink' => $certTempFile
                    ]);

                    // Extract first page only and save to new temp file
                    $firstPageFile = $this->extractFirstPageFromPdf($certTempFile);
                    if ($firstPageFile) {
                        $fragments[] = $firstPageFile;
                        $tempFiles[] = $firstPageFile;
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to process certificate: ' . $e->getMessage());
                    // Continue without certificate if there's an error
                }
            }
            // Tambah halaman sertifikat personil (portrait)
            if ($report->user->latestQualification) {
                $competency = $report->user->latestQualification;

                if ($competency->certificate_file) {
                    try {
                        // Ambil URL sertifikat dari storage
                        $certificateUrl = Storage::disk('digitalocean')->temporaryUrl($competency->certificate_file, now()->addMinutes(5));

                        // Download file sertifikat
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $certificateUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $certificateData = curl_exec($ch);
                        curl_close($ch);

                        if ($certificateData !== false) {
                            // Simpan sementara file sertifikat
                            $tempFile = tempnam(sys_get_temp_dir(), 'cert');
                            file_put_contents($tempFile, $certificateData);

                            // Extract first page only and save to new temp file
                            $firstPageFile = $this->extractFirstPageFromPdf($tempFile);
                            if ($firstPageFile) {
                                $fragments[] = $firstPageFile;
                                $tempFiles[] = $firstPageFile;
                            }

                            // Hapus file temporary
                            unlink($tempFile);
                        }
                    } catch (\Exception $e) {
                        // Handle error jika gagal membaca sertifikat
                        \Log::error('Error reading personnel certificate: ' . $e->getMessage());
                    }
                }

                // Tambah halaman sertifikat equipment (portrait)
                if ($report->equipment && $report->equipment->activeCertifications) {
                    foreach ($report->equipment->activeCertifications as $certification) {
                        if ($certification->url) {
                            try {
                                // Download file sertifikat
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $certification->url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                $certificateData = curl_exec($ch);
                                curl_close($ch);

                                if ($certificateData !== false) {
                                    // Simpan sementara file sertifikat
                                    $tempFile = tempnam(sys_get_temp_dir(), 'cert');
                                    file_put_contents($tempFile, $certificateData);

                                    // Extract first page only and save to new temp file
                                    $firstPageFile = $this->extractFirstPageFromPdf($tempFile);
                                    if ($firstPageFile) {
                                        $fragments[] = $firstPageFile;
                                        $tempFiles[] = $firstPageFile;
                                    }

                                    // Hapus file temporary
                                    unlink($tempFile);
                                }
                            } catch (\Exception $e) {
                                // Handle error jika gagal membaca sertifikat
                                \Log::error('Error reading equipment certificate: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }

            \Log::info('Generating form page (landscape)');
            $formPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.form1', $data);
            $formPdf->setPaper('A4', 'landscape');
            $formTempFile = tempnam(sys_get_temp_dir(), 'form_');
            file_put_contents($formTempFile, $formPdf->output());
            $fragments[] = $formTempFile;
            $tempFiles[] = $formTempFile;

            // Free memory
            $formPdf = null;
            unset($formPdf);
            // STEP 4: Merge all fragments into a single PDF
            \Log::info('Merging ' . count($fragments) . ' PDF fragments');
            $outputFile = $this->mergePdfFragments($fragments);
            if ($outputFile) {
                $tempFiles[] = $outputFile;

                // Set filename
                $pdf_name = $report->ship->name . ' [' . $report->report_number . '].pdf';

                // Read file content
                $fileContent = file_get_contents($outputFile);

                // Clean up all temporary files
                foreach ($tempFiles as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }

                // Return the PDF
                return response($fileContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $pdf_name . '"');
            }

            return response('Failed to generate PDF', 500);

        } catch (\Exception $e) {
            \Log::error('PDF generation error: ' . $e->getMessage());
            return response('Error generating PDF: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Extract only the first page from a PDF file
     *
     * @param string $inputFile Path to input PDF file
     * @return string|null Path to output PDF file containing only first page, or null on failure
     */
    private function extractFirstPageFromPdf($inputFile)
    {
        $outputFile = tempnam(sys_get_temp_dir(), 'p1_');

        try {
            $pdf = new \setasign\Fpdi\Fpdi();

            // Get page count
            $pageCount = $pdf->setSourceFile($inputFile);

            if ($pageCount >= 1) {
                // Import only first page
                $pdf->AddPage();
                $templateId = $pdf->importPage(1);
                $pdf->useTemplate($templateId);

                // Save to output file
                file_put_contents($outputFile, $pdf->Output('S'));
                return $outputFile;
            }
        } catch (\Exception $e) {
            \Log::error('Error extracting first page from PDF: ' . $e->getMessage());
        }

        // Clean up on failure
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }

        return null;
    }

    /**
     * Merge multiple PDF files into a single PDF
     *
     * @param array $files Array of file paths to merge
     * @return string|null Path to merged PDF file, or null on failure
     */
    private function mergePdfFragments($files)
    {
        if (empty($files)) {
            return null;
        }

        $outputFile = tempnam(sys_get_temp_dir(), 'merged_');

        try {
            $pdf = new \setasign\Fpdi\Fpdi();

            // Process each file
            foreach ($files as $file) {
                if (!file_exists($file)) {
                    continue;
                }

                $pageCount = $pdf->setSourceFile($file);

                // Import all pages
                for ($i = 1; $i <= $pageCount; $i++) {
                    $templateId = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($templateId);

                    // Determine orientation
                    $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

                    $pdf->AddPage($orientation);
                    $pdf->useTemplate($templateId);
                }
            }

            // Save merged PDF
            file_put_contents($outputFile, $pdf->Output('S'));
            return $outputFile;

        } catch (\Exception $e) {
            \Log::error('Error merging PDF fragments: ' . $e->getMessage());

            // Clean up on failure
            if (file_exists($outputFile)) {
                unlink($outputFile);
            }

            return null;
        }
    }

    public function listPDF($id)
    {


        // Mengubah batas waktu eksekusi menjadi 3600 detik (1 jam)
        ini_set('max_execution_time', 3600);

        // Mengubah batas memori menjadi 512 MB
        ini_set('memory_limit', '4G');
        $report_id = base64_decode($id);
        if ($report_id && is_numeric($report_id)) {
            $report = Report::where('id', $report_id)->with('ship')->first();

            // dd($report->invoice);
            // Ambil invoice terbaru jika ada lebih dari 1
            // $latestInvoice = $report->invoice()->latest()->first();

            // if (!$latestInvoice) {
            //     $response = app(InvoiceController::class)->initiatePayment($report);
            //     return redirect($response['redirect_url']);
            // } else {
            //     if($latestInvoice->status == 'pending') {
            //         return redirect($latestInvoice->payment_url);
            //     } else if ($latestInvoice->status == 'paid') {
            //     } else {
            //         $response = app(InvoiceController::class)->initiatePayment($report);
            //         return redirect($response['redirect_url']);
            //     }
            // }

            // if((auth()->user()->level != 'superadmin' && auth()->user()->level != 'administrator' && auth()->user()->company->id != $report->user->company->id) || $report->supervisor_verifikasi != 'approved' || $report->surveyor_verifikasi != 'approved')
            // {
            //   return redirect()->back()->with('error', 'Tidak dapat untuk mencetak laporan ini');
            // }

            $option_limit = 25;
            $plate = get_plate_data($option_limit);
            $total_line = 11;

            if ($report && $report->status == 'deleted') {
                return 'Report has been deleted';
            }

            // dd($report_id);

            $ship = Ship::find($report->ship_id);
            $ship_type_id = $report->ship->ship_type_id;
            $ship_type_data = ShipTypeCategory::where('ship_type_id', $ship_type_id)->with('category')->orderBy('order')->get();
            $category_id = ShipTypeCategory::where('ship_type_id', $ship_type_id)->get()->pluck('category_id');
            $categories = Category::whereIn('id', $category_id)
                ->with(['images' => function ($q) use ($report_id) {
                    $q->where('report_id', $report_id);
                }])->get();

            $report_images = ReportImage::where('report_id', $report_id)->get()->keyBy('category_id');
            $certificates = Certification::where('report_id', $report_id)->get();

            $data = [];
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

            $data['report_id'] = $report_id;
            $data['categories'] = $categories;
            $data['form_data_all'] = $form_data_all;
            $data['plate'] = $plate;
            $data['total_line'] = $total_line;
            $data['option_limit'] = $option_limit;
            $data['report'] = $report;
            $data['ship'] = $ship;
            $data['report_images'] = $report_images;

            // Tambah halaman sertifikat perusahaan (portrait)
            if ($report->user->company->activeCertificate) {
                $certificate = $report->user->company->activeCertificate;
                $this->checkFile($certificate->certificate_file);
            }

            // Tambah halaman sertifikat personil (portrait)
            if ($report->user->latestQualification) {
//                $competency = $report->user->latestQualification;
//                $this->checkFile($competency->certificate_file);
            }

            // Tambah halaman sertifikat equipment (portrait)
            if ($report->equipment && $report->equipment->activeCertifications) {
                foreach ($report->equipment->activeCertifications as $certification) {
                    $this->checkFile($certification->url);
                }
            }

        }
    }

    protected function checkFile($file)
    {
        $exist = Storage::disk('digitalocean')->exists($file);

        echo $file . "::" . ($exist ? 'ada' : 'TIDAK ADA') . '<br/>';
    }

}
