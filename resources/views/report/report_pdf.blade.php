<?php

$show_total_page  = false;
$limit_page_break = 20;

?><!DOCTYPE html>
<html lang="en">
<head>

  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

  <style type="text/css">
  body {
    font-family: 'Times New Roman', Times, serif;
  }
    table {
      border-collapse: collapse;
    }
    .form_table {
      margin:10px 0 30px 0;
      width: 100%;
      border-spacing: initial;
    }
    .form_table_three {
      margin:10px 0 30px -10px;
    }
    .form_table td , .form_table th {
      text-align: center;
      padding: 1px 4px;
      border: 1px solid #555;
    }
    .font_16 td , .font_16 th {
      font-size: 15px;
    }
    .font_15 td , .font_15 th {
      font-size: 14px;
    }
    .font_14 td , .font_14 th {
      font-size: 13px;
    }
    .font_13 td , .font_13 th {
      font-size: 12px;
    }
    .font_12 td , .font_12 th {
      font-size: 11px;
    }
    .font_11 td , .font_11 th {
      font-size: 10px;
    }
    .font_10 td , .font_10 th {
      font-size: 9px;
    }
    .font_9 td , .font_9 th {
      font-size: 8px;
    }
    .font_8 td , .font_8 th {
      font-size: 7px;
    }

    .trasparent {
      background-color: transparent;
    }

    .red {
      background-color: red;
    }

    .green {
      background-color: green;
    }

    .yellow {
      background-color: yellow;
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
      margin-top: 90px;
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
    .certificate_area {
      margin-top: 10px;
      text-align: center;
    }
    .certificate_area img {
      margin-bottom: 2px;
      max-width: 1020px;
      max-height: 720px;
    }
    .certificate_title {
      text-align: center;
      font-size: 12px;
    }
    .print_btn {
      position: fixed;
      right: 20px;
      top: 20px;
    }

    .rotate_header
    {
      /* margin-top: 30px; */
      margin-left: -80px;
    }
    .rotate_header table tr td {
      /* padding: 5px 0; */
    }

    .category_title {
      margin: 0 auto;
      margin-top: 20%;
      width: 80%;
    }

    .rotate {
      transform: rotate(-90deg);
      width: 200mm;
      text-align: center;
    }

    .landscape_one {
      margin-top: -120px;
      page-break-before: always;
    }
    .landscape_two {
      margin-top: -120px;
      page-break-before: always;
    }

    .landscape_three {
      margin-top: -120px;
      page-break-before: always;
    }
    .category_title_area {
      page-break-before: always;
    }

    .pagebreak {
      clear: both;
      page-break-after: always;
    }

    .rotate span{
      -ms-writing-mode: none;
      -webkit-writing-mode: none;
      writing-mode: none;
      transform: rotate(-90deg);
      white-space: nowrap;
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
      font-size:11px;
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

    .covers {
      margin-top: 20px;
      transform: rotate(-90deg);
      transform-origin: left top;
      height: 100vw;
      position: absolute;
      top: 100%;
      width: 73%;
    }

    .table-of-contents {
      margin-top: 20px;
      page-break-before: always;
      page-break-after: always;
      transform: rotate(-90deg);
      transform-origin: left top;
      height: 100vw;
      position: absolute;
      top: 100%;
      width: 80%;
    }
    .table-of-contents h2 {
      text-align: center;
      writing-mode: vertical-rl;
      text-orientation: mixed;
      margin-top: 20px;
      margin-bottom: 60px;
      text-transform: uppercase;
    }
    .table-of-contents ul {
      list-style-type: none;
      padding-left: 0;
      writing-mode: vertical-rl;
      text-orientation: mixed;
      text-align: justify;
    }
    .table-of-contents li {
      margin-bottom: 30px;
      margin-left: 80px;
      writing-mode: vertical-rl;
      text-orientation: mixed;
      font-weight: bold;
      width: 75%; /* Meningkatkan lebar dari 80% menjadi 90% */
      text-align: justify;
    }
  .cover-page {
    transform: rotate(90deg);
    transform-origin: left top;
    width: 100vh;
    height: 100vw;
    position: absolute;
    top: 100%;
    left: 0;
  }

  .cover-page-content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px;
  }

  .cover-page-header {
    text-align: center;
  }

  .cover-page-logo {
    text-align: center;
  }

  .cover-page-footer {
    text-align: center;
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
  }
  </style>
</head>
<body>



  <!-- Main Content -->
  <div class="main-content">

    <div class="section-body">

      <div class="covers" style="text-align: center;height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
          <h2 style="font-size: 24px; margin: 0;"><u>ULTRASONIC THICKNESS MEASUREMENT REPORT</u></h2>
          <p>No. Report : <?= $report->report_number ?? '-' ?></p>
          <table style="font-size: 18px; margin: 10px auto 0; line-height: 1;">
            <tr><td>Nama Kapal</td><td>: <?= $ship->name ?? '-' ?></td></tr>
            <tr><td>Owner</td><td>: <?= $ship->owner ?? '-' ?></td></tr>
            <tr><td>Lokasi Inspeksi</td><td>: <?= $report->inspection_location ?? '-' ?></td></tr>
            <tr><td>Tanggal Inspeksi</td><td>: <?= $report->date_of_measurement ? date('d F Y', strtotime($report->date_of_measurement)) : '-' ?></td></tr>
          </table>

          <?php
          $logoUrl = $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                     $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                     Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                   );

          // Use cURL to fetch the image, ignoring SSL verification
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $logoUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $logoData = curl_exec($ch);
          curl_close($ch);

          if ($logoData !== false) {
              $logoBase64 = base64_encode($logoData);
              $logoDataUri = 'data:image/png;base64,' . $logoBase64;
          } else {
              // Fallback to a default image or leave empty
              $logoDataUri = '';
          }
          ?>
          <?php if (!empty($logoDataUri)): ?>
              <img src="<?= $logoDataUri ?>" style="max-width: 200px; height: auto; margin-top: 180px; margin-bottom: 170px; display: block; margin-left: auto; margin-right: auto;">
          <?php else: ?>
              <!-- Fallback content or message -->
              <p style="text-align: center; margin-top: 180px; margin-bottom: 170px;">Logo not available</p>
          <?php endif; ?>
        <h2 style="font-size: 24px; margin: 0;">{!! strtoupper($report->user->company->name ?? '-') !!} {!! strtoupper($report->user->company->branch ?? '') !!}</h2>

        </div>

  <!-- Table of Contents -->
  <div class="table-of-contents">
    <h2>Table of Contents</h2>
    <ul>
      <li>GENERAL PARTICULARS</li>
      <?php
      $counter = 1;
      foreach($categories as $category):
        $form_categories = isset($form_data_all[$category->id]) ? $form_data_all[$category->id] : [];
        $form_count = 0;
        foreach($form_categories as $form_category) {
          $form_count += count($form_category->form->where('report_id', $report_id));
        }
      ?>
        <li><?= ($form_count > 0 ? $category->title : '') ?></li>
      <?php
        $counter++;
      endforeach;
      ?>
    </ul>
  </div>

      <div class="rotate rotate_header" style="padding: 0 !important">



        <table style="width:80%; margin: 0 auto;">
          <tr>
            <td style="width:120px; vertical-align: middle; margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0;">
              <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                         $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                         Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                       ) ?>" style="width: auto; height: 60px; vertical-align: middle;">
            </td>
            <td style="font-size: 16px; font-weight: bold; text-align: left; text-transform: uppercase; vertical-align: middle; padding-left: 15px;">
              {!! $report->user->company->name !!} {!! $report->user->company->branch !!}
            </td>
          </tr>
        </table>
        <hr>

        <table class="report_header_table" style="margin:0 auto; font-size: 12px;">
          <tr>
            <th colspan="7" class="text-center">
              <u>THICKNESS MEASUREMENT REPORT</u>
              <br>
              No. Laporan : <?=strtoupper($report->report_number ?? '');?>
            </th>
          </tr>
          <tr>
            <th colspan="7" style="padding-top: 8px;">
              GENERAL PARTICULARS
            </th>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Nama Kapal
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <?=strtoupper($report->ship->name ?? '');?>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Pemilik Kapal
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <?=strtoupper($report->ship->owner ?? '-');?>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Alamat Pemilik
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <?=strtoupper($report->ship->owner_city ?? '-');?>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Jenis Kapal
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <?=strtoupper($report->ship->ship_type ?? '');?>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Nomor Registrasi Kapal
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <?=strtoupper($report->ship->no_reg ?? '-');?>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Klasifikasi
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <?=strtoupper($report->ship->classification ?? '-');?>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Jenis Laporan
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              {{strtoupper($report->kind_of_survey ?? '')}}
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Perusahaan Pelaksana Pengukuran<br>Ketebalan
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              {!!strtoupper($report->user->cabang ?? '-')!!}
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Operator / Inspektor
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <?php if($report->user->user_type == 'bki'){ ?>
                <?=strtoupper($report->user->name ?? '-');?>
              <?php }else{ ?>
                <?=strtoupper($report->user->name ?? '-');?>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Kualifikasi Operator / Inspektor
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              {{strtoupper($report->user->qualification ?? '-')}}
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Tempat Inspeksi
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              {{strtoupper($report->location ?? '-')}}
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Tanggal Inspeksi
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              {{strtoupper($report->date_of_measurement ? date('d-m-Y', strtotime($report->date_of_measurement)) : '-')}}
            </td>
          </tr>
          <tr>
            <td style="vertical-align: top; padding-top: 3px; padding-bottom: 3px;">
              Detail Peralatan
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              <table class="table-borderless" style="border-collapse: collapse; width: 100%;">
                <tr>
                  <td style="padding: 0; text-align: left;">Nama</td>
                  <td style="width: 10px; padding-left: 5px;"> : </td>
                  <td style="padding: 0;">{{ strtoupper($report->equipment->name ?? '-') }}</td>
                </tr>
                <tr>
                  <td style="padding: 0; text-align: left;">Manufaktur</td>
                  <td style="width: 10px; padding-left: 5px;"> : </td>
                  <td style="padding: 0;">{{ strtoupper($report->equipment->manufactur ?? '-') }}</td>
                </tr>
                <tr>
                  <td style="padding: 0; text-align: left;">Model</td>
                  <td style="width: 10px; padding-left: 5px;"> : </td>
                  <td style="padding: 0;">{{ strtoupper($report->equipment->model ?? '-') }}</td>
                </tr>
                <tr>
                  <td style="padding: 0; text-align: left;">Serial</td>
                  <td style="width: 10px; padding-left: 5px;"> : </td>
                  <td style="padding: 0;">{{ strtoupper($report->equipment->serial ?? '-') }}</td>
                </tr>
                <tr>
                  <td style="padding: 0; text-align: left;">Toleransi</td>
                  <td style="width: 10px; padding-left: 5px;"> : </td>
                  <td style="padding: 0;">± {{ strtoupper($report->equipment->tolerancy ?? '-') }} MM</td>
                </tr>
                <tr>
                  <td style="padding: 0; text-align: left;">Tipe Probe</td>
                  <td style="width: 10px; padding-left: 5px;"> : </td>
                  <td style="padding: 0;">{{ strtoupper($report->equipment->probe_type ?? '-') }}</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">
              Kriteria Penerimaan
            </td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
              BKI RULES
            </td>
          </tr>
        </table>

        <table style="width: 100%; margin-top: 0px; font-size: 12px;">
          <tr>
            <td style="width: 50%; text-align: center; vertical-align: top;">
              <p>Inspector/Operator Signature</p>
              <?=signature($report->form()->first()->id, 'operator', 'pdf');?>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top;">
              <p>Surveyor Signature</p>
              <?=signature($report->form()->first()->id, 'surveyor', 'pdf');?>
            </td>
          </tr>
        </table>

      </div>


{{--
      <div class="tab-pane">
        <div class="report_container">
          <?php foreach($certificates as $image){?>
            <div class="certificate_area">
              <?php if($image->url_resized){ ?>
                <img class="image_category" src="<?=public_path('storage').'/'.$image->url_resized;?>" />
              <?php }else{ ?>
                <img class="image_category" src="<?=public_path('storage').'/'.$image->url;?>" />
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      </div> --}}


      <?php
      $x = 0;

      foreach ($categories as $category)
      {
        $report_image = $report_images[$category->id] ?? '';
        $image_url    = $report_image ? $report_image['url'] : '';

        ?>

        <div class="tab-pane">

          <div class="report_container">

            <div class="category_title_area" style="width:100%;margin-top: 80px;margin-bottom: 80px;">
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
              foreach($form_categories as $form_category)
              {?>


                <?php foreach($category->images as $image){?>
                  <?php if($image->form_type_id == $form_category->id){ ?>
                    <div class="image_area">
                      <?php
                      $imageUrl = $image->url_resized ? Storage::disk('digitalocean')->temporaryUrl($image->url_resized, now()->addMinutes(5)) :
                                  Storage::disk('digitalocean')->temporaryUrl($image->url, now()->addMinutes(5));

                      // Use cURL to fetch the image, ignoring SSL verification
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $imageUrl);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                      $imageData = curl_exec($ch);
                      curl_close($ch);

                      if ($imageData !== false) {
                          $imageBase64 = base64_encode($imageData);
                          $imageDataUri = 'data:image/png;base64,' . $imageBase64;
                      } else {
                          // Fallback to a default image or leave empty
                          $imageDataUri = '';
                      }
                      ?>
                      <?php if (!empty($imageDataUri)): ?>
                          <img class="image_category_<?=$category->id;?>" src="<?= $imageDataUri ?>" />
                      <?php else: ?>
                          <!-- Fallback content or message -->
                          <p>Image not available</p>
                      <?php endif; ?>
                    </div>
                  <?php } ?>
                <?php } ?>


                <?php
                $status  = ($form->surveyor_verifikasi ?? 'waiting_for_approval');
                foreach($form_category->form as $form)
                {
                  if($form_category->form_data_format == 'one')
                  {
                    $form_data = $form->form_data_one->keyBy('plate_position')->toArray();

                    $unit_type        = $form->form_type->unit_type;
                    $unit             = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                    $page             = 1;
                    $total_page       = (int)ceil(count($unit['unit_position'])/$limit_page_break);
                    ?>

                    <div class="landscape landscape_one">

                      <table class="header_table">
                        <tr>
                          <td class="logo">
                            <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                       $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                       Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                     ) ?>">
                          </td>
                          <td class="bki_title">
                            {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
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


                              $color_1 = get_color($aft_dim_s_pct);
                              $color_2 = get_color($aft_dim_p_pct);
                              $color_3 = get_color($forward_dim_s_pct);
                              $color_4 = get_color($forward_dim_p_pct);
                              $color_5 = get_color($mean_dim_p);
                              $color_6 = get_color($mean_dim_s);

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
                                <td></td>
                              </tr>

                            <?php }?>

                            <?php if($line%$limit_page_break == 0 && $current_page <= $total_page){
                              $current_page++;
                              ?>

                              <?php if($total_page > 1 && $show_total_page){ ?>
                                <tr>
                                  <td colspan="20" class="paging">

                                    Table <?=$page++;?> of <?=$total_page;?>
                                  </td>
                                </tr>
                              <?php } ?>

                            </tbody>
                          </table>

                          <table class="report_signature_table">
                            <tr>
                              <td class="text-center" style="width:250px;vertical-align: bottom; line-height: 12px; font-size: 12px;">
                                <?=signature($form->id, 'operator', 'pdf');?>
                                <hr>
                                Inspektor / Operator
                              </td>
                              <td class="text-center" style="vertical-align: middle;">
                                <?=qr_code($form->id, 'pdf');?>
                              </td>
                              <td class="text-center" style="width:250px;vertical-align: bottom; line-height: 12px; font-size: 12px;">
                                <?=signature($form->id, 'surveyor', 'pdf');?>
                                <hr>
                                Surveyor
                              </td>
                            </tr>
                          </table>


                        </div>
                        <div class="landscape landscape_one">


                          <table class="header_table">
                            <tr>
                              <td class="logo">
                                <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                           $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                           Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                         ) ?>">
                              </td>
                              <td class="bki_title">
                                {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
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


                            <?php } ?>



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

                          <?php if($total_page > 1 && $show_total_page){ ?>
                            <tr>
                              <td colspan="20" class="paging">

                                Table <?=$page++;?> of <?=$total_page;?>
                              </td>
                            </tr>
                          <?php } ?>

                        </tbody>
                      </table>


                      <table class="report_signature_table">
                        <tr>
                          <td class="text-center" style="width:250px;vertical-align: bottom; line-height: 12px; font-size: 12px;">
                            <?=signature($form->id, 'operator', 'pdf');?>
                            <hr>
                            Inspektor / Operator
                          </td>
                          <td class="text-center" style="vertical-align: middle;">
                            <?=qr_code($form->id, 'pdf');?>
                          </td>
                          <td class="text-center" style="width:250px;vertical-align: bottom; line-height: 12px; font-size: 12px;">
                            <?=signature($form->id, 'surveyor', 'pdf');?>
                            <hr>
                            Surveyor
                          </td>
                        </tr>
                      </table>
                    </div>

                    <?php


                  }
                  if($form_category->form_data_format == 'two')
                  {

                    $form_data = $form ? $form->form_data_two->keyBy('plate_position')->toArray() : [];
                    $unit_type  = $form->form_type->unit_type;
                    $unit             = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                    $page             = 1;
                    $total_page       = (int)ceil(count($unit['unit_position'])/$limit_page_break);
                    ?>

                    <div class="landscape_two">

                      <table class="header_table">
                        <tr>
                          <td class="logo">
                            <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                       $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                       Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                     ) ?>">
                          </td>
                          <td class="bki_title">
                            {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
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

                              $color_1 = get_color($dim_p_pct);
                              $color_2 = get_color($dim_s_pct);

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



                            <?php if($line%$limit_page_break == 0 && $current_page < $total_page){
                              $current_page++;?>

                              <?php if($total_page > 1 && $show_total_page){ ?>
                                <tr>
                                  <td colspan="11" class="paging">

                                    Table <?=$page++;?> of <?=$total_page;?>
                                  </td>
                                </tr>
                              <?php } ?>


                            </tbody>
                          </table>


                          <table class="report_signature_table">
                            <tr>
                              <td class="text-center" style="width:250px;vertical-align: bottom;">
                                <?=signature($form->id, 'operator', 'pdf');?>
                                <hr>
                                Inspektor / Operator
                              </td>
                              <td class="text-center" style="vertical-align: middle;">
                                <?=qr_code($form->id, 'pdf');?>
                              </td>
                              <td class="text-center" style="width:250px;vertical-align: bottom;">
                                <?=signature($form->id, 'surveyor', 'pdf');?>
                                <hr>
                                Surveyor
                              </td>
                            </tr>
                          </table>

                        </div>

                        <div class="landscape_two">




                          <table class="header_table">
                            <tr>
                              <td class="logo">
                                <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                           $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                           Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                         ) ?>">
                              </td>
                              <td class="bki_title">
                                {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
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

                          <?php if($total_page > 1 && $show_total_page){ ?>
                            <tr>
                              <td colspan="11" class="paging">

                                Table <?=$page++;?> of <?=$total_page;?>
                              </td>
                            </tr>
                          <?php } ?>

                        </tbody>
                      </table>


                      <table class="report_signature_table">
                        <tr>
                          <td class="text-center" style="width:250px;vertical-align: bottom;">
                            <?=signature($form->id, 'operator', 'pdf');?>
                            <hr>
                            Inspektor / Operator
                          </td>
                          <td class="text-center" style="vertical-align: middle;">
                            <?=qr_code($form->id, 'pdf');?>
                          </td>
                          <td class="text-center" style="width:250px;vertical-align: bottom;">
                            <?=signature($form->id, 'surveyor', 'pdf');?>
                            <hr>
                            Surveyor
                          </td>
                        </tr>
                      </table>

                    </div>



                    <?php
                  }


                  if($form_category->form_data_format == 'three')
                  {
                    $form_data = $form->form_data_three->keyBy('plate_position')->toArray();

                    $unit_type  = $form->form_type->unit_type;
                    $unit       = get_unit_data($form->total_line, $form->form_type->unit_type);
                    $page             = 1;
                    $total_page       = (int)ceil(count($unit['unit_position'])/$limit_page_break);

                    ?>



                    <div class="landscape_three">



                      <table class="header_table">
                        <tr>
                          <td class="logo">
                            <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                       $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                       Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                     ) ?>">
                          </td>
                          <td class="bki_title">
                            {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
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
                              <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th colspan="2"><span>Gauged</span></th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th colspan="2"><span>Diminution P</span></th>
                            <th colspan="2"><span>Diminution S</span></th>

                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th colspan="2"><span>Gauged</span></th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th colspan="2"><span>Diminution P</span></th>
                            <th colspan="2"><span>Diminution S</span></th>

                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                            </th>
                            <th colspan="2"><span>Gauged</span></th>
                            <th rowspan="2" class="rotate_title">
                              <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
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

                                  $color_1 = get_color($dim_p_pct);
                                  $color_2 = get_color($dim_s_pct);

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





                            <?php if($line%$limit_page_break == 0 && $current_page < $total_page){
                              $current_page++; ?>


                              <?php if($total_page > 1 && $show_total_page){ ?>
                                <tr>
                                  <td colspan="31" class="paging">

                                    Table <?=$page++;?> of <?=$total_page;?>
                                  </td>
                                </tr>
                              <?php } ?>

                            </tbody>
                          </table>


                          <table class="report_signature_table">
                            <tr>
                              <td class="text-center" style="width:250px;vertical-align: bottom;">
                                <?=signature($form->id, 'operator', 'pdf');?>
                                <hr>
                                Inspektor / Operator
                              </td>
                              <td class="text-center" style="vertical-align: middle;">
                                <?=qr_code($form->id, 'pdf');?>
                              </td>
                              <td class="text-center" style="width:250px;vertical-align: bottom;">
                                <?=signature($form->id, 'surveyor', 'pdf');?>
                                <hr>
                                Surveyor
                              </td>
                            </tr>
                          </table>
                        </div>



                        <div class="landscape_three">



                          <table class="header_table">
                            <tr>
                              <td class="logo">
                                <img src="<?=public_path('storage').'/'.($report->user->company->logo_resized ?? $report->user->company->logo ?? 'uploads/company_logos/logobki_new.png');?>">
                              </td>
                              <td class="bki_title">
                                {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
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
                                  <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th colspan="2"><span>Gauged</span></th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th colspan="2"><span>Diminution P</span></th>
                                <th colspan="2"><span>Diminution S</span></th>

                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th colspan="2"><span>Gauged</span></th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th colspan="2"><span>Diminution P</span></th>
                                <th colspan="2"><span>Diminution S</span></th>

                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                                </th>
                                <th colspan="2"><span>Gauged</span></th>
                                <th rowspan="2" class="rotate_title">
                                  <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
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


                          <?php if($total_page > 1 && $show_total_page){ ?>
                            <tr>
                              <td colspan="31" class="paging">

                                Table <?=$page++;?> of <?=$total_page;?>
                              </td>
                            </tr>
                          <?php } ?>

                        </tbody>
                      </table>

                      <table class="report_signature_table">
                        <tr>
                          <td class="text-center" style="width:250px;vertical-align: bottom;">
                            <?=signature($form->id, 'operator', 'pdf');?>
                            <hr>
                            Inspektor / Operator
                          </td>
                          <td class="text-center" style="vertical-align: middle;">
                            <?=qr_code($form->id, 'pdf');?>
                          </td>
                          <td class="text-center" style="width:250px;vertical-align: bottom;">
                            <?=signature($form->id, 'surveyor', 'pdf');?>
                            <hr>
                            Surveyor
                          </td>
                        </tr>
                      </table>
                    </div>

                    <?php


                  }

                }

              }

            }?>
          </div>
        </div>
        <?php $x++;
      }?>


    </div>
  </div>


</body>
</html>
