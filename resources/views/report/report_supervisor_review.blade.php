<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>{{ $report->report_number ?? '-' }} - {{ $report->user->company->name ?? '-' }}{{ $report->user->company->branch ? ' - ' + $report->user->company->branch : '' }}</title>
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
                  <?=$report->ship->name;?
                </td>
              </tr>
... (file continues unchanged) ...

  <!-- iziToast JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

  <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255,255,255,0.8); display: none; justify-content: center; align-items: center; z-index: 9999;">
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

      function setButtonLoading($btn, loading) {
        if (loading) {
          $btn.prop('disabled', true);
          $btn.find('.spinner-border').removeClass('d-none');
          $btn.find('.btn-text').text('Mengirim...');
        } else {
          $btn.prop('disabled', false);
          $btn.find('.spinner-border').addClass('d-none');
          if ($btn.hasClass('revise-btn')) $btn.find('.btn-text').text('Revisi');
          else if ($btn.hasClass('review-btn')) $btn.find('.btn-text').text('Reviewed');
        }
      }

      $('.revise-btn').click(function(e) {
        e.preventDefault();
        var $btn = $(this);
        var formId = $btn.data('form-id');
        var comment = $('#comment_' + formId).summernote('code');

        if (comment.trim() === '<p><br></p>' || comment.trim() === '') {
          iziToast.warning({
            title: 'Peringatan',
            message: 'Komentar wajib diisi untuk revisi.',
            position: 'topRight'
          });
          return;
        }

        setButtonLoading($btn, true);

        $.ajax({
          url: '/supervisor_revise/' + formId,
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            comment: comment
          },
          success: function(response) {
            iziToast.success({
              title: 'Revisi',
              message: 'Form telah berhasil direvisi',
              position: 'topRight'
            });
            $('#signature_review-' + formId).css('background-color', 'rgba(255, 255, 0, 0.161);');
            updateCounts(response.revisedCount, response.approvedCount, response.pendingCount);

            // Jika Anda ingin langsung reload, biarkan baris ini; atau hapus agar halaman tidak reload
            location.reload();
          },
          error: function(response) {
            iziToast.error({
              title: 'Error',
              message: 'Gagal merevisi form: ' + (response.responseJSON && response.responseJSON.message ? response.responseJSON.message : response.statusText),
              position: 'topRight'
            });
          },
          complete: function() {
            setButtonLoading($btn, false);
          }
        });
      });

      $('.review-btn').click(function(e) {
        e.preventDefault();
        var $btn = $(this);
        var formId = $btn.data('form-id');
        var comment = $('#comment_' + formId).summernote('code');

        setButtonLoading($btn, true);

        $.ajax({
          url: '/supervisor_approve/' + formId,
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            comment: comment
          },
          success: function(response) {
            iziToast.success({
              title: 'Berhasil',
              message: 'Form telah berhasil disetujui.',
              position: 'topRight'
            });

            $('#signature_review-' + formId).css('background-color', 'white');
            var reviewSection = $('[data-form-id="' + formId + '"]').closest('.review-section');
            reviewSection.html('<button class="btn btn-success btn-block" disabled style="margin-bottom: 10px; width: 100%;"><i class="fa fa-check"></i> Disetujui ✔</button>');

            updateCounts(response.revisedCount, response.approvedCount, response.pendingCount);
          },
          error: function(response) {
            iziToast.error({
              title: 'Error',
              message: 'Gagal menyetujui form: ' + (response.responseJSON && response.responseJSON.message ? response.responseJSON.message : response.statusText),
              position: 'topRight'
            });
          },
          complete: function() {
            setButtonLoading($btn, false);
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

              var form = document.createElement('form');
              form.method = 'POST';
              form.action = '{{ route("supervisor.submit", ["id" => ":id"]) }}'.replace(':id', reportId);

              var csrf = document.createElement('input');
              csrf.type = 'hidden';
              csrf.name = '_token';
              csrf.value = '{{ csrf_token() }}';
              form.appendChild(csrf);

              var method = document.createElement('input');
              method.type = 'hidden';
              method.name = '_method';
              method.value = 'POST';
              form.appendChild(method);

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

              var form = document.createElement('form');
              form.method = 'POST';
              form.action = '{{ route("supervisor.submit", ["id" => ":id"]) }}'.replace(':id', reportId);
              console.log(form.action);

              var csrf = document.createElement('input');
              csrf.type = 'hidden';
              csrf.name = '_token';
              csrf.value = '{{ csrf_token() }}';
              form.appendChild(csrf);

              var method = document.createElement('input');
              method.type = 'hidden';
              method.name = '_method';
              method.value = 'POST';
              form.appendChild(method);

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