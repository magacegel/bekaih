<?php

namespace App\Http\Controllers;

use App\Lib\ReportGenerator;
use App\Models\Category;
use App\Models\Certification;
use App\Models\Equipment;
use App\Models\Form;
use App\Models\FormDataOne;
use App\Models\FormDataThree;
use App\Models\FormDataTwo;
use App\Models\FormType;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\Ship;
use App\Models\ShipType;
use App\Models\ShipTypeCategory;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{

    public function index()
    {

        $ships = Ship::all();

        $ship_types = ShipType::all();

        return view('report.index', compact('ships', 'ship_types'));

    }

    public function report_submit(Request $request)
    {
        // dd($request->all());
        try {
            $report_id = $request->report_id;
            $report = Report::find($report_id);

            $user = auth()->user();
            $canSubmit = false;

            // Cek jika user adalah pembuat report
            if ($user->id == $report->user_id) {
                $canSubmit = true;
            } // Cek jika user adalah supervisor dari company yang sama
            else if ($user->hasRole('supervisor') && $user->company_id == $report->user->company_id) {
                $canSubmit = true;
            } // Cek jika user adalah superadmin atau administrator
            else if ($user->hasAnyRole(['superadmin', 'administrator'])) {
                $canSubmit = true;
            }

            if (!$canSubmit) {
                $reason = '';
                if ($user->id != $report->user_id && !$user->hasAnyRole(['superadmin', 'administrator'])) {
                    if (!$user->hasRole('supervisor')) {
                        $reason = 'Anda tidak memiliki akses sebagai pembuat laporan atau supervisor';
                    } else if ($user->company_id != $report->user->company_id) {
                        $reason = 'Anda bukan supervisor dari perusahaan yang membuat laporan ini';
                    }
                }
                return redirect()->back()->with('error', 'Tidak dapat submit laporan ini. ' . $reason);
            }

            DB::beginTransaction();

            $report->submit_date = date('Y-m-d');
            $report->supervisor_verifikasi = null;
            $report->surveyor_verifikasi = null;
            $report->surveyor_date = null;
            $report->supervisor_date = null;
            $report->supervisor_id = null;
            // dd($report);
            $report->save();

            // dd($report);


            // Simpan history
            $history = new \App\Models\ReportHistory();
            $history->report_id = $report_id;
            $history->user_id = auth()->user()->id;
            $history->status = 'submitted';
            $history->actor_type = \App\Models\ReportHistory::ACTOR_OPERATOR;
            $history->notes = 'Report submitted by operator';
            $history->save();

            // Simpan history untuk form
            foreach ($report->form as $form) {
                $form_history = new \App\Models\FormHistory();
                $form_history->form_id = $form->id;
                $form_history->user_id = auth()->user()->id;
                $form_history->status = 'submitted';
                $form_history->actor_type = 'operator';
                $form_history->notes = 'Form submitted by operator';
                $form_history->save();
            }

            DB::commit();

            ReportGenerator::createPages($report);

            return redirect()->route('report.report_detail', base64_encode($report_id))->with('success', 'Report submitted successfully');

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat submit laporan: ' . $e->getMessage());
        }
    }


    public function report_detail($page_id)
    {


        $report_id = base64_decode($page_id);
        if (is_numeric($report_id)) {
            $report = Report::where('id', $report_id)->with('ship', 'user')->first();

            if (!auth()->user()->hasAnyRole(['superadmin', 'administrator']) && auth()->user()->company->id != $report->company->id) {
                return 'Not enough credentials';
            }


            // $option_limit = 25;
            $option_limit = 60;
            $plate = get_plate_data($option_limit);
            $total_line = 11;


            if ($report && $report->status == 'deleted') {
                return 'Report has been deleted';
            }


            $ship_type_id = $report->ship->ship_type_id;
            $ship_type = ShipType::find($ship_type_id);


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
                        $q->where('report_id', $report_id)->orderBy('id', 'ASC');
                        // $q->where('report_id', $report_id)->orderBy('name','ASC');
                    }, 'form.form_data_one', 'form.form_data_two', 'form.report.user', 'form.report.ship'
                ]);

                $form_data_all[$category->id] = $form_type;
            }

            // $editable = false;
            $editable = true;

            // $status = 'draft';
            $status = 'waiting_for_approval';
            // $status = 'approved';

            return view('report.report_detail', compact('page_id', 'report', 'report_id', 'categories', 'form_data_all', 'plate', 'total_line', 'option_limit', 'report_images', 'certificates', 'ship_type', 'ship_type_id', 'editable', 'status'));

        }
    }

    public function report_print_pdf($id)
    {

        // Mengubah batas waktu eksekusi menjadi 3600 detik (1 jam)
        ini_set('max_execution_time', 3600);

        // Mengubah batas memori menjadi 512 MB
        ini_set('memory_limit', '512M');
        $report_id = base64_decode($id);
        if ($report_id && is_numeric($report_id)) {
            $report = Report::where('id', $report_id)->with('ship')->first();

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
            $data['certificates'] = $certificates;

            $pdf_name = '' . $ship->name . ' [' . $report->report_number . '].pdf';

            dd(json_encode($data));
            return view('report.report_pdf', $data);

            // Membuat PDF dengan Dompdf
            $pdf = \PDF::loadView('report.report_pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            // return $pdf->stream($pdf_name);

            // Menyimpan PDF hasil Dompdf ke temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'dompdf_');
            file_put_contents($tempFile, $pdf->output());

            // Membuat objek FPDI
            $fpdi = new \setasign\Fpdi\Fpdi();

            // Menambahkan halaman pertama dari PDF hasil Dompdf (portrait)
            $pageCount = $fpdi->setSourceFile($tempFile);
            $templateId = $fpdi->importPage(1);
            $fpdi->AddPage('P'); // 'P' untuk portrait
            $fpdi->useTemplate($templateId, 0, 0, null, null, true);

            // Menambahkan sertifikat aktif dari perusahaan pengguna yang membuat laporan
            $activeCertificate = $report->user->company->activeCertificate;
            if ($activeCertificate && Storage::disk('digitalocean')->exists($activeCertificate->certificate_file)) {
                $tempCertFile = tempnam(sys_get_temp_dir(), 'cert_');
                file_put_contents($tempCertFile, Storage::disk('digitalocean')->get($activeCertificate->certificate_file));

                $certificatePageCount = $fpdi->setSourceFile($tempCertFile);
                for ($pageNo = 1; $pageNo <= $certificatePageCount; $pageNo++) {
                    $templateId = $fpdi->importPage($pageNo);
                    $fpdi->AddPage('P');
                    $fpdi->useTemplate($templateId);
                }

                unlink($tempCertFile);
            }

            // // Menambahkan sertifikat kalibrasi aktif dari peralatan yang digunakan
            // $activeCalibrationCertificates = $report->equipment->certifications()->where('active', 1)->get();
            // foreach ($activeCalibrationCertificates as $activeCalibrationCertificate) {
            //     if (Storage::disk('digitalocean')->exists($activeCalibrationCertificate->url)) {
            //         $calibrationPageCount = $fpdi->setSourceFile(Storage::disk('digitalocean')->get($activeCalibrationCertificate->url));
            //         for ($pageNo = 1; $pageNo <= $calibrationPageCount; $pageNo++) {
            //             $templateId = $fpdi->importPage($pageNo);
            //             $fpdi->AddPage('P');
            //             $fpdi->useTemplate($templateId);
            //         }
            //     }
            // }

            // // Menambahkan sisa halaman dari PDF hasil Dompdf (landscape)
            $fpdi->setSourceFile($tempFile);
            for ($pageNo = 2; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $fpdi->importPage($pageNo);
                $fpdi->AddPage('L'); // 'L' untuk landscape
                $fpdi->useTemplate($templateId);
            }

            // // Menghapus temporary file
            unlink($tempFile);

            // Menghasilkan output PDF
            return $fpdi->Output($pdf_name, 'I');
        }
    }

    public function report_print_tcpdf($id)
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

            // dd($data['form_data_all']);

            // return view('report.components.form1', $data);
            // return view('report.components.toc', $data);
            // return view('report.components.general-particular', $data);
            // $pdftest = PDF::loadView('report.components.form1', $data);
            // $pdftest->setPaper('A4', 'landscape');
            // return $pdftest->stream();
            // Initialize TCPDF
            // Create PDF using DomPDF
            // Create PDF for cover page (portrait)
            $pdf = new \setasign\Fpdi\Fpdi();

            // Add cover page (portrait)
            // ob_start();
            $coverPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.cover', $data);
            $coverPdf->setPaper('A4', 'portrait');
            // $coverContent = ob_get_clean();
            $pdf->addPage('P', 'A4');
            $pdf->setSourceFile(\setasign\Fpdi\PdfParser\StreamReader::createByString($coverPdf->output()));
            $pdf->useTemplate($pdf->importPage(1));

            // Tambah halaman general particular (portrait)
            $gpPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.general-particular', $data);
            $gpPdf->setPaper('A4', 'portrait');
            $pdf->addPage('P', 'A4');
            $pdf->setSourceFile(\setasign\Fpdi\PdfParser\StreamReader::createByString($gpPdf->output()));
            $pdf->useTemplate($pdf->importPage(1));

            // Tambah halaman daftar isi (portrait)
            $tocPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.toc', $data);
            $tocPdf->setPaper('A4', 'portrait');
            $pdf->addPage('P', 'A4');
            $pdf->setSourceFile(\setasign\Fpdi\PdfParser\StreamReader::createByString($tocPdf->output()));
            $pdf->useTemplate($pdf->importPage(1));

            // Tambah halaman sertifikat perusahaan (portrait)
            if ($report->user->company->activeCertificate) {
                $certificate = $report->user->company->activeCertificate;
                if ($certificate->certificate_file
                    && $certificate->certificate_file != '[]'
                    && Storage::disk('digitalocean')->exists($certificate->certificate_file)) {
                    try {
                        // Ambil URL sertifikat dari storage
                        $certificateUrl = Storage::disk('digitalocean')->temporaryUrl($certificate->certificate_file, now()->addMinutes(5));

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

                            // Tambah halaman sertifikat ke PDF
                            $pdf->addPage('P', 'A4');

                            // Import halaman sertifikat dari file temporary
                            $pdf->setSourceFile($tempFile);
                            $pdf->useTemplate($pdf->importPage(1));

                            // Hapus file temporary
                            unlink($tempFile);
                        }
                    } catch (\Exception $e) {
                        // Handle error jika gagal membaca sertifikat
                        \Log::error('Error reading certificate: ' . $e->getMessage());
                    }
                }
            }

            // Tambah halaman sertifikat personil (portrait)
            if ($report->user->latestQualification) {
                $competency = $report->user->latestQualification;

                if ($competency->certificate_file
                    && $competency->certificate_file != '[]'
                    && Storage::disk('digitalocean')->exists($competency->certificate_file)) {
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

                            // Tambah halaman sertifikat ke PDF
                            $pdf->addPage('P', 'A4');

                            // Import halaman sertifikat dari file temporary
                            $pdf->setSourceFile($tempFile);
                            $pdf->useTemplate($pdf->importPage(1));

                            // Hapus file temporary
                            unlink($tempFile);
                        }
                    } catch (\Exception $e) {
                        // Handle error jika gagal membaca sertifikat
                        \Log::error('Error reading personnel certificate: ' . $e->getMessage());
                    }
                }
            }

            // Tambah halaman sertifikat equipment (portrait)
            if ($report->equipment && $report->equipment->activeCertifications) {
                foreach ($report->equipment->activeCertifications as $certification) {
                    if ($certification->url
                        && $certification->url != '[]'
                        && Storage::disk('digitalocean')->exists($certification->url)) {
                        try {
                            // Ambil URL sertifikat dari storage
                            $certificateUrl = Storage::disk('digitalocean')->temporaryUrl($certification->url, now()->addMinutes(5));

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

                                // Tambah halaman sertifikat ke PDF
                                $pdf->addPage('P', 'A4');

                                // Import halaman sertifikat dari file temporary
                                $pdf->setSourceFile($tempFile);
                                $pdf->useTemplate($pdf->importPage(1));

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

            // Add form page (landscape)
            // ob_start();
            $formPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.components.form1', $data);
            $formPdf->setPaper('A4', 'landscape');

            // Get form PDF content
            $formPdfContent = \setasign\Fpdi\PdfParser\StreamReader::createByString($formPdf->output());
            $pdf->setSourceFile($formPdfContent);

            // Get total pages
            $pageCount = $pdf->setSourceFile($formPdfContent);

            // Import all pages
            for ($i = 1; $i <= $pageCount; $i++) {
                $pdf->addPage('L', 'A4');
                $pdf->useTemplate($pdf->importPage($i));
            }

            // Set filename
            $pdf_name = $ship->name . ' [' . $report->report_number . '].pdf';

            // Output PDF
            return response($pdf->Output('S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $pdf_name . '"');
        }
    }


    public function report_preview($id)
    {

        $report_id = base64_decode($id);
        if ($report_id && is_numeric($report_id)) {
            $report = Report::where('id', $report_id)->with(['ship', 'surveyor'])->first();

            if (!auth()->user()->hasAnyRole(['superadmin', 'administrator']) && auth()->user()->company->id != $report->company->id) {
                return 'Not enough credentials';
            }

            $option_limit = 25;
            $plate = get_plate_data($option_limit);
            $total_line = 11;


            if ($report && $report->status == 'deleted') {
                return 'Report has been deleted';
            }


            $ship = Ship::find($report->ship_id);

            $ship_type_id = $report->ship->ship_type_id;

            $ship_type_data = ShipTypeCategory::where('ship_type_id', $ship_type_id)->with('category')->orderBy('order')->get();

            $category_id = ShipTypeCategory::where('ship_type_id', $ship_type_id)->get()->pluck('category_id');

            $categories = Category::whereIn('id', $category_id)
                ->with(['images' => function ($q) use ($report_id) {
                    $q->where('report_id', $report_id);
                }])->get();

            $report_images = ReportImage::where('report_id', $report_id)->get()->keyBy('category_id');
            $certificates = $report->equipment->activeCertifications ?? collect();

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

            $pdf_name = '' . $ship->name . ' [' . $report->report_number . '].pdf';

            // Tambahkan header untuk mencegah IDM auto download
            // header('X-Download-Options: noopen');
            // header('X-Content-Type-Options: nosniff');
            // header('Content-Disposition: inline');

            // $status = 'draft';
            $status = 'waiting_for_approval';
            // $status = 'approved';

            return view('report.report_preview', compact('report_id', 'categories', 'form_data_all', 'plate', 'total_line', 'option_limit', 'report', 'ship', 'report_images', 'certificates', 'status'));

        }

    }

    public function report_supervisor_review($id)
    {

        // Cara buat enkripsinya mas bay untuk di new bki
        // $id yang digunakan dibawah itu udah dalam bentuk base64 ya mas bay
        // $count_equals = substr_count($id, '=');
        // $id_without_equals = str_replace('=', '', $id);
        // $combined_id = $id_without_equals ."$".($count_equals == 0 ? '' : $count_equals). env('APP_KEY_LITE');

        // $encoded_id = base64_encode($combined_id);  //nahh ini enkripsi nya mas bay sebagai id untuk di button dalem app new bki

        // dd($encoded_id);

        // ini decode enkripsi mas bay yang diatas itu

        try {
            $report_id = base64_decode($id);
        } catch (\Exception $e) {
            abort(403, 'Akses tidak sah');
        }


        // $report_id = base64_decode($id);

        if ($report_id && is_numeric($report_id)) {
            $report = Report::where('id', $report_id)->with(['ship', 'surveyor'])->first();

            if (!auth()->user()->hasAnyRole(['superadmin', 'supervisor']) && auth()->user()->company_id != $report->user->company_id) {
                abort(403, 'Akses tidak sah');
            }

            $option_limit = 25;
            $plate = get_plate_data($option_limit);
            $total_line = 11;


            if ($report && $report->status == 'deleted') {
                return 'Report has been deleted';
            }


            $ship = Ship::find($report->ship_id);

            $ship_type_id = $report->ship->ship_type_id;

            $ship_type_data = ShipTypeCategory::where('ship_type_id', $ship_type_id)->with('category')->orderBy('order')->get();

            $category_id = ShipTypeCategory::where('ship_type_id', $ship_type_id)->get()->pluck('category_id');

            $categories = Category::whereIn('id', $category_id)
                ->with(['images' => function ($q) use ($report_id) {
                    $q->where('report_id', $report_id);
                }])->get();

            $report_images = ReportImage::where('report_id', $report_id)->get()->keyBy('category_id');


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


            $pdf_name = '' . $ship->name . ' [' . $report->report_number . '].pdf';

            $certificates = Certification::where('report_id', $report_id)->get();

            // $status = 'draft';
            $status = 'waiting_for_approval';
            // $status = 'approved';

            return view('report.report_supervisor_review', compact('report_id', 'categories', 'form_data_all', 'plate', 'total_line', 'option_limit', 'report', 'ship', 'report_images', 'certificates', 'status'));

        }

    }

    public function supervisor_approve(Form $form)
    {
        try {
            DB::beginTransaction();

            if (!auth()->user()->hasAnyRole(['superadmin', 'supervisor']) && auth()->user()->company_id != $form->report->user->company_id) {
                abort(403, 'Akses tidak sah');
            }

            $form->supervisor_verifikasi = 'approved';
            $form->supervisor_date = \Carbon\Carbon::now();
            $form->save();

            // Simpan form history
            $formHistory = new \App\Models\FormHistory();
            $formHistory->form_id = $form->id;
            $formHistory->user_id = auth()->user()->id;
            $formHistory->status = 'approved';
            $formHistory->actor_type = 'supervisor';
            $formHistory->notes = 'Form disetujui oleh supervisor';
            $formHistory->save();

            // Hitung jumlah form yang direvisi, disetujui, dan pending
            $revisedCount = $form->report->form()->where('supervisor_verifikasi', 'revised')->count();
            $approvedCount = $form->report->form()->where('supervisor_verifikasi', 'approved')->count();
            $pendingCount = $form->report->form()->whereNull('supervisor_verifikasi')->count();

            DB::commit();

            ReportGenerator::createPages($form->report);

            return response()->json([
                'message' => 'Disetujui',
                'revisedCount' => $revisedCount,
                'approvedCount' => $approvedCount,
                'pendingCount' => $pendingCount
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function supervisor_revise(Request $request, Form $form)
    {
        try {
            DB::beginTransaction();

            if (!auth()->user()->hasAnyRole(['superadmin', 'supervisor']) && auth()->user()->company_id != $form->report->user->company_id) {
                abort(403, 'Akses tidak sah');
            }

            if (empty($request->comment)) {
                return response()->json(['message' => 'Komentar wajib diisi untuk revisi'], 422);
            }

            $form->supervisor_verifikasi = 'revised';
            $form->supervisor_date = \Carbon\Carbon::now();
            $form->supervisor_notes = $request->comment;
            $form->save();

            // Simpan form history
            $formHistory = new \App\Models\FormHistory();
            $formHistory->form_id = $form->id;
            $formHistory->user_id = auth()->user()->id;
            $formHistory->status = 'revised';
            $formHistory->actor_type = 'supervisor';
            $formHistory->notes = 'Form perlu direvisi: ' . $request->comment;
            $formHistory->save();

            $form->report->supervisor_verifikasi = 'revised';
            $form->report->supervisor_date = \Carbon\Carbon::now();
            $form->report->save();

            // Hitung jumlah form yang direvisi, disetujui, dan pending
            $revisedCount = $form->report->form()->where('supervisor_verifikasi', 'revised')->count();
            $approvedCount = $form->report->form()->where('supervisor_verifikasi', 'approved')->count();
            $pendingCount = $form->report->form()->whereNull('supervisor_verifikasi')->count();

            DB::commit();

            ReportGenerator::createPages($form->report);

            return response()->json([
                'message' => 'Perlu Revisi',
                'revisedCount' => $revisedCount,
                'approvedCount' => $approvedCount,
                'pendingCount' => $pendingCount
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function supervisor_submit($id)
    {
        try {
            DB::beginTransaction();

            $report_id = base64_decode($id);
            $report = Report::find($report_id);

            if (!auth()->user()->hasAnyRole(['superadmin', 'supervisor']) && auth()->user()->company_id != $report->user->company_id) {
                abort(403, 'Akses tidak sah');
            }

            $forms = $report->form;

            // Cek apakah ada form yang belum terisi supervisor_verifikasinya
            foreach ($forms as $form) {
                if (is_null($form->supervisor_verifikasi)) {
                    return response()->json(['message' => 'Masih ada form yang belum diverifikasi oleh supervisor'], 422);
                }
            }

            // Cek apakah ada form yang direvisi
            $revisedFormExists = $forms->contains(function ($form) {
                return $form->supervisor_verifikasi === 'revised';
            });

            if (!$revisedFormExists) {
                $report->supervisor_verifikasi = 'approved';
            }

            $report->supervisor_date = Carbon::now();
            $report->save();

            // Simpan history
            $history = new \App\Models\ReportHistory();
            $history->report_id = $report_id;
            $history->user_id = auth()->user()->id;
            $history->status = $revisedFormExists ? 'revised' : 'approved';
            $history->actor_type = \App\Models\ReportHistory::ACTOR_SUPERVISOR;
            $history->notes = $revisedFormExists ? 'Report perlu direvisi' : 'Report disetujui oleh supervisor';
            $history->save();

            DB::commit();

            ReportGenerator::createPages($report);

            return redirect()->route('report.index')->with('success', 'Laporan telah disubmit ke Surveyor');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . "  " . $e->getTraceAsString());
        }
    }

    public function report_surveyor_review($id)
    {

        // dd($id);
        // Cara buat enkripsinya mas bay untuk di new bki
        // $id yang digunakan dibawah itu udah dalam bentuk base64 ya mas bay
        // $count_equals = substr_count($id, '=');
        // $id_without_equals = str_replace('=', '', $id);
        // $combined_id = $id_without_equals ."$".($count_equals == 0 ? '' : $count_equals). env('APP_KEY_LITE');

        // $encoded_id = base64_encode($combined_id);  //nahh ini enkripsi nya mas bay sebagai id untuk di button dalem app new bki

        // dd($encoded_id);

        // ini decode enkripsi mas bay yang diatas itu
        try {
            $decoded_id = base64_decode($id);
            $delete_applite = str_replace(env('APP_KEY_LITE'), '', $decoded_id);
            $parts = explode('$', $delete_applite);
            $equals = str_repeat('=', (!is_numeric($parts[1]) ? 0 : $parts[1]));
            $report_id = base64_decode($parts[0] . $equals);
            // dd($report_id);
        } catch (\Exception $e) {
            abort(403, 'Akses tidak sah');
        }


        // $report_id = base64_decode($id);

        if ($report_id && is_numeric($report_id)) {
            $report = Report::where('id', $report_id)->with(['ship', 'surveyor'])->first();

            // Validasi akses berdasarkan kondisi surveyor verifikasi
            $canAccess = false;
            
            // Kondisi 1: supervisor_verifikasi = approved & surveyor_verifikasi = revised
            if ($report->supervisor_verifikasi == 'approved' && $report->surveyor_verifikasi == 'revised') {
                $canAccess = true;
            }
            
            // Kondisi 2: supervisor_verifikasi = approved & surveyor_verifikasi masih kosong
            if ($report->supervisor_verifikasi == 'approved' && is_null($report->surveyor_verifikasi)) {
                $canAccess = true;
            }
            
            // Jika tidak memenuhi kondisi, abort dengan error 403
            if (!$canAccess) {
                abort(403, 'Laporan ini tidak dapat diakses. Laporan harus sudah disetujui supervisor dan dalam tahap verifikasi surveyor.');
            }
            
            $option_limit = 25;
            $plate = get_plate_data($option_limit);
            $total_line = 11;


            if ($report && $report->status == 'deleted') {
                return 'Report has been deleted';
            }


            $ship = Ship::find($report->ship_id);

            $ship_type_id = $report->ship->ship_type_id;

            $ship_type_data = ShipTypeCategory::where('ship_type_id', $ship_type_id)->with('category')->orderBy('order')->get();

            $category_id = ShipTypeCategory::where('ship_type_id', $ship_type_id)->get()->pluck('category_id');

            $categories = Category::whereIn('id', $category_id)
                ->with(['images' => function ($q) use ($report_id) {
                    $q->where('report_id', $report_id);
                }])->get();

            $report_images = ReportImage::where('report_id', $report_id)->get()->keyBy('category_id');


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


            $pdf_name = '' . $ship->name . ' [' . $report->report_number . '].pdf';

            $certificates = Certification::where('report_id', $report_id)->get();

            // $status = 'draft';
            $status = 'waiting_for_approval';
            // $status = 'approved';

            return view('report.report_surveyor_review', compact('report_id', 'categories', 'form_data_all', 'plate', 'total_line', 'option_limit', 'report', 'ship', 'report_images', 'certificates', 'status'));

        }

    }

    public function surveyor_approve(Form $form)
    {
        try {
            DB::beginTransaction();

            $form->surveyor_verifikasi = 'approved';
            $form->surveyor_date = \Carbon\Carbon::now();
            $form->save();

            // Simpan form history
            $form_history = new \App\Models\FormHistory();
            $form_history->form_id = $form->id;
            $form_history->user_id = $form->report->surveyor_nup;
            $form_history->status = 'approved';
            $form_history->actor_type = 'surveyor';
            $form_history->notes = 'Form disetujui oleh surveyor';
            $form_history->save();

            // Periksa status surveyor_verifikasi untuk semua form dalam report ini
            $allFormsApproved = Form::where('report_id', $form->report_id)
                    ->whereNotIn('surveyor_verifikasi', ['approved'])
                    ->count() == 0;

            if ($allFormsApproved) {
                // Jika semua form sudah disetujui, ubah status report menjadi approved
                $form->report->surveyor_verifikasi = 'approved';
                $form->report->surveyor_date = \Carbon\Carbon::now();
                $form->report->save();
            }

            // Hitung jumlah form yang direvisi, disetujui, dan pending
            $revisedCount = $form->report->form()->where('supervisor_verifikasi', 'revised')->count();
            $approvedCount = $form->report->form()->where('supervisor_verifikasi', 'approved')->count();
            $pendingCount = $form->report->form()->whereNull('supervisor_verifikasi')->count();

            DB::commit();

            ReportGenerator::createPages($form->report);

            return response()->json([
                'message' => 'Disetujui',
                'revisedCount' => $revisedCount,
                'approvedCount' => $approvedCount,
                'pendingCount' => $pendingCount
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function surveyor_revise(Request $request, Form $form)
    {
        try {
            if (empty($request->comment)) {
                return response()->json(['message' => 'Komentar wajib diisi untuk revisi'], 422);
            }

            DB::beginTransaction();

            $form->surveyor_verifikasi = 'revised';
            $form->surveyor_date = \Carbon\Carbon::now();
            $form->surveyor_notes = $request->comment;
            $form->save();

            // Simpan form history
            $form_history = new \App\Models\FormHistory();
            $form_history->form_id = $form->id;
            $form_history->user_id = $form->report->surveyor_nup;
            $form_history->status = 'revised';
            $form_history->actor_type = 'surveyor';
            $form_history->notes = $request->comment;
            $form_history->save();

            $form->report->surveyor_verifikasi = 'revised';
            $form->report->surveyor_date = \Carbon\Carbon::now();
            $form->report->save();

            // Hitung jumlah form yang direvisi, disetujui, dan pending
            $revisedCount = $form->report->form()->where('supervisor_verifikasi', 'revised')->count();
            $approvedCount = $form->report->form()->where('supervisor_verifikasi', 'approved')->count();
            $pendingCount = $form->report->form()->whereNull('supervisor_verifikasi')->count();

            DB::commit();

            ReportGenerator::createPages($form->report);

            return response()->json([
                'message' => 'Perlu Revisi',
                'revisedCount' => $revisedCount,
                'approvedCount' => $approvedCount,
                'pendingCount' => $pendingCount
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function surveyor_submit($id)
    {
        try {
            DB::beginTransaction();

            $report_id = base64_decode($id);
            $report = Report::find($report_id);

            $forms = $report->form;

            // Cek apakah ada form yang belum terisi surveyor_verifikasinya
            foreach ($forms as $form) {
                if (is_null($form->surveyor_verifikasi)) {
                    return response()->json(['message' => 'Masih ada form yang belum diverifikasi oleh surveyor'], 422);
                }
            }

            // Cek apakah ada form yang direvisi
            $revisedFormExists = $forms->contains(function ($form) {
                return $form->surveyor_verifikasi === 'revised';
            });

            if (!$revisedFormExists) {
                $report->surveyor_verifikasi = 'approved';
            }

            $report->surveyor_date = Carbon::now();
            $report->save();

            // Simpan history
            $history = new \App\Models\ReportHistory();
            $history->report_id = $report_id;
            $history->user_id = \App\Models\User::where('nup', $report->surveyor_nup)->first()->id;
            $history->status = $revisedFormExists ? 'revised' : 'approved';
            $history->actor_type = \App\Models\ReportHistory::ACTOR_SURVEYOR;
            $history->notes = $revisedFormExists ? 'Report perlu direvisi' : 'Report disetujui oleh surveyor';
            $history->save();

            DB::commit();

            ReportGenerator::createPages($report);

            return redirect()->route('report.index')->with('success', 'Laporan telah disubmit ke Surveyor');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . "  " . $e->getTraceAsString());
        }
    }

    public function report_print($id)
    {
        $report_id = base64_decode($id);
        if ($report_id) {
            $report = Report::where('id', $report_id)->with('ship')->first();

            if (!auth()->user()->hasAnyRole(['superadmin', 'administrator']) && auth()->user()->id != $report->user_id) {
                return 'Not enough credentials';
            }

            $option_limit = 25;
            $plate = get_plate_data($option_limit);
            $total_line = 11;


            if ($report && $report->status == 'deleted') {
                return 'Report has been deleted';
            }


            $ship = Ship::find($report->ship_id);

            $ship_type_id = $report->ship->ship_type_id;

            $ship_type_data = ShipTypeCategory::where('ship_type_id', $ship_type_id)->with('category')->orderBy('order')->get();

            $category_id = ShipTypeCategory::where('ship_type_id', $ship_type_id)->get()->pluck('category_id');

            $categories = Category::whereIn('id', $category_id)
                ->with(['images' => function ($q) use ($report_id) {
                    $q->where('report_id', $report_id);
                }])->get();

            $report_images = ReportImage::where('report_id', $report_id)->get()->keyBy('category_id');


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


            return view('report.report_print', compact('report_id', 'categories', 'form_data_all', 'plate', 'total_line', 'option_limit', 'report', 'ship', 'report_images'));
        }
    }


    public function report_data(Request $request)
    {


        $response['status'] = 'error';
        $response['message'] = '';

        if ($request->action == 'create') {
            $no_reg = $request->no_reg;

            $ship = Ship::where('no_reg', $no_reg)->first();
            if ($ship) {
                $ship_id = $ship->id;
            } else {
                $data['ship_type_id'] = $request->ship_type_id;
                $data['classification'] = $request->ship_classification;
                $data['name'] = $request->nama_kapal;
                $data['no_reg'] = $request->no_reg;
                $data['no_imo'] = $request->no_imo;
                $data['ship_type'] = $request->ship_type;
                $data['weight'] = $request->ship_weight;
                $data['loa'] = $request->ship_loa;
                $data['breadth'] = $request->ship_breadth;
                $data['depth'] = $request->ship_height;
                $data['owner'] = $request->ship_owner;
                $data['owner_city'] = $request->owner_city;

                $ship = Ship::create($data);
                $ship_id = $ship->id;
            }

            $report_data = [
                'user_id' => auth()->user()->id,
                'company_id' => auth()->user()->company_id,
                'ship_id' => $ship_id,
                'name' => $request->report_name,
                'report_number' => $request->report_number,
                'report_date' => date('Y-m-d'),
                'kind_of_survey' => $request->kind_of_survey,
                'surveyor_nup' => $request->surveyor,
                'province' => $request->province,
                'city' => $request->city,
                'location' => $request->location,
            ];

            if ($request->has('date_of_measurement')) {
                $report_data['date_of_measurement'] = $request->date_of_measurement;
            }

            if ($request->has('end_date_measurement')) {
                $report_data['end_date_measurement'] = $request->end_date_measurement;
            }

            if ($request->has('equipment_id')) {
                if ($request->equipment_id == 'new') {
                    $equipment = Equipment::create([
                        'name' => $request->new_equipment_name,
                        'manufactur' => $request->new_equipment_manufacturer,
                        'model' => $request->new_equipment_model,
                        'serial_number' => $request->new_equipment_serial,
                        'tolerance' => $request->new_equipment_tolerance,
                        'probe_type' => $request->new_equipment_probe_type,
                        'user_id' => auth()->user()->id
                    ]);
                    $report_data['equipment_id'] = $equipment->id;
                } else {
                    $report_data['equipment_id'] = $request->equipment_id;
                }
            }

            $report = Report::create($report_data);

            if ($report) {
                $response['status'] = 'success';
                $response['message'] = 'Report has been created';
            }
        }

        if ($request->action == 'delete') {

            $report_id = $request->report_id;

            if ($report_id) {
                $form_id = Form::where('report_id', $report_id)->get()->pluck('id');

                if (count($form_id)) {
                    // Ini yang diganti statusnya saja
                    $report = Report::where('id', $report_id)->first();
                    if ($report) {
                        $report->status = 'deleted';
                        $report->updated_by = Auth::user()->id;
                        $report->save();
                    }
                } else {
                    // ini yang beneran dihapus
                    foreach ($form_id as $id) {

                        $form = Form::where('id', $id)->with('form_type')->first();

                        if ($form->form_type->form_data_format == 'one') {
                            FormDataOne::where('form_id', $id)->delete();
                        }

                        if ($form->form_type->form_data_format == 'two') {
                            FormDataTwo::where('form_id', $id)->delete();
                        }

                        if ($form->form_type->form_data_format == 'three') {
                            FormDataThree::where('form_id', $id)->delete();
                        }
                    }

                    Form::where('report_id', $report_id)->delete();
                    Report::where('id', $report_id)->delete();
                }


            }

            $response['status'] = 'success';
            $response['message'] = 'Report has been deleted';

        }

        if ($request->action == 'get_form_type') {

            $category_id = $request->category_id;
            $ship_type_id = $request->ship_type_id;
            $form_name_list = ShipTypeCategory::where('ship_type_id', $ship_type_id)->where('category_id', $category_id)->get()->keyBy('form_type_id')->toArray();

            if ($category_id && $ship_type_id) {
                $form_type_id = ShipTypeCategory::where('ship_type_id', $ship_type_id)->where('category_id', $category_id)->get()->pluck('form_type_id');
                $form_types = FormType::whereIn('id', $form_type_id)->get();

                $response['data'] = $form_types;

            }

            $response['status'] = 'success';
            $response['message'] = '';

        }


        return response()->json($response);

    }


    public function report_datatables()
    {
        if (auth()->user()->hasAnyRole(['superadmin', 'administrator'])) {
            $tmp = Report::where('status', '!=', 'deleted')->with(['ship', 'form', 'user']);
        } else {
            $tmp = Report::where('status', '!=', 'deleted')->where('company_id', auth()->user()->company_id)->with(['ship', 'form', 'user']);
        }

        return Datatables::of($tmp)
            ->addColumn('action', function ($tmp) {
                // Check if certificate is expired
                $user = auth()->user();
                $company = $user->company;
                $companyExpiredDate = $company->activeCertificate->expired_date ? \Carbon\Carbon::parse($company->activeCertificate->expired_date) : null;
                $userExpiredDate = $user->latestQualification ? \Carbon\Carbon::parse($user->latestQualification->expired_date) : null;

                $hasExpiredCompanyCert = $companyExpiredDate && $companyExpiredDate->lt(\Carbon\Carbon::now());
                $hasExpiredUserCert = $userExpiredDate && $userExpiredDate->lt(\Carbon\Carbon::now());

                $isExpired = $hasExpiredCompanyCert || $hasExpiredUserCert;

                $html = '';

                // Review button - hidden if expired
                if (!$isExpired && auth()->user()->hasRole('supervisor') && auth()->user()->company_id == $tmp->company_id && $tmp->submit_date && $tmp->supervisor_verifikasi != 'approved' && $tmp->supervisor_verifikasi != 'revised') {
                    $html .= '<button type="button" class="btn btn-warning btn-sm shadow-sm hover-scale me-1" onclick="window.location=\'' . url('supervisor_review/' . base64_encode($tmp->id)) . '\'" data-bs-toggle="tooltip" title="Review" style="border-radius: 4px;">
          <i class="fa fa-bolt"></i>
        </button>';
                }

                // View button - always shown
                $html .= '<button type="button" class="btn btn-info btn-sm shadow-sm hover-scale me-1 b_detail" id="' . base64_encode($tmp->id) . '" data-bs-toggle="tooltip" title="Detail" style="border-radius: 4px;">
        <i class="fa fa-eye"></i>
      </button>';

                // Print button - hidden if expired
                if (!$isExpired && !((auth()->user()->level != 'superadmin' && auth()->user()->level != 'administrator' && auth()->user()->company->id != $tmp->user->company->id) || $tmp->supervisor_verifikasi != 'approved' || $tmp->surveyor_verifikasi != 'approved')) {
                    $html .= '<button type="button" class="btn btn-secondary btn-sm shadow-sm hover-scale me-1 b_print" id="' . base64_encode($tmp->id) . '" data-bs-toggle="tooltip" title="Print" style="border-radius: 4px;">
          <i class="fa fa-print"></i>
        </button>';
                }

                // Delete button - hidden if expired
                if (!$isExpired && !$tmp->submit_date) {
                    $html .= '<button type="button" class="btn btn-danger btn-sm shadow-sm hover-scale b_delete" form_count="' . $tmp->form->count() . '" id="' . $tmp->id . '" data-bs-toggle="tooltip" title="Delete" style="border-radius: 4px;">
          <i class="fa fa-trash"></i>
        </button>';
                }
                return $html;
            })
            ->editColumn('date_of_measurement', function ($tmp) {
                return $tmp->date_of_measurement ? $tmp->date_of_measurement->format('d/m/Y') : '-';
            })
            ->editColumn('end_date_measurement', function ($tmp) {
                return $tmp->end_date_measurement ? $tmp->end_date_measurement->format('d/m/Y') : '-';
            })
            ->editColumn('submit_date', function ($tmp) {
                return $tmp->submit_date ? $tmp->submit_date->format('d/m/Y') : '-';
            })
            ->addIndexColumn()
            ->make(true);
    }


    public function report_image(Request $request)
    {
        $report_id = $request->report_id;
        $image_id = $request->image_id;
        $category_id = $request->category_id;
        $form_type_id = $request->form_type_id;

        if ($request->action == 'upload' || $request->action == 'upload_certificate') {
            $max_upload_size = 20000;
            $resize_limit = 1200;

            $validation = [
                'image' => 'required|mimes:jpeg,png,jpg|max:' . $max_upload_size
            ];

            $this->validate($request, $validation);

            if ($request->image) {
                $file = $request->image;
                $extension = $file->getClientOriginalExtension();
                $name = 'image_' . $category_id . '_' . date('YmdHis');
                $image_name = $name . '.' . $extension;
                $image_name_resized = $name . '_resized.' . $extension;

                $do_directory = env('DO0_DIRECTORY', 'staging');
                $company_id = auth()->user()->company_id;

                $report = Report::findOrFail($report_id);
                $ship_name = $report->ship->name;
                $report_year = date('Y', strtotime($report->date_of_measurement));

                $ship_name = preg_replace('/[^A-Za-z0-9\s]/', '', strtoupper($ship_name));
                $directory = $do_directory . '/' . $company_id . '/reports/' . $report_id . '-' . $report_year . '-' . $ship_name;

                if ($request->action == 'upload_certificate') {
                    $full_filename = $directory . '/certificates_calibration/' . $image_name;
                    $full_filename_resized = $directory . '/certificates_calibration/' . $image_name_resized;
                } else {
                    $full_filename = $directory . '/report_images/' . $image_name;
                    $full_filename_resized = $directory . '/report_images/' . $image_name_resized;
                }

                $upload_file = $request->file('image');

                $img = Image::make($upload_file);
                if ($img->height() > $img->width()) {
                    $img->rotate(90);
                }

                // Save original image
                $img->stream();
                Storage::disk('digitalocean')->put($full_filename, $img, 'private');

                // Save resized image
                if ($img->width() > $resize_limit || $img->height() > $resize_limit)
                    $img->resize($resize_limit, $resize_limit, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                $img->stream();
                Storage::disk('digitalocean')->put($full_filename_resized, $img, 'private');

                // Get temporary URLs
                $url = Storage::disk('digitalocean')->temporaryUrl($full_filename, now()->addMinutes(10));
                $url_resized = Storage::disk('digitalocean')->temporaryUrl($full_filename_resized, now()->addMinutes(10));

                if ($request->action == 'upload_certificate') {
                    $image = Certification::create(['report_id' => $report_id, 'title' => $request->title, 'description' => '', 'url' => $full_filename, 'url_resized' => $full_filename_resized]);
                } else {
                    $image = ReportImage::create(['report_id' => $report_id, 'category_id' => $category_id, 'form_type_id' => $form_type_id, 'url' => $full_filename, 'url_resized' => $full_filename_resized]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Image has been uploaded!',
                    'image' => $url,
                    'image_resized' => $url_resized,
                    'image_id' => $image->id,
                    'form_type_id' => $form_type_id
                ]);
            }
        }

        if ($request->action == 'delete' || $request->action == 'delete_certificate') {
            if ($request->action == 'delete_certificate') {
                $type = "Certificate";
                $image = Certification::where('id', $image_id)->first();
            } else {
                $type = "Image";
                $image = ReportImage::where('id', $image_id)->first();
            }

            if ($image) {
                if (Storage::disk('digitalocean')->exists($image->url)) {
                    Storage::disk('digitalocean')->delete($image->url);
                }
                if (Storage::disk('digitalocean')->exists($image->url_resized)) {
                    Storage::disk('digitalocean')->delete($image->url_resized);
                }

                $image->delete();

                return response()->json(['status' => 'success', 'message' => $type . ' has been deleted!']);
            }
        }
    }

    public function api_report_all(Request $request)
    {
        $query = Report::where('status', '!=', 'deleted')
            //  ->where('supervisor_verifikasi', 'approved')
            ->get()
            ->map(function ($report) {
                $encid = base64_encode($report->id);
                $count_equals = substr_count($encid, '=');
                $id_without_equals = str_replace('=', '', $encid);
                $combined_id = $id_without_equals . "$" . ($count_equals == 0 ? '' : $count_equals) . env('APP_KEY_LITE');

                $encoded_id = base64_encode($combined_id);
                $report->encid = $encoded_id;
                $report->ship_name = $report->ship->name;
                unset($report->ship);
                return $report;
            });

        return Datatables::of($query)->make(true);
    }

    public function api_report(Request $request, $id)
    {
        //$nup = "88512";
        //$nupenc = Crypt::encryptString($nup);
        //dd($nupenc);
        //$nupdec = Crypt::decryptString($nupenc);
        $iddecode = base64_decode($id);
        $splitpilihan = explode(';', $iddecode);
        $nupdecode = $splitpilihan[0];


        $nup = base64_decode($nupdecode);
        $query = Report::when($nup != '50991' && $nup != '88012', function ($q) use ($nup) {
            return $q->where('surveyor_nup', $nup);
        })
            ->where('status', '!=', 'deleted')
            ->where('supervisor_verifikasi', 'approved')
            //->where('supervisor_verifikasi', 'approved')
            ->get()->map(function ($report) {
                $encid = base64_encode($report->id);
                $count_equals = substr_count($encid, '=');
                $id_without_equals = str_replace('=', '', $encid);
                $combined_id = $id_without_equals . "$" . ($count_equals == 0 ? '' : $count_equals) . env('APP_KEY_LITE');

                $encoded_id = base64_encode($combined_id);
                $report->encid = $encoded_id;
                $report->ship_name = $report->ship->name;
                unset($report->ship);
                return $report;
            });

        //return DataTables::queryBuilder($query)->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        //return response()->json($nup);
        return Datatables::of($query)
            ->make(true);
    }

    // Menampilkan form edit general particular
    public function editGeneralParticular($id)
    {
        try {
            $report_id = base64_decode($id);

            if (!is_numeric($report_id)) {
                return redirect()->back()->with('error', 'ID laporan tidak valid');
            }

            $report = Report::with(['ship', 'user'])->findOrFail($report_id);

            // Cek akses
            if ((auth()->user()->level != 'superadmin' && auth()->user()->level != 'supervisor')
                && auth()->user()->company_id != $report->user->company_id) {
                abort(403, 'Akses tidak sah');
            }

            return view('report.edit_general_particular', compact('report'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Memproses update general particular
    public function updateGeneralParticular(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $report_id = base64_decode($id);
            if (!is_numeric($report_id)) {
                return redirect()->back()->with('error', 'ID laporan tidak valid');
            }

            $report = Report::with('user')->findOrFail($report_id);

            // Cek akses
            if ((auth()->user()->level != 'superadmin' && auth()->user()->level != 'supervisor')
                && auth()->user()->company_id != $report->user->company_id) {
                abort(403, 'Akses tidak sah');
            }

            // Update data report
            $report->update([
                'name' => $request->report_name,
                'report_number' => $request->report_number,
                'kind_of_survey' => $request->kind_of_survey,
                'location' => $request->location,
                'province' => $request->province,
                'city' => $request->city,
                'date_of_measurement' => $request->date_of_measurement,
                'end_date_measurement' => $request->end_date_measurement,
                'surveyor_nup' => $request->surveyor,
                'updated_by' => auth()->user()->id
            ]);

            // Simpan history
            $history = new \App\Models\ReportHistory();
            $history->report_id = $report_id;
            $history->user_id = auth()->user()->id;
            $history->status = 'updated';
            $history->actor_type = auth()->user()->level;
            $history->notes = 'General particular diupdate';
            $history->save();

            \Illuminate\Support\Facades\DB::commit();

            ReportGenerator::createPages($report);

            return redirect()->route('report.report_detail', base64_encode($report_id))
                ->with('success', 'Data general particular berhasil diupdate');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

}
