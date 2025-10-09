<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>{{ $report->report_number ?? '-' }} - {{ $report->user->company->name ?? '-' }}{{ $report->user->company->branch ? ' - ' . $report->user->company->branch : '' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Biro Klasifikasi Indonesia" name="description" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

  <!-- App Css-->
  <link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
  <!-- Summernote CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

  {{-- <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

  <style type="text/css">

    body  {
      background: #f3f3f9;
    }
    .main-content {
      width: 90%;
      margin:0 auto;
    }
    .approve_div {
      position: fixed;
      right: 10px;
      top: 10px;
      z-index: 9;
    }

    table {
      border-collapse: collapse;
    }
    .form_table {
      margin:10px 0 30px 0;
      width: 100%;
      border-spacing: initial;
    }
    .form_table td , .form_table th {
      text-align: center;
      padding: 1px 4px;
      border: 1px solid #555;
    }
    .font_16 td , .font_16 th {
      font-size: 16px;
    }
    .font_15 td , .font_15 th {
      font-size: 15px;
    }
    .font_14 td , .font_14 th {
      font-size: 14px;
    }
    .font_13 td , .font_13 th {
      font-size: 13px;
    }
    .font_12 td , .font_12 th {
      font-size: 12px;
    }
    .font_11 td , .font_11 th {
      font-size: 11px;
    }
    .font_10 td , .font_10 th {
      font-size: 10px;
    }
    .font_9 td , .font_9 th {
      font-size: 9px;
    }
    .font_8 td , .font_8 th {
      font-size: 8px;
    }

    .add_form_btn {
      float: right;
    }
    .report_container {
      width: 100%;
    }
    .header_description {
      font-style: italic;
      font-weight: normal;
      font-size: smaller;
    }

    .header_table {
      width: 100%;
      margin-top: 10px;
    }
    .header_table .logo
    {
      width: 80px;
    }
    .header_table .logo img
    {
      width: 60px;
      height: auto;
    }
    .header_table .bki_title {
      width: 300px;
      font-weight: bold;
      padding-left: 20px;
      padding-top: 10px;
    }
    .header_table .form_title {
      text-align: center;
      font-weight: bold;

    }
    .sub_header_table {
      width: 100%;
      margin-top: 8px;
    }
    .sub_header_table td {
      width: 33.33%;
    }
    .image_area {
      margin-top: 120px;
      text-align: center;
    }
    .image_area img {
      width: auto;
      height: 100%;
      margin-bottom: 20px;
      max-width: 1066px;
      max-height: 720px;
    }
    .print_btn {
      position: fixed;
      right: 20px;
      top: 20px;
    }

    .rotate_header
    {
      margin-top: 30px;
      width: 800px;
      margin: 0 auto;
    }


    .category_title {
      margin: 0 auto;
      margin-top: 10px;
      margin-bottom: 50px;
      width: 80%;
    }

    .image_area {
    }
    .rotate {
    }

    .landscape_one {
      padding-bottom:60px;
      /*      border-bottom: 1px solid #ccc;*/
    }
    .landscape_two {
      padding-bottom:60px;
      /*      border-bottom: 1px solid #ccc;*/
    }

    .landscape_three {
      padding-bottom:60px;
      /*      border-bottom: 1px solid #ccc;*/
    }
    .category_title_area {
      border-bottom: 1px solid #ccc;
    }

    .pagebreak {
      clear: both;
      /*      border-bottom: 1px solid #ccc;*/

    }


    .rotate_title {
      text-align: center;
    }
    .rotate_title span{
      white-space: nowrap;
      transform-origin: left bottom 0;
      display: none;
    }


    .report_signature_table {
      width: 80%;
      margin: 0 auto;
      margin-bottom: 0px;
      margin-top: 0px;
      text-align: center;
    }
    .report_signature_table hr {
      margin-top: 3px;
      margin-bottom: 1px;
    }
    .signature_name {
      font-size:12px;
    }

    .form_table_three.font_9 .rotate_title img {
      height:55px !important;
    }
    .form_table_three.font_10 .rotate_title img {
      height:65px !important;
    }
    .form_table_three.font_11 .rotate_title img {
      height:70px !important;
    }

    .paging {
      text-align: right !important;
      font-size: 10px;
      padding-top: 4px !important;
      font-style: italic;
      border-right: 1px solid transparent !important;
      border-bottom: 1px solid transparent !important;
      border-left: 1px solid transparent !important;
    }
    .first_column {
      white-space: nowrap;
    }
    .page-content .row_header {
      width: 1200px;
      margin: 0 auto !important;
    }
    .page-content .row {
      margin:20px;
      padding: 15px;
    }
    .report_header_table {
      width: 800px;
      margin: 0 auto;
      margin-bottom: 25px !important;
    }
    .report_header_table td {
      padding: 10px 15px;
    }


    /* Styles for sidebar menu */
    .sidebar {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1002;
      top: 0;
      left: 0;
      background-color: #111;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
      pointer-events: none;
    }

    .sidebar > * {
      pointer-events: auto;
    }

    .sidebar a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 14px;
      color: #818181;
      display: block;
      transition: 0.3s;
    }

    .sidebar a:hover {
      color: #f1f1f1;
    }

    .sidebar .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 24px;
      margin-left: 50px;
    }

    .openbtn {
      font-size: 16px;
      cursor: pointer;
      background-color: #111;
      color: white;
      padding: 8px 12px;
      border: none;
      position: fixed;
      top: 10px;
      left: 10px;
      z-index: 1001;
    }

    .openbtn:hover {
      background-color: #444;
    }

    #main {
      transition: margin-left .5s;
      padding: 16px;
    }

    .content-wrapper {
      position: relative;
      z-index: 1;
    }

    /* Tambahan style untuk collapsible menu */
    .sidebar .nav-link[data-toggle="collapse"] {
      position: relative;
    }

    .sidebar .nav-link[data-toggle="collapse"]::after {
      content: '\f107';
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      transition: transform 0.3s;
    }

    .sidebar .nav-link[data-toggle="collapse"][aria-expanded="true"]::after {
      transform: translateY(-50%) rotate(180deg);
    }

    .sidebar .collapse {
      transition: all 0.3s ease;
    }

    .sidebar .collapse:not(.show) {
      display: none;
    }

    .sidebar .collapse.show {
      display: block;
    }

    .sidebar .nav-item {
      width: 100%;
    }

    .sidebar ul.nav {
      width: 100%;
    }

    .sidebar .ml-3 {
      margin-left: 1rem !important;
    }
  </style>
</head>
<body>

  <div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <h5 style="color: white; padding-left: 32px;">Daftar Form</h5>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="#general-particulars">General Particulars</a>
      </li>
      <?php
      $counter = 1;
      foreach($categories as $category):
        $form_categories = isset($form_data_all[$category->id]) ? $form_data_all[$category->id] : [];
        $form_count = 0;
        foreach($form_categories as $form_category) {
          $form_count += count($form_category->form->where('report_id', $report_id));
        }
        if($form_count > 0):
      ?>
        <li class="nav-item">
          <a class="nav-link collapsed" data-toggle="collapse" href="#category-<?= $category->id ?>" role="button" aria-expanded="false" aria-controls="category-<?= $category->id ?>">
            <?= $category->name ?>
          </a>
          <div class="collapse" id="category-<?= $category->id ?>">
            <ul class="nav flex-column ml-3">
              <?php foreach($form_categories as $form_category): ?>
                <?php foreach($form_category->form->where('report_id', $report_id) as $form): ?>
                  <li class="nav-item">
                    <a class="nav-link" href="#form-<?= $form->id ?>"><?= $form->name ?></a>
                  </li>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        </li>
      <?php
        endif;
        $counter++;
      endforeach;
      ?>
    </ul>
  </div>

  <button class="openbtn" onclick="openNav()">&#9776; Menu</button>

  <!-- Main Content -->
  <div class="approve_div sticky-top" style="position: sticky; top: 0; z-index: 1000; background-color: #fff; padding: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div class="container">
      <div class="text-right">
        <div class="row">
          <div class="col-10">
            <div class="row mb-3"><div class="col-md-12"><div class="card"><div class="card-body"><h5 class="card-title">Status Review</h5><ul class="list-group list-group-horizontal"><li class="list-group-item d-flex justify-content-between align-items-center flex-fill">Direvisi<span id="revised-count" class="badge bg-primary rounded-pill">{{ $report->form->where('supervisor_verifikasi', 'revised')->count() }}</span></li><li class="list-group-item d-flex justify-content-between align-items-center flex-fill">Disetujui<span id="approved-count" class="badge bg-success rounded-pill">{{ $report->form->where('supervisor_verifikasi', 'approved')->count() }}</span></li><li class="list-group-item d-flex justify-content-between align-items-center flex-fill">Belum Direview<span id="pending-count" class="badge bg-warning rounded-pill">{{ $report->form->whereNotIn('supervisor_verifikasi', ['revised', 'approved'])->count() }}</span></li></ul></div></div></div></div>

          </div>
          <div class="col-2" id="send_to_supervisor_btn_container">
            @if(isset($report->submit_date) && $report->form->every(function($form) { return ($form->supervisor_verifikasi === 'approved' || $form->supervisor_verifikasi === 'revised'); }))
            <input type="button" data-id="{{ base64_encode($report->id) }}" class="send_to_supervisor_btn btn h-50 btn-primary" value="Submit Review">
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-content">

    <div class="row row_header">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="covers" style="text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
              <h2 style="font-size: 24px; margin: 0;"><u>LAPORAN PENGUKURAN KETEBALAN ULTRASONIK</u></h2>
              <p>No. Laporan : <?= $report->report_number ?? '-' ?></p>
              <table style="font-size: 18px; margin: 10px auto; line-height: 1; text-align: left;">
                <tr>
                    <td style="width: 150px;">Nama Kapal</td>
                    <td style="width: 20px;">:</td>
                    <td style="width: 300px;">{{ $ship->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 150px;">Pemilik</td>
                    <td style="width: 20px;">:</td>
                    <td style="width: 300px;">{{ $ship->owner ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 150px;">Lokasi Inspeksi</td>
                    <td style="width: 20px;">:</td>
                    <td style="width: 300px;">{{ $report->location ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 150px;">Tanggal Inspeksi</td>
                    <td style="width: 20px;">:</td>
                    <td style="width: 300px;">{{ $report->date_of_measurement ? date('d F Y', strtotime($report->date_of_measurement)) : '-' }}</td>
                </tr>
              </table>

              <img src="<?=Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized ?? $report->user->company->logo ?? 'uploads/company_logos/logobki_new.png', now()->addMinutes(5));?>" style="max-width: 200px; height: auto; margin-top: 180px; margin-bottom: 170px; display: block; margin-left: auto; margin-right: auto;">

              <h2 style="font-size: 24px; margin: 0;">{!! strtoupper($report->user->company->name ?? '-') !!}</h2>
              <h2 style="font-size: 16px; margin: 0;">{!! strtoupper($report->user->company->branch ?? '-') !!}</h2>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h3>Sertifikat Operator</h3>
            <div class="certificate-company">
              @if($report->user && $report->user->latestQualification && $report->user->latestQualification->certificate_file)
                <?php
                $competency_certificate = $report->user->latestQualification->certificate_file;
                $pdf_url = Storage::disk('digitalocean')->temporaryUrl($competency_certificate, now()->addMinutes(5));
                ?>
                <iframe src="{{ $pdf_url }}" width="100%" height="600px" style="border: none;"></iframe>
              @else
                <p>Sertifikat perusahaan tidak tersedia</p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h3>Sertifikat Perusahaan</h3>
            <div class="certificate-company">
              @if($report->user && $report->user->company->activeCertificate)
                <?php
                $certificate_file = $report->user->company->activeCertificate->certificate_file;
                $pdf_url = Storage::disk('digitalocean')->temporaryUrl($certificate_file, now()->addMinutes(5));
                ?>
                <iframe src="{{ $pdf_url }}" width="100%" height="600px" style="border: none;"></iframe>
              @else
                <p>Sertifikat perusahaan tidak tersedia</p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h3>Sertifikat Kalibrasi</h3>
            @if($report->equipment && $report->equipment->certifications)
              @foreach($report->equipment->certifications()->where('active', 1)->get() as $certificate)
                <div class="certificate_area">
                  <?php
                  try {
                    $pdf_url = Storage::disk('digitalocean')->temporaryUrl($certificate->url, now()->addMinutes(5));
                  } catch (\Exception $e) {
                    $pdf_url = null;
                  }
                  ?>
                  @if($pdf_url)
                    <iframe src="{{ $pdf_url }}" width="100%" height="600px" style="border: none;"></iframe>
                  @else
                    <p>Sertifikat kalibrasi tidak tersedia</p>
                  @endif
                </div>
              @endforeach
            @else
              <p>Tidak ada sertifikat kalibrasi yang aktif</p>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <!-- Table of Contents -->
            <div class="table-of-contents">
              <h2>Table of Contents</h2>
              <ul>
                <li>General Particulars</li>
                <?php
                $counter = 1;
                foreach($categories as $category):
                  $form_categories = isset($form_data_all[$category->id]) ? $form_data_all[$category->id] : [];
                  $form_count = 0;
                  foreach($form_categories as $form_category) {
                    $form_count += count($form_category->form->where('report_id', $report_id));
                  }
                  if($form_count > 0):
                ?>
                  <li><?= $category->title ?></li>
                <?php
                  endif;
                  $counter++;
                endforeach;
                ?>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <table style="width:80%; margin: 0 auto;">
              <tr>
                <td style="width:120px">
                  <img src="{{ $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) : asset('images/logobki_new.png') }}" style="width: auto; height: 60px; object-fit: contain;">
                </td>
                <td style="font-size: 16px;font-weight: bold;text-align: center;text-transform: uppercase;">
                  {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
                </td>
              </tr>
            </table>

            <hr>

            <table id="general-particulars" class="report_header_table" style="margin:0 auto;">

              <tr>
                <th colspan="7" class="text-center">
                  <u>THICKNESS MEASUREMENT REPORT</u>
                  <br>
                  Report No. : <?=$report->report_number;?>
                </th>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Nama Kapal
                  <div class="eng"><i>Ship's Name</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <?=$report->ship->name;?>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Pemilik Kapal
                  <div class="eng"><i>Ship Owner</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <?=$report->ship->owner ?? '-';?>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Alamat Pemilik
                  <div class="eng"><i>Owner Address</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <?=$report->ship->owner_city ?? '-';?>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Jenis Kapal
                  <div class="eng"><i>Type Of Ship</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <?=$report->ship->ship_type;?>
                </td>
              </tr>
              <tr>
                <td rowspan="3" style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Ukuran Kapal
                  <div class="eng"><i>Ship Dimension</i></div>
                </td>
                <td rowspan="3" style="vertical-align: top;">:</td>
                <td style="width:180px; vertical-align: top;">LOA</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;"><?=$report->ship->loa ?? '-';?></td>
                <td style="vertical-align: top;">Meter</td>
              </tr>
              <tr>
                <td style="vertical-align: top;">Breadth (B)</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;"><?=$report->ship->breadth ?? '-';?></td>
                <td style="vertical-align: top;">Meter</td>
              </tr>
              <tr>
                <td style="vertical-align: top;">Depth (H)</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;"><?=$report->ship->depth ?? '-';?></td>
                <td style="vertical-align: top;">Meter</td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Tonase Kotor/Daya
                  <div class="eng"><i>GT/HP</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <?=$report->ship->weight;?> GT <?=$report->ship->power ? '/'.$report->ship->power.'HP' : '';?>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Klasifikasi
                  <div class="eng"><i>Classification</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <?=$report->ship->classification ?? '-';?>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Jenis Laporan
                  <div class="eng"><i>Kind of Report</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  {{$report->kind_of_survey}}
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Perusahaan Pelaksana Pengukuran Ketebalan
                  <div class="eng"><i>Company Performing Thickness Measurement</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  {!!$report->company->name ?? '-'!!} {!!$report->company->branch ?? ''!!}
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Operator / Inspektor
                  <div class="eng"><i>Operator / Inspector</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <?php if($report->user->user_type == 'bki'){ ?>
                    <?=$report->user->name ?? '-';?><br>
                    {{-- <?=$report->user->cif ?? '-';?> --}}
                  <?php }else{ ?>
                    <?=$report->user->name ?? '-';?><br>
                    {{-- <?=$report->user->email ?? '-';?> --}}
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Kualifikasi Operator / Inspektor
                  <div class="eng"><i>Qualification of Operator / Inspector</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  {{$report->user->qualification ?? '-'}}
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Tempat Inspeksi
                  <div class="eng"><i>Place of Inspection</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  {{$report->location ?? '-'}}
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Tanggal Inspeksi
                  <div class="eng"><i>Date of Inspection</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  {{$report->date_of_measurement ? date('d-m-Y', strtotime($report->date_of_measurement)) : '-'}}
                </td>
              </tr>
              <tr>
                <td style="vertical-align: top; padding-top: 5px; padding-bottom: 5px;">
                  Detail Peralatan
                  <div class="eng"><i>Details of Equipment</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  <table class="table-borderless" style="border-collapse: collapse; width: 100%;">
                    <tr>
                      <td style="padding: 0; text-align: left;">Nama</td>
                      <td>:</td>
                      <td style="padding: 0;">{{ $report->equipment->name ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td style="padding: 0; text-align: left;">Manufaktur</td>
                      <td>:</td>
                      <td style="padding: 0;">{{ $report->equipment->manufactur ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td style="padding: 0; text-align: left;">Model</td>
                      <td>:</td>
                      <td style="padding: 0;">{{ $report->equipment->model ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td style="padding: 0; text-align: left;">Serial</td>
                      <td>:</td>
                      <td style="padding: 0;">{{ $report->equipment->serial ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td style="padding: 0; text-align: left;">Toleransi</td>
                      <td>:</td>
                      <td style="padding: 0;">{{ $report->equipment->tolerancy ?? '-' }}</td>
                    </tr>
                    <tr>
                      <td style="padding: 0; text-align: left;">Tipe Probe</td>
                      <td>:</td>
                      <td style="padding: 0;">{{ $report->equipment->probe_type ?? '-' }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Acceptance Criteria
                  <div class="eng"><i>Classification</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td colspan="5" style="vertical-align: top;">
                  BKI Rules
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Tanda Tangan Inspector/Operator
                  <div class="eng"><i>Signature Of Inspector/Operator</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td class="text-center" colspan="5" style="width:250px;vertical-align: bottom; line-height: 12px; font-size: 12px;">
                  <?php
                  if($report->form && $report->form->first()) {
                    echo signature($report->form->first()->id, 'operator', '');
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
                  Tanda Tangan Surveyor
                  <div class="eng"><i>Signature Of Surveyor</i></div>
                </td>
                <td style="vertical-align: top;">:</td>
                <td class="text-center" colspan="5" style="width:250px;vertical-align: bottom; line-height: 12px; font-size: 12px;">
                  <?php
                  if($report->form && $report->form->first()) {
                    echo signature($report->form->first()->id, 'surveyor', 'gp');
                  }
                  ?>
                </td>
              </tr>
            </table>


          </div>
        </div>
      </div>
    </div>





    <?php
    $x = 0;

    foreach ($categories as $category)
    {
      $report_image = $report_images[$category->id] ?? '';
      $image_url    = $report_image ? $report_image['url'] : '';

      ?>



      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">

              <div class="tab-pane">

                <div class="report_container">

                  <div id="sec-category-{{$category->id}}" class="category_title_area">
                    <h4 class="category_title" style="text-align:center;">
                      <?=$category->name;?>
                      <br>
                      <?=$category->title;?>
                    </h4>
                  </div>




                  <?php
                  $form_categories = $form_data_all[$category->id] ?? '';
                  if($form_categories)
                  {
                    foreach($form_categories as $form_category){?>

                      <?php foreach($category->images as $image){?>
                        <?php if($image->form_type_id == $form_category->id){ ?>
                          <div class="image_area">
                            <?php if($image->url_resized){ ?>
                              <img class="image_category_<?=$category->id;?>" src="<?=Storage::disk('digitalocean')->temporaryUrl($image->url_resized, now()->addMinutes(5));?>" />
                            <?php }else{ ?>
                              <img class="image_category_<?=$category->id;?>" src="<?=Storage::disk('digitalocean')->temporaryUrl($image->url, now()->addMinutes(5));?>" />
                            <?php } ?>
                          </div>
                        <?php } ?>
                      <?php } ?>

                      <?php
                      foreach($form_category->form as $form)
                      {
                        $status  = ($form->supervisor_verifikasi ?? 'waiting_for_approval');
                        ?>

                        <hr style="border-top: 1px dashed red;">

                        <?php
                        if($form_category->form_data_format == 'one')
                        {
                          $form_data = $form->form_data_one->keyBy('plate_position')->toArray();
                          $unit_type  = $form->form_type->unit_type;
                          $unit = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                          ?>

                          <div id="form-{{$form->id}}" class="landscape landscape_one">

                            <table class="header_table">
                              <tr>
                                <td class="logo">
                                  <img src="/images/logobki_new.png">
                                </td>
                                <td class="bki_title">
                                  PT. BIRO KLASIFIKASI INDONESIA
                                </td>
                                <td class="form_title align-top" style="text-align:right;">
                                  <?=$category->name;?>
                                </td>
                              </tr>

                            </table>

                            <div style="width:100%;padding:10px;font-weight:bold;text-align: center"><?=$category->description;?></div>

                            <table class="sub_header_table">
                              <tr>
                                <td>
                                  <b> Ship's Name : </b>
                                  <?=$ship->name;?>
                                </td>
                                <td>
                                  <b> IMO No. : </b>
                                  <?=$ship->no_imo;?>
                                </td>
                                <td>
                                  <b> Report No. : </b>
                                  <?=$report->report_number;?>
                                </td>
                              </tr>
                            </table>

                            <table class="form_table <?=get_font_size($form->total_line, 'one');?>" border="1">
                              <thead>
                                <tr>
                                  <th>STRAKE POSITION</th>
                                  <th colspan="19" class="form_title" style="border-right:none;">
                                    <?=$form->name;?>
                                  </th>
                                </tr>
                                <tr>
                                  <th rowspan="3">PLATE POSITION</th>
                                  <th rowspan="3">No<br>or<br>Letter</th>
                                  <th rowspan="2">ORG<br>THK</th>
                                  <th rowspan="2">MIN<br>THK</th>
                                  <th colspan="7">Aft Reading</th>
                                  <th colspan="7">Forward Reading</th>
                                  <th colspan="2">Mean Dimunition %</th>
                                </tr>
                                <tr>

                                  <th colspan="2">Gauged</th>
                                  <th rowspan="2">Dimunition<br>Level</th>
                                  <th colspan="2">Diminution P</th>
                                  <th colspan="2">Diminution S</th>

                                  <th colspan="2">Gauged</th>
                                  <th rowspan="2">Dimunition<br>Level</th>
                                  <th colspan="2">Diminution P</th>
                                  <th colspan="2">Diminution S</th>

                                  <th rowspan="2">P</th>
                                  <th rowspan="2">S</th>

                                </tr>
                                <tr>
                                  <th>mm</th>
                                  <th>mm</th>
                                  <th>P</th>
                                  <th>S</th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>mm</th>
                                  <th>%</th>

                                  <th>P</th>
                                  <th>S</th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>mm</th>
                                  <th>%</th>

                                </tr>

                              </thead>
                              <tbody>


                                <?php


                                $line=0;
                                $current_page=1;
                                foreach ($unit['unit_position'] as $position) {


                                  $line++;
                                  if(isset($form_data[$position]))
                                  {
                                    $data = $form_data[$position];


                                    $aft_dim_lvl = $data['aft_dim_lvl'] ?? '';
                                    $aft_dim_s_pct = $data['aft_dim_s_pct'] ?? '';
                                    $aft_dim_p_pct = $data['aft_dim_p_pct'] ?? '';


                                    $forward_dim_lvl = $data['forward_dim_lvl'] ?? '';
                                    $forward_dim_s_pct = $data['forward_dim_s_pct'] ?? '';
                                    $forward_dim_p_pct = $data['forward_dim_p_pct'] ?? '';

                                    $mean_dim_p = $data['mean_dim_p'] ?? '';
                                    $mean_dim_s = $data['mean_dim_s'] ?? '';


                                    $color_1 = get_color($aft_dim_s_pct, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                    $color_2 = get_color($aft_dim_p_pct, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                    $color_3 = get_color($forward_dim_s_pct, $data['new_plate'], $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                    $color_4 = get_color($forward_dim_p_pct, $data['new_plate'], $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                    $color_5 = get_color($mean_dim_p, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                    $color_6 = get_color($mean_dim_s, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);

                                    ?>

                                    <tr>

                                      <?php if($unit_type == 'free_text') {?>
                                        <td class="text-left first_column"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                      <?php } else { ?>
                                        <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                      <?php } ?>

                                      <td><?=$data['no_letter'];?></td>
                                      <td><?=$data['org_thickness'];?></td>
                                      <td><?=$data['min_thickness'];?></td>

                                      <td><?=$data['aft_gauged_p'];?></td>
                                      <td><?=$data['aft_gauged_s'];?></td>
                                      <td><?=$data['aft_dim_lvl'] ? $data['aft_dim_lvl'].'%':'';?></td>

                                      <td><?=$data['aft_dim_p_mm'];?></td>
                                      <td class="<?=$color_2;?>"><?=$data['aft_dim_p_pct'] ? $data['aft_dim_p_pct'].'%':'';?></td>

                                      <td><?=$data['aft_dim_s_mm'];?></td>
                                      <td class="<?=$color_1;?>"><?=$data['aft_dim_s_pct'] ? $data['aft_dim_s_pct'].'%':'';?></td>


                                      <td><?=$data['forward_gauged_p'];?></td>
                                      <td><?=$data['forward_gauged_s'];?></td>

                                      <td><?=$data['forward_dim_lvl'] ? $data['forward_dim_lvl'].'%':'';?></td>

                                      <td><?=$data['forward_dim_p_mm'];?></td>
                                      <td class="<?=$color_4;?>"><?=$data['forward_dim_p_pct'] ? $data['forward_dim_p_pct'].'%':'';?></td>

                                      <td><?=$data['forward_dim_s_mm'];?></td>
                                      <td class="<?=$color_3;?>"><?=$data['forward_dim_s_pct'] ? $data['forward_dim_s_pct'].'%':'';?></td>


                                      <td class="<?=$color_5;?>"><?=$data['mean_dim_p'] ? $data['mean_dim_p'].'%':'';?></td>
                                      <td class="<?=$color_6;?>"><?=$data['mean_dim_s'] ? $data['mean_dim_s'].'%':'';?></td>


                                    </tr>
                                  <?php }else{ ?>
                                    <tr>
                                      <td class="first_column"><?=$unit['unit_position_text'][$position];?></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                    </tr>

                                  <?php }?>





                                <?php }?>

                                <tr class="total_tr">
                                  <td class="text-center" style="border-right-color:transparent;">
                                    <b>Total</b>
                                  </td>
                                  <td colspan="16" class="text-right" style="border-right-color:transparent;">
                                  </td>
                                  <td colspan="3">

                                    <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                                  </td>
                                </tr>

                              </tbody>
                            </table>


                            <div id="signature_review-{{$form->id}}" style="display: flex; justify-content: space-between; margin-top: 20px; {{$status == 'revised' ? 'background-color: rgba(255, 255, 0, 0.161)' : ''}}">
                              <table class="report_signature_table" style="width: 60%;">
                                <tr>
                                  <td class="text-center" style="width:250px;vertical-align: bottom;">
                                    <?=signature($form->id, 'operator', '');?>
                                    <hr>
                                    Inspektor / Operator
                                  </td>
                                  <td class="text-center" style="vertical-align: middle;">
                                    <?php if($status == 'approved'){?><?=qr_code($form->id, '');?><?php }?>
                                  </td>
                                  <td class="text-center" style="width:250px;vertical-align: bottom;">
                                    <?=signature($form->id, 'surveyor', $status);?>
                                    <hr>
                                    Surveyor
                                  </td>
                                </tr>
                              </table>
                              <div class="review-section" style="width: 35%; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                                <?php if($form->supervisor_verifikasi == 'approved') { ?>
                                  <button class="btn btn-success btn-block" disabled style="margin-bottom: 10px; width: 100%;">
                                    <i class="fa fa-check"></i> Disetujui ✔
                                  </button>
                                <?php } else { ?>
                                  <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="comment_{{ $form->id }}" style="display: block; margin-bottom: 5px; font-weight: bold;">Komentar:</label>
                                    <textarea id="comment_{{ $form->id }}" class="summernote" style="width: 100%;">
                                      {!! $form->supervisor_notes ?? ''!!}
                                    </textarea>
                                  </div>
                                  <div class="button-group" style="display: flex; justify-content: space-between;">
                                    <button class="btn btn-warning revise-btn" data-form-id="{{ $form->id }}" style="width: 49%;">
                                      Revisi
                                    </button>
                                    <button class="btn btn-primary review-btn" data-form-id="{{ $form->id }}" style="width: 49%;">
                                      Reviewed
                                    </button>
                                  </div>
                                <?php } ?>
                              </div>
                            </div>
                          </div>

                          <?php


                        }
                        if($form_category->form_data_format == 'two')
                        {

                          $form_data  = $form ? $form->form_data_two->keyBy('plate_position')->toArray() : [];
                          $unit_type  = $form->form_type->unit_type;
                          $unit = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                          ?>

                          <div id="form-{{$form->id}}" class="landscape_two">




                            <table class="header_table">
                              <tr>
                                <td class="logo">
                                  <img src="/images/logobki_new.png">
                                </td>
                                <td class="bki_title">
                                  PT. BIRO KLASIFIKASI INDONESIA
                                </td>
                                <td class="form_title align-top" style="text-align:right;">
                                  <?=$category->name;?>
                                </td>
                              </tr>

                            </table>

                            <div style="width:100%;padding:10px;font-weight:bold;text-align: center"><?=$category->description;?></div>

                            <table class="sub_header_table">
                              <tr>
                                <td>
                                  <b> Ship's Name : </b>
                                  <?=$ship->name;?>
                                </td>
                                <td>
                                  <b> IMO No. : </b>
                                  <?=$ship->no_imo;?>
                                </td>
                                <td>
                                  <b> Report No. : </b>
                                  <?=$report->report_number;?>
                                </td>
                              </tr>
                            </table>


                            <table class="form_table <?=get_font_size($form->total_line, 'two');?>" border="1">
                              <thead>
                                <tr>
                                  <th colspan="11" class="text-left">
                                    <?=$form->name;?>
                                  </th>
                                </tr>
                                <?php if($form->title_1){?>
                                  <tr>
                                    <th colspan="11" class="text-left">
                                      <?=$form->title_1;?>
                                    </th>
                                  </tr>
                                <?php } ?>
                                <tr>
                                  <th rowspan="2"><?=$form->form_type->unit_title;?></th>
                                  <th rowspan="2"><?=$form->form_type->number_wording;?></th>
                                  <th rowspan="2">ORG THK (mm)</th>
                                  <th rowspan="2">MIN. THK (mm)</th>
                                  <th colspan="2">Gauged</th>
                                  <th rowspan="2">Diminution Level</th>
                                  <th colspan="2"><?=$form->form_type->dim_p_title;?></th>
                                  <th colspan="2"><?=$form->form_type->dim_s_title;?></th>

                                </tr>
                                <tr>

                                  <th><?=$form->form_type->gauged_p_title;?></th>
                                  <th><?=$form->form_type->gauged_s_title;?></th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>mm</th>
                                  <th>%</th>

                                </tr>


                              </thead>


                              <tbody>

                                <?php


                                $line = 0;
                                $current_page=1;
                                foreach ($unit['unit_position'] as $position)
                                {
                                  $line++;

                                  if(isset($form_data[$position]))
                                  {
                                    $data = $form_data[$position];

                                    $dim_lvl = $data['dim_lvl'] ?? '';
                                    $dim_p_pct = $data['dim_p_pct'] ?? '';
                                    $dim_s_pct = $data['dim_s_pct'] ?? '';

                                    $color_1 = get_color($dim_p_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                    $color_2 = get_color($dim_s_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit'] ?? '%', $data['org_thickness']);

                                    ?>
                                    <tr>
                                      <?php if($unit_type == 'free_text') {?>
                                        <td class="text-left first_column"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                      <?php } else { ?>
                                        <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                      <?php } ?>

                                      <td><?=$data['item_no'] ?? '';?></td>
                                      <td><?=$data['org_thickness'] ?? '';?></td>
                                      <td><?=$data['min_thickness'] ?? '';?></td>
                                      <td><?=$data['gauged_p'] ?? '';?></td>
                                      <td><?=$data['gauged_s'] ?? '';?></td>
                                      <td><?=$data['dim_lvl'] ? $data['dim_lvl'].'%' : '';?></td>
                                      <td><?=$data['dim_p_mm'] ?? '';?></td>
                                      <td class="<?=$color_1;?>"><?=$data['dim_p_pct'] ? $data['dim_p_pct'].'%' : '';?></td>
                                      <td><?=$data['dim_s_mm'] ?? '';?></td>
                                      <td class="<?=$color_2;?>"><?=$data['dim_s_pct'] ? $data['dim_s_pct'].'%' : '';?></td>


                                    </tr>
                                  <?php }else{ ?>
                                    <tr>
                                      <?php if($unit_type == 'free_text') {?>

                                        <td class="first_column"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                      <?php } else { ?>
                                        <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>

                                      <?php } ?>
                                      <?php for($z=1;$z<=10;$z++)
                                      { ?>
                                        <td>&nbsp;</td>
                                      <?php } ?>

                                    </tr>

                                  <?php }?>



                                <?php }?>


                                <tr class="total_tr">
                                  <td class="text-center" style="border-right-color:transparent;">
                                    <b>Total</b>
                                  </td>
                                  <td colspan="8" class="text-right" style="border-right-color:transparent;">
                                  </td>
                                  <td colspan="2">

                                    <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                                  </td>
                                </tr>

                              </tbody>
                            </table>


                            <div id="signature_review-{{$form->id}}" style="display: flex; justify-content: space-between; margin-top: 20px; {{$status == 'revised' ? 'background-color: rgba(255, 255, 0, 0.161)' : ''}}">
                              <table class="report_signature_table" style="width: 60%;">
                                <tr>
                                  <td class="text-center" style="width:250px;vertical-align: bottom;">
                                    <?=signature($form->id, 'operator', '');?>
                                    <hr>
                                    Inspektor / Operator
                                  </td>
                                  <td class="text-center" style="vertical-align: middle;">
                                    <?php if($status == 'approved'){?><?=qr_code($form->id, '');?><?php }?>
                                  </td>
                                  <td class="text-center" style="width:250px;vertical-align: bottom;">
                                    <?=signature($form->id, 'surveyor', $status);?>
                                    <hr>
                                    Surveyor
                                  </td>
                                </tr>
                              </table>
                              <div class="review-section" style="width: 35%; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                                <?php if($form->supervisor_verifikasi == 'approved') { ?>
                                  <button class="btn btn-success btn-block" disabled style="margin-bottom: 10px; width: 100%;">
                                    <i class="fa fa-check"></i> Disetujui ✔
                                  </button>
                                <?php } else { ?>
                                  <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="comment_{{ $form->id }}" style="display: block; margin-bottom: 5px; font-weight: bold;">Komentar:</label>
                                    <textarea id="comment_{{ $form->id }}" class="summernote" style="width: 100%;">
                                      {!! $form->supervisor_notes ?? ''!!}
                                    </textarea>
                                  </div>
                                  <div class="button-group" style="display: flex; justify-content: space-between;">
                                    <button class="btn btn-warning revise-btn" data-form-id="{{ $form->id }}" style="width: 49%;">
                                      Revisi
                                    </button>
                                    <button class="btn btn-primary review-btn" data-form-id="{{ $form->id }}" style="width: 49%;">
                                      Reviewed
                                    </button>
                                  </div>
                                <?php } ?>
                              </div>
                            </div>

                          </div>



                          <?php
                        }


                        if($form_category->form_data_format == 'three')
                        {
                          $form_data = $form->form_data_three->keyBy('plate_position')->toArray();

                          $unit_type  = $form->form_type->unit_type;
                          $unit = get_unit_data($form->total_line, $unit_type);
                          ?>



                          <div id="form-{{$form->id}}" class="landscape_three">



                            <table class="header_table">
                              <tr>
                                <td class="logo">
                                  <img src="/images/logobki_new.png">
                                </td>
                                <td class="bki_title">
                                  PT. BIRO KLASIFIKASI INDONESIA
                                </td>
                                <td class="form_title align-top" style="text-align:right;">
                                  <?=$category->name;?>
                                </td>
                              </tr>

                            </table>

                            <div style="width:100%;padding:5px;font-weight:bold;text-align: center"><?=$category->description;?></div>

                            <table class="sub_header_table">
                              <tr>
                                <td>
                                  <b> Ship's Name : </b>
                                  <?=$ship->name;?>
                                </td>
                                <td>
                                  <b> IMO No. : </b>
                                  <?=$ship->no_imo;?>
                                </td>
                                <td>
                                  <b> Report No. : </b>
                                  <?=$report->report_number;?>
                                </td>
                              </tr>
                            </table>

                            <table class="form_table form_table_three <?=get_font_size($form->total_line, 'three');?>" border="1">
                              <thead>
                                <tr>
                                  <th colspan="31" class="form_title" style="border-right:none;">
                                    <?=$form->name;?>
                                  </th>
                                </tr>
                                <tr>
                                  <th rowspan="3">Strake<br>Position</th>
                                  <th colspan="10">
                                    <?=$form->title_1 ??'-';?>
                                  </th>
                                  <th colspan="10">
                                    <?=$form->title_2 ??'-';?>
                                  </th>
                                  <th colspan="10">
                                    <?=$form->title_3 ??'-';?>
                                  </th>
                                </tr>
                                <tr>

                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/no_or_letter.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/org_thk.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/min_thk.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th colspan="2"><span>Gauged</span></th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/dim_lvl.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th colspan="2"><span>Diminution P</span></th>
                                  <th colspan="2"><span>Diminution S</span></th>

                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/no_or_letter.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/org_thk.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/min_thk.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th colspan="2"><span>Gauged</span></th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/dim_lvl.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th colspan="2"><span>Diminution P</span></th>
                                  <th colspan="2"><span>Diminution S</span></th>

                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/no_or_letter.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/org_thk.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/min_thk.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th colspan="2"><span>Gauged</span></th>
                                  <th rowspan="2" class="rotate_title">
                                    <img src="/images/dim_lvl.jpg" style="width: auto;height: 80px;">
                                  </th>
                                  <th colspan="2"><span>Diminution P</span></th>
                                  <th colspan="2"><span>Diminution S</span></th>

                                </tr>

                                <tr>
                                  <th>P</th>
                                  <th>S</th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>P</th>
                                  <th>S</th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>P</th>
                                  <th>S</th>
                                  <th>mm</th>
                                  <th>%</th>
                                  <th>mm</th>
                                  <th>%</th>

                                </tr>


                              </thead>
                              <tbody>



                                <?php
                                $line = 0;
                                $current_page = 1;
                                foreach ($unit['unit_position'] as $position) {
                                  $line++;


                                  if(isset($form_data[$position]))
                                  {
                                    $data = $form_data[$position];
                                    ?>
                                    <tr>
                                      <?php if($unit_type == 'free_text') {?>
                                        <td class="first_column"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                      <?php } else if($unit_type == 'strake') { ?>
                                        <td class="text-left first_column"><?=ucwords(str_replace('_', ' ', $unit['unit_position_text'][$position]));?></td>
                                      <?php } else { ?>
                                        <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                      <?php } ?>

                                      <?php for($zz=1;$zz<=3;$zz++){?>


                                        <?php

                                        $dim_lvl = $data['dim_lvl_'.$zz] ?? '';
                                        $dim_p_pct = $data['dim_p_pct_'.$zz] ?? '';
                                        $dim_s_pct = $data['dim_s_pct_'.$zz] ?? '';

                                        $color_1 = get_color($dim_p_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit_'.$zz] ?? '%', $data['org_thickness_'.$zz]);
                                        $color_2 = get_color($dim_s_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit_'.$zz] ?? '%', $data['org_thickness_'.$zz]);

                                        ?>
                                        <td><?=$data['item_no_'.$zz] ?? '';?></td>
                                        <td><?=$data['org_thickness_'.$zz] ?? '';?></td>
                                        <td><?=$data['min_thickness_'.$zz] ?? '';?></td>
                                        <td><?=$data['gauged_p_'.$zz] ?? '';?></td>
                                        <td><?=$data['gauged_s_'.$zz] ?? '';?></td>
                                        <td><?=$data['dim_lvl_'.$zz] ? $data['dim_lvl_'.$zz].'%' : '';?></td>
                                        <td><?=$data['dim_p_mm_'.$zz] ?? '';?></td>
                                        <td class="<?=$color_1;?>"><?=$data['dim_p_pct_'.$zz] ? $data['dim_p_pct_'.$zz].'%' : '';?></td>
                                        <td><?=$data['dim_s_mm_'.$zz] ?? '';?></td>
                                        <td class="<?=$color_2;?>"><?=$data['dim_s_pct_'.$zz] ? $data['dim_s_pct_'.$zz].'%' : '';?></td>


                                      <?php }?>




                                    </tr>
                                  <?php }else{ ?>
                                    <tr>
                                      <td>&nbsp;</td>
                                      <?php for($z=1;$z<=30;$z++)
                                      { ?>
                                        <td>&nbsp;</td>
                                      <?php } ?>
                                    </tr>
                                  <?php }?>

                                <?php }?>


                                <tr class="total_tr">
                                  <td class="text-center" style="border-right-color:transparent;">
                                    <b>Total</b>
                                  </td>
                                  <td colspan="28" class="text-right" style="border-right-color:transparent;">
                                  </td>
                                  <td colspan="2">

                                    <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                                  </td>
                                </tr>


                              </tbody>
                            </table>

                            <div id="signature_review-{{$form->id}}" style="display: flex; justify-content: space-between; margin-top: 20px; {{$status == 'revised' ? 'background-color: rgba(255, 255, 0, 0.161)' : ''}}">
                              <table class="report_signature_table" style="width: 60%;">
                                <tr>
                                  <td class="text-center" style="width:250px;vertical-align: bottom;">
                                    <?=signature($form->id, 'operator', '');?>
                                    <hr>
                                    Inspektor / Operator
                                  </td>
                                  <td class="text-center" style="vertical-align: middle;">
                                    <?php if($status == 'approved'){?><?=qr_code($form->id, '');?><?php }?>
                                  </td>
                                  <td class="text-center" style="width:250px;vertical-align: bottom;">
                                    <?=signature($form->id, 'surveyor', $status);?>
                                    <hr>
                                    Surveyor
                                  </td>
                                </tr>
                              </table>
                              <div class="review-section" style="width: 35%; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                                <?php if($form->supervisor_verifikasi == 'approved') { ?>
                                  <button class="btn btn-success btn-block" disabled style="margin-bottom: 10px; width: 100%;">
                                    <i class="fa fa-check"></i> Disetujui ✔
                                  </button>
                                <?php } else { ?>
                                  <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="comment_{{ $form->id }}" style="display: block; margin-bottom: 5px; font-weight: bold;">Komentar:</label>
                                    <textarea id="comment_{{ $form->id }}" class="summernote" style="width: 100%;">
                                      {!! $form->supervisor_notes ?? ''!!}
                                    </textarea>
                                  </div>
                                  <div class="button-group" style="display: flex; justify-content: space-between;">
                                    <button class="btn btn-warning revise-btn" data-form-id="{{ $form->id }}" style="width: 49%;">
                                      Revisi
                                    </button>
                                    <button class="btn btn-primary review-btn" data-form-id="{{ $form->id }}" style="width: 49%;">
                                      Reviewed
                                    </button>
                                  </div>
                                <?php } ?>
                              </div>
                            </div>
                          </div>

                          <?php


                        }

                      }

                    }

                  }?>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>

      <?php $x++;
    }?>



  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Summernote JS -->
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

  {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet"> --}}

  <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>



  <!-- iziToast JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

  <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255,255,255,0.8); display: flex; justify-content: center; align-items: center; z-index: 9999;">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <script type="text/javascript">
  function openNav() {
      document.getElementById("mySidebar").style.width = "250px";
      document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
      document.getElementById("mySidebar").style.width = "0";
      document.getElementById("main").style.marginLeft = "0";
    }

    $(document).ready(function(){
      $('.sidebar a').on('click', function(event) {
        if (this.hash !== "") {
          event.preventDefault();
          var hash = this.hash;

          // Handle collapsible menu items
          if ($(this).attr('data-toggle') === 'collapse') {
            // Toggle the collapse state
            var $target = $($(this).attr('href'));
            $('.collapse').not($target).collapse('hide');
            $target.collapse('toggle');

            // Update aria-expanded
            var isExpanded = $(this).attr('aria-expanded') === 'true';
            $(this).attr('aria-expanded', !isExpanded);

            // Only scroll if expanding
            if (!isExpanded) {
              // Modify hash to point to sec-category if it's a category
              var categoryId = hash.replace('#category-', '');
              var scrollTarget = '#sec-category-' + categoryId;

              setTimeout(function() {
                $('html, body').animate({
                  scrollTop: $(scrollTarget).offset().top - 100
                }, 300);
              }, 200);
            }
          } else {
            // For regular links, close any open menus and scroll
            $('.collapse').collapse('hide');
            $('.nav-link[data-toggle="collapse"]').attr('aria-expanded', 'false');

            $('html, body').animate({
              scrollTop: $(hash).offset().top - 100
            }, 300);
          }
        }
      });
      $('.summernote').summernote({
        height: 80,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough', 'superscript', 'subscript']],
          ['para', ['ul', 'ol', 'paragraph']],
        ]
      });

      $('.revise-btn').click(function() {
        var formId = $(this).data('form-id');
        var comment = $('#comment_' + formId).summernote('code');

        if (comment.trim() === '<p><br></p>' || comment.trim() === '') {
          iziToast.warning({
            title: 'Peringatan',
            message: 'Komentar wajib diisi untuk revisi.',
            position: 'topRight'
          });
          return;
        }

        $('#loading-overlay').show();

        $.ajax({
          url: '/supervisor_revise/' + formId,
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            comment: comment
          },
          success: function(response) {
            $('#loading-overlay').hide();
            iziToast.success({
              title: 'Revisi',
              message: 'Form telah berhasil direvisi',
              position: 'topRight'
            });
            // Tambahkan background color ke elemen signature_review
            $('#signature_review-' + formId).css('background-color', 'rgba(255, 255, 0, 0.161)');

            // Update count
            updateCounts(response.revisedCount, response.approvedCount, response.pendingCount);

            // Perbarui tampilan halaman
            location.reload();
          },
          error: function(response) {
            $('#loading-overlay').hide();
            iziToast.error({
              title: 'Error',
              message: 'Gagal merevisi form: ' + response.responseJSON.message,
              position: 'topRight'
            });
          }
        });
      });

      $('.review-btn').click(function() {
        var formId = $(this).data('form-id');
        var comment = $('#comment_' + formId).summernote('code');

        $('#loading-overlay').show();

        $.ajax({
          url: '/supervisor_approve/' + formId,
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            comment: comment
          },
          success: function(response) {
            $('#loading-overlay').hide();
            iziToast.success({
              title: 'Berhasil',
              message: 'Form telah berhasil disetujui.',
              position: 'topRight'
            });

            $('#signature_review-' + formId).css('background-color', 'white');
            var reviewSection = $('[data-form-id="' + formId + '"]').closest('.review-section');
            reviewSection.html('<button class="btn btn-success btn-block" disabled style="margin-bottom: 10px; width: 100%;"><i class="fa fa-check"></i> Disetujui ✔</button>');

            // Update count
            updateCounts(response.revisedCount, response.approvedCount, response.pendingCount);
          },
          error: function(response) {
            $('#loading-overlay').hide();
            iziToast.error({
              title: 'Error',
              message: 'Gagal menyetujui form: ' + response.responseJSON.message,
              position: 'topRight'
            });
          }
        });
      });

      function updateCounts(revisedCount, approvedCount, pendingCount) {
        $('#revised-count').text(revisedCount);
        $('#approved-count').text(approvedCount);
        $('#pending-count').text(pendingCount);

        if (pendingCount === 0) {
          $('#send_to_supervisor_btn_container').html('<input type="button" data-id="{{ base64_encode($report->id) }}" class="send_to_supervisor_btn btn h-50 btn-primary" value="Submit Review">');
        } else {
          $('#send_to_supervisor_btn_container').html('');
        }

        $('.send_to_supervisor_btn').off('click').on('click', function() {
          var reportId = $(this).data('id');
          var revisedCount = parseInt($('#revised-count').text());
          var pendingCount = parseInt($('#pending-count').text());
          var confirmationText = "Apakah Anda yakin ingin mengirim laporan ini ke Surveyor?";

          if (revisedCount > 0 && pendingCount === 0) {
            confirmationText = "Apakah Anda yakin? Report akan dikembalikan ke Operator untuk dilakukan perbaikan.";
          } else if (revisedCount === 0 && pendingCount === 0) {
            confirmationText = "Apakah Anda yakin ingin mengirim laporan ini ke Surveyor?";
          } else {
            return;
          }

          Swal.fire({
            title: 'Konfirmasi',
            text: confirmationText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, submit!',
            cancelButtonText: 'Batal',
            background: '#fff'
          }).then((result) => {
            if (result.isConfirmed) {
              $('#loading-overlay').show();

              // Create form element
              var form = document.createElement('form');
              form.method = 'POST';
              form.action = '{{ route("supervisor.submit", ["id" => ":id"]) }}'.replace(':id', reportId);

              // Add CSRF token
              var csrf = document.createElement('input');
              csrf.type = 'hidden';
              csrf.name = '_token';
              csrf.value = '{{ csrf_token() }}';
              form.appendChild(csrf);

              // Add method field
              var method = document.createElement('input');
              method.type = 'hidden';
              method.name = '_method';
              method.value = 'POST';
              form.appendChild(method);

              // Submit form
              document.body.appendChild(form);
              form.submit();
            }
          });
        });
      }
    });

    $('.send_to_supervisor_btn').click(function() {
      var reportId = $(this).data('id');
      var revisedCount = parseInt($('#revised-count').text());
      var pendingCount = parseInt($('#pending-count').text());
      var confirmationText = "Apakah Anda yakin ingin mengirim laporan ini ke Surveyor?";

      if (revisedCount > 0 && pendingCount === 0) {
        confirmationText = "Apakah Anda yakin? Report akan dikembalikan ke Operator untuk dilakukan perbaikan.";
      } else if (revisedCount === 0 && pendingCount === 0) {
        confirmationText = "Apakah Anda yakin ingin mengirim laporan ini ke Surveyor?";
      } else {
        return;
      }

      Swal.fire({
        title: 'Konfirmasi',
        text: confirmationText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, submit!',
        cancelButtonText: 'Batal',
        background: '#fff' // Menambahkan background putih
      }).then((result) => {
        if (result.isConfirmed) {
              $('#loading-overlay').show();

              // Create form element
              var form = document.createElement('form');
              form.method = 'POST';
              form.action = '{{ route("supervisor.submit", ["id" => ":id"]) }}'.replace(':id', reportId);
              console.log(form.action);
              // Add CSRF token
              var csrf = document.createElement('input');
              csrf.type = 'hidden';
              csrf.name = '_token';
              csrf.value = '{{ csrf_token() }}';
              form.appendChild(csrf);

              // Add method field
              var method = document.createElement('input');
              method.type = 'hidden';
              method.name = '_method';
              method.value = 'POST';
              form.appendChild(method);

              // Submit form
              document.body.appendChild(form);
          form.submit();
        }
      });
    });


    $(window).on('load', function() {
      $('#loading-overlay').hide();
    });
  </script>

</body>
</html>
