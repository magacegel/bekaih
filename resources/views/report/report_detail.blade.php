@extends('layouts.master', ['sidebar_collapse' => true])

@section('content')

<style type="text/css">
  /* Animasi highlight untuk form yang baru di-update */
.form_table {
  transition: box-shadow 0.3s ease;
}

.form_table.highlight {
  animation: highlightPulse 2s ease-in-out;
}

@keyframes highlightPulse {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(41, 90, 214, 0);
  }
  50% {
    box-shadow: 0 0 0 4px rgba(41, 90, 214, 0.5);
  }
}
  
  .nav-tabs .nav-item {
    margin-right: 4px;
  }
  .rotate span{
    -ms-writing-mode: tb-rl;
    -webkit-writing-mode: vertical-rl;
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    white-space: nowrap;
  }
  .ship_type {
    font-weight: normal;
    font-size: 13px;
  }
  .first_column {
    white-space: nowrap;
  }
  .nav-tabs .nav-item {
    margin-right: 4px;
  }
  .rotate span{
    -ms-writing-mode: tb-rl;
    -webkit-writing-mode: vertical-rl;
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    white-space: nowrap;
  }
  .ship_type {
    font-weight: normal;
    font-size: 13px;
  }
  .first_column {
    white-space: nowrap;
  }
  /* Container untuk shortcut navigation */
/* Container untuk shortcut navigation */
/* Container untuk shortcut navigation */
.shortcut-container {
  position: fixed;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  z-index: 1050;
  display: flex;
  align-items: center;
}

/* Toggle button (panah) */
.shortcut-toggle {
  width: 35px;
  height: 35px;
  background: linear-gradient(135deg, #4FB3D9, #3A9DBF);
  border: none;
  border-radius: 50% 0 0 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: -2px 2px 8px rgba(0, 0, 0, 0.2);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  z-index: 1051;
  transform: translateX(0);
}

.shortcut-toggle:hover {
  background: linear-gradient(135deg, #3A9DBF, #2A8DAF);
  box-shadow: -3px 3px 12px rgba(0, 0, 0, 0.3);
}

/* Toggle button bergeser saat panel terbuka */
.shortcut-toggle.active {
  transform: translateX(-65px);
}

/* Icon panah */
.shortcut-toggle svg {
  width: 18px;
  height: 18px;
  fill: white;
  transition: transform 0.3s ease;
}

/* Rotasi panah saat terbuka */
.shortcut-toggle.active svg {
  transform: rotate(180deg);
}

/* Panel shortcut - MODIFIKASI dari .floating-form-tabs */
.floating-form-tabs {
  position: fixed;
  top: 50%;
  right: 0;
  transform: translateY(-50%) translateX(100%);
  background: transparent;
  box-shadow: none;
  border-radius: 0;
  padding: 15px 8px;
  z-index: 1050;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 0;
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  max-height: 400px;
  overflow-y: auto;
  overflow-x: hidden;
}

/* Panel saat terbuka */
.floating-form-tabs.active {
  transform: translateY(-50%) translateX(0);
}

/* Custom scrollbar untuk panel */
.floating-form-tabs::-webkit-scrollbar {
  width: 4px;
}

.floating-form-tabs::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 10px;
}

.floating-form-tabs::-webkit-scrollbar-thumb {
  background: rgba(41, 90, 214, 0.5);
  border-radius: 10px;
}

.floating-form-tabs::-webkit-scrollbar-thumb:hover {
  background: rgba(30, 71, 168, 0.7);
}

/* Form tab button - TETAP PAKAI YANG ADA */
.form-tab-btn {
  width: 38px;
  height: 38px;
  margin: 6px 0;
  padding: 0;
  background: transparent;
  color: #263e71;
  border: none;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  box-shadow: none;
  outline: none;
}

.form-tab-btn.active,
.form-tab-btn:hover {
  background: #295ad6;
  color: #fff;
}

.shortcut-badge-only {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  font-size: 15px;
  border-radius: 50%;
  background: #295ad6 !important;
  color: #fff;
  margin: 0;
  box-shadow: 0 1px 4px #c3d0ff4d;
  text-align: center;
  line-height: 28px;
}

.form-tab-btn .badge {
  margin: 0;
  padding: 0;
  line-height: 28px;
  font-size: 15px;
  background: #295ad6 !important;
  color: #fff;
  margin-bottom: 0;
}
.shortcut-action-buttons {
  position: sticky;
  top: 0;
  background: transparent;
  z-index: 10;
  padding: 8px 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 8px;
  flex-shrink: 0;
}

/* Container untuk form navigation (scrollable) */
.shortcut-form-list {
  flex: 1;
  overflow-y: auto;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Custom scrollbar untuk form list */
.shortcut-form-list::-webkit-scrollbar {
  width: 4px;
}

.shortcut-form-list::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 10px;
}

.shortcut-form-list::-webkit-scrollbar-thumb {
  background: rgba(41, 90, 214, 0.5);
  border-radius: 10px;
}

.shortcut-form-list::-webkit-scrollbar-thumb:hover {
  background: rgba(30, 71, 168, 0.7);
}

/* Button Add Image - warna biru seperti button aslinya */
.shortcut-add-image-btn {
  background: #17a2b8 !important;
  color: white !important;
  width: 38px !important;
  height: 38px !important;
  border-radius: 50% !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: all 0.2s ease !important;
  box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3) !important;
  margin: 4px 0 !important;
  border: none !important;
  cursor: pointer !important;
  flex-shrink: 0;
}

.shortcut-add-image-btn:hover {
  background: #138496 !important;
  box-shadow: 0 4px 8px rgba(23, 162, 184, 0.5) !important;
  transform: scale(1.05);
}

.shortcut-add-image-btn i {
  font-size: 16px;
}

/* Button Add New Form - warna primary seperti button aslinya */
.shortcut-add-form-btn {
  background: #295ad6 !important;
  color: white !important;
  width: 38px !important;
  height: 38px !important;
  border-radius: 50% !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: all 0.2s ease !important;
  box-shadow: 0 2px 4px rgba(41, 90, 214, 0.3) !important;
  margin: 4px 0 !important;
  border: none !important;
  cursor: pointer !important;
  flex-shrink: 0;
}

.shortcut-add-form-btn:hover {
  background: #1e47a8 !important;
  box-shadow: 0 4px 8px rgba(41, 90, 214, 0.5) !important;
  transform: scale(1.05);
}

.shortcut-add-form-btn i {
  font-size: 16px;
}

/* Active state untuk action buttons saat diklik */
.shortcut-add-image-btn:active,
.shortcut-add-form-btn:active {
  transform: scale(0.95);
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
  .floating-form-tabs {
    max-height: 350px;
  }
  
  .shortcut-form-list {
    min-height: 220px;
  }
  .shortcut-add-image-btn,
  .shortcut-add-form-btn {
    width: 35px !important;
    height: 35px !important;
  }

  .shortcut-add-image-btn i,
  .shortcut-add-form-btn i {
    font-size: 14px;
  }
  
  .shortcut-action-buttons {
    padding: 6px 0;
  }
  .shortcut-toggle svg {
    width: 16px;
    height: 16px;
  }
  
  .shortcut-toggle.active {
    transform: translateX(-60px);
  }
  
  .form-tab-btn {
    width: 35px;
    height: 35px;
    font-size: 12px;
  }
  
  .shortcut-badge-only {
    width: 25px;
    height: 25px;
    font-size: 13px;
    line-height: 25px;
  }
}
</style>


<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        <?=$report->ship->name;?>
        &nbsp;
        &nbsp;
        &nbsp;
        <span class="ship_type">
          (<?=$ship_type->type ?? '-'; ?>)
        </span>

      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{url('report')}}">Report</a></li>
          <li class="breadcrumb-item active">Report Detail</li>
        </ol>
      </div>

    </div>
  </div>
</div>
<!-- end page title -->


<div class="row">
  <div class="col-lg-12">
    <div class="card">

      <div class="card-body bg-light">
        <!-- Floating shortcuts rendered dynamically -->
      <div class="shortcut-container">
  <!-- Panel Shortcut Navigation -->
  <div id="floating-form-tabs" class="floating-form-tabs" style="display:none;"></div>
  
  <!-- Toggle Button (Panah) -->
  <button class="shortcut-toggle" id="shortcutToggle" type="button" style="display:none;">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
    </svg>
  </button>
</div>
        <!-- <h5 class="fw-semibold">Add New Report</h5> -->
        <ul class="nav nav-tabs" id="myTab" role="tablist" opened_tab="">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab_main" target="#main" type="button">Report Detail</button>
          </li>
          {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab_main" target="#certification" type="button">Sertifikat Kalibrasi</button>
          </li> --}}
          <?php
          $x= 0;
          foreach ($categories as $category) { ?>

            <li class="nav-item" role="presentation">
              <?php
              $needsRevision = false;
              foreach ($category->form_type as $formType) {
                foreach ($formType->form as $form) {
                  if ($form->report_id == $report->id && ($form->surveyor_verifikasi === 'revised' || $form->supervisor_verifikasi === 'revised')) {
                    $needsRevision = true;
                    break 2;
                  }
                }
              }
              ?>
              <button class="nav-link <?php echo $needsRevision ? 'bg-warning' : ''; ?>" id="tab_<?=$category->id;?>" target="#<?=$category->id;?>" type="button">
                <?=$category->abbreviation;?>
                <?php if ($needsRevision): ?>
                  <i class="fas fa-exclamation-triangle text-danger"></i>
                <?php endif; ?>
              </button>
            </li>

            <?php $x++;
          }?>

          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab_history" target="#history" type="button">Report History</button>
          </li>
        </ul>
        <div class="card-body table-responsive" style="padding:0 !important">






          <div class="tab-content" id="myTabContent">

            <div class="card">
              <div class="card-body">



                <div class="tab-pane fade show active" id="main">

                  <div class="report_container">
                    <div class="row col-12 mb-2">
                      @php
                      $companyExpiredDate = auth()->user()->company->activeCertificate->expired_date ? \Carbon\Carbon::parse(auth()->user()->company->activeCertificate->expired_date) : null;
                      $hasExpiredCompanyCert = $companyExpiredDate && $companyExpiredDate->lt(\Carbon\Carbon::now());

                      $userExpiredDate = auth()->user()->latestQualification ? \Carbon\Carbon::parse(auth()->user()->latestQualification->expired_date) : null;
                      $hasExpiredUserCert = $userExpiredDate && $userExpiredDate->lt(\Carbon\Carbon::now());

                      $isExpired = $hasExpiredCompanyCert || $hasExpiredUserCert;
                      @endphp

                      <div class="d-flex justify-content-end">
                        @if(!$isExpired)
                          {{-- @php dd($report); @endphp --}}
                          @if($report->submit_date && $report->supervisor_verifikasi != 'revised' && $report->surveyor_verifikasi != 'revised')
                            <a href="/report_preview/<?=$page_id;?>" target="_blank" class="btn btn-primary btn-sm me-2">
                              <i class="fa fa-eye" aria-hidden="true"></i> Preview Report
                            </a>
                          @else
                            <a href="{{ route('report.edit_general_particular', base64_encode($report->id)) }}"
                               class="btn btn-primary btn-sm me-2">
                              Edit General Particular
                            </a>
                            <a href="/report_preview/<?=$page_id;?>" target="_blank" class="btn btn-primary btn-sm me-2">
                              <i class="fa fa-paper-plane" aria-hidden="true"></i> Preview & Submit Report
                            </a>
                          @endif
                        @endif
                        @if(($report->supervisor_verifikasi == 'approved' && $report->surveyor_verifikasi == 'approved') || auth()->user()->hasRole('superadmin'))
                        <a href="/report_print_tcpdf/<?=$page_id;?>" target="_blank" class="btn btn-secondary btn-sm">
                          <i class="fa fa-file-pdf" aria-hidden="true"></i> Print Report
                        </a>
                        @endif
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <table class="table table-bordered">
                          <tr>
                            <th colspan="2" class="text-start">
                              General Particulars
                            </th>
                          </tr>
                          <tr>
                            <td style="width:50%">
                              No. Laporan
                              <div class="eng">Number of Report</div>
                            </td>
                            <td>
                              <?=$report->report_number;?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Jenis Laporan
                              <div class="eng">Kind of Report</div>
                            </td>
                            <td>
                              {{$report->kind_of_survey}}
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Operator / Inspektor
                              <div class="eng">Operator / Inspector</div>
                            </td>
                            <td>
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
                            <td>
                              Kualifikasi Operator / Inspektor
                              <div class="eng">Qualification of Operator / Inspector</div>
                            </td>
                            <td>
                              {{$report->user->qualification ?? '-'}}
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Tempat Inspeksi
                              <div class="eng">Place of Inspection</div>
                            </td>
                            <td>
                              {{$report->province ?? '-'}}, {{$report->city ?? '-'}}<br>
                              {{$report->location ?? '-'}}
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Tanggal Inspeksi
                              <div class="eng">Date of Inspection</div>
                            </td>
                            <td>
                              {{\Carbon\Carbon::parse($report->date_of_measurement)->locale('id')->isoFormat('D MMMM Y') ?? '-'}}
                              @if($report->end_date_measurement)
                                s/d {{\Carbon\Carbon::parse($report->end_date_measurement)->locale('id')->isoFormat('D MMMM Y')}}
                              @endif
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Detail Peralatan
                              <div class="eng">Details of Equipment</div>
                            </td>
                            <td>
                            <table class="table table-sm table-borderless">
                              <tr>
                                <th>Nama</th>
                                <td>{{ $report->equipment->name ?? '-' }}</td>
                              </tr>
                              <tr>
                                <th>Manufaktur</th>
                                <td>{{ $report->equipment->manufactur ?? '-' }}</td>
                              </tr>
                              <tr>
                                <th>Model</th>
                                <td>{{ $report->equipment->model ?? '-' }}</td>
                              </tr>
                              <tr>
                                <th>Serial</th>
                                <td>{{ $report->equipment->serial ?? '-' }}</td>
                              </tr>
                              <tr>
                                <th>Toleransi</th>
                                <td>{{ $report->equipment->tolerancy ?? '-' }}</td>
                              </tr>
                              <tr>
                                <th>Tipe Probe</th>
                                <td>{{ $report->equipment->probe_type ?? '-' }}</td>
                              </tr>
                            </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Perusahaan Pelaksana Pengukuran Ketebalan
                              <div class="eng">Company Performing Thickness Measurement</div>
                            </td>
                            <td>
                              {!! $report->user->company->name ?? '-'!!}
                              @if($report->user->company->branch)
                                <br>
                                {!! $report->user->company->branch !!}
                              @endif
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="col-md-6">
                        <table class="table table-bordered">
                          <tr>
                            <th style="width:50%">
                              Nama Kapal
                              <div class="eng">Ship's Name</div>
                            </th>
                            <th>
                              <?=$report->ship->name;?>
                            </th>
                          </tr>
                          <tr>
                            <td style="width:50%">
                              Pemilik Kapal
                              <div class="eng">Ship Owner</div>
                            </td>
                            <td>
                              <?=$report->ship->owner ?? '-';?>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:50%">
                              Alamat Pemilik
                              <div class="eng">Owner Address</div>
                            </td>
                            <td>
                              <?=$report->ship->owner_city ?? '-';?>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:50%">
                              Jenis Kapal
                              <div class="eng">Type Of Ship</div>
                            </td>
                            <td>
                              <?=$report->ship->ship_type;?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Ukuran Kapal
                              <div class="eng">Ship Dimension</div>
                            </td>
                            <td>
                              <table class="table table-sm table-borderless">
                                <tr>
                                  <td>LOA</td>
                                  <td>:</td>
                                  <td><?=$report->ship->loa ?? '-';?> Meter</td>
                                </tr>
                                <tr>
                                  <td>Breadth (B)</td>
                                  <td>:</td>
                                  <td><?=$report->ship->breadth ?? '-';?> Meter</td>
                                </tr>
                                <tr>
                                  <td>Depth (H)</td>
                                  <td>:</td>
                                  <td><?=$report->ship->depth ?? '-';?> Meter</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Tonase Kotor/Daya
                              <div class="eng">GT/HP</div>
                            </td>
                            <td>
                              <?=$report->ship->weight;?> GT <?=$report->ship->power ? '/'.$report->ship->power.'HP' : '';?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Klasifikasi
                              <div class="eng">Classification</div>
                            </td>
                            <td>
                              <?=$report->ship->classification ?? '-';?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Acceptance Criteria
                              <div class="eng">Classification</div>
                            </td>
                            <td>
                              BKI Rules
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>

                  </div>
                </div>


                <?php
                $x = 0;

                foreach ($categories as $category) {

                  $canEdit = (!$report->submit_date || ($report->supervisor_verifikasi === 'revised' || $report->surveyor_verifikasi === 'revised')) && (Auth::user()->id == $report->user_id || (Auth::user()->company_id == $report->company_id)) || auth()->user()->hasRole('superadmin');

                  ?>

                  <div class="tab-pane fade" id="<?=$category->id;?>">

                    <div class="report_container">
                      @if($form_data_all[$category->id]->pluck('form')->flatten()->filter(function($form) {
                        return ($form->supervisor_verifikasi === 'revised' || $form->surveyor_verifikasi === 'revised') && ($form->supervisor_notes || $form->surveyor_notes);
                      })->isNotEmpty())
                        <div class="supervisor-surveyor-notes mb-4" style="background-color: rgba(255, 255, 0, 0.161); padding: 10px; border-radius: 5px;">
                          <h5>Catatan Revisi</h5>
                          <div class="collapse" id="revisionNotes">
                            <ul class="list-unstyled">
                              @foreach($form_data_all[$category->id]->pluck('form')->flatten()->filter(function($form) { return $form->supervisor_notes || $form->surveyor_notes; }) as $form)
                                <li class="mb-2">
                                  <a href="#table-form-{{ $form->id }}" class="text-dark">
                                    <strong>{{ $loop->iteration }}. {{ $form->name }}</strong>
                                    @if($form->supervisor_verifikasi === 'revised' || $form->surveyor_verifikasi === 'revised')
                                      <i class="fa fa-exclamation-circle text-warning ml-1"></i>
                                    @endif
                                  </a>
                                </li>
                              @endforeach
                            </ul>
                          </div>
                          <button class="btn btn-link p-0" type="button" data-toggle="collapse" data-target="#revisionNotes" aria-expanded="true" aria-controls="revisionNotes">
                            <i class="fa fa-chevron-down"></i> Lihat Catatan Revisi
                          </button>
                        </div>
                      @endif
                      <div class="text-right">

                        <?php if($canEdit){ ?>
                          <button class="btn btn-sm btn-info add_image_button add_image_button_<?=$category->id?>" category_name="<?=$category->name;?>" category_id="<?=$category->id;?>" ship_type_id="<?=$ship_type_id;?>" style="display: inline;"><i class="fa fa-plus" aria-hidden="true"></i> Add Image</button>


                          &nbsp;&nbsp;
                          <button class="btn btn-sm btn-primary add_form_btn" category_name="<?=$category->name;?>" category_id="<?=$category->id;?>" ship_type_id="<?=$ship_type_id;?>"><i class="fa fa-plus" aria-hidden="true"></i> Add New Form</button>
                        <?php } ?>
                      </div>

                      <h4>
                        <?=$category->name;?>
                      </h4>
                      <span class="font-weight-bold"><?=$category->description;?></span>


                      <?php
                      $form_categories = $form_data_all[$category->id] ?? '';
                      if($form_categories)
                      {
                        foreach($form_categories as $form_category)
                        {

                          ?>

                      <?php if($category->images){ ?>
                        <div class="image_div image_div_<?=$category->id;?>_<?=$form_category->id;?> bg-light">

                          <?php foreach ($category->images as $image)
                          {
                            if($image->form_type_id == $form_category->id)
                              {?>

                                <div class="image_area image_area_<?=$image->id;?>">
                                  <?php if($canEdit){ ?>
                                    <button class="btn btn-sm btn-danger delete_image_button" image_id="<?=$image->id;?>" style="display:'inline';"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  <?php } ?>
                                  <a data-fslightbox href="<?=Storage::disk('digitalocean')->temporaryUrl($image->url, now()->addHours(6));?>" data-type="image">
                                    <img class="image_category image_category_<?=$image->id;?>" src="<?=Storage::disk('digitalocean')->temporaryUrl($image->url_resized, now()->addHours(6));?>">
                                  </a>
                                </div>
                              <?php } ?>
                          <?php } ?>


                        </div>

                      <?php } ?>

                      <?php
                      foreach($form_category->form as $form)
                      {
                        if($form_category->form_data_format == 'one')
                            {
                              $form_data = $form->form_data_one->keyBy('plate_position')->toArray();
                              ?>
                              @if($form->supervisor_verifikasi === 'revised' || $form->surveyor_verifikasi === 'revised')
                              <div id="table-form-{{$form->id}}" class="revision-notes mt-2 mb-3 pt-2" style="background-color: #fff3cd; padding: 10px; border-radius: 5px; border: 1px solid #ffeeba; clear: both;">
                                <h6 class="mb-2" style="color: #856404;">Catatan Revisi : {{ $form->name }}</h6>
                                @if($form->supervisor_notes)
                                  <p class="mb-2"><strong>Supervisor:</strong> {!! $form->supervisor_notes !!}</p>
                                @endif
                                @if($form->surveyor_notes)
                                  <p class="mb-0"><strong>Surveyor:</strong> {!! $form->surveyor_notes !!}</p>
                                @endif
                              </div>
                            @endif
                            <div id="form-block-{{ $form->id }}" data-form-name="{{ $form->name }}"></div>
                              <table class="form_table" border="1">
                                <thead>
                                  <tr>
                                    <th>STRAKE POSITION</th>
                                    <th colspan="16" class="form_title" style="border-right:none;">
                                      <?=$form->name;?>
                                    </th>
                                    <th colspan="3" class="text-right" style="border-left:none;">
                                      <?php if($canEdit){ ?>
                                        <a href="#" class="btn btn-sm btn-info edit_btn" form_id="<?=base64_encode($form->id);?>"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger delete_btn" form_id="<?=$form->id;?>"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                                      <?php } ?>
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
                                  $unit_type = $form->form_type->unit_type;
                                  $unit = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);

                                  foreach ($unit['unit_position'] as $position) {


                                    if(isset($form_data[$position]))
                                    {
                                      $data = $form_data[$position];
                                      $color_1 = get_color($data['aft_dim_p_pct'], $data['new_plate'], $data['aft_dim_lvl'], $data['aft_dim_lvl_unit'], $data['org_thickness']);
                                      $color_2 = get_color($data['aft_dim_s_pct'], $data['new_plate'], $data['aft_dim_lvl'], $data['aft_dim_lvl_unit'], $data['org_thickness']);
                                      $color_3 = get_color($data['forward_dim_p_pct'], $data['new_plate'], $data['forward_dim_lvl'], $data['forward_dim_lvl_unit'], $data['org_thickness']);
                                      $color_4 = get_color($data['forward_dim_s_pct'], $data['new_plate'], $data['forward_dim_lvl'], $data['forward_dim_lvl_unit'], $data['org_thickness']);
                                      $color_5 = get_color($data['mean_dim_p'], $data['new_plate'], $data['aft_dim_lvl'], $data['aft_dim_lvl_unit'], $data['org_thickness']);
                                      $color_6 = get_color($data['mean_dim_s'], $data['new_plate'], $data['aft_dim_lvl'], $data['aft_dim_lvl_unit'], $data['org_thickness']);
                                      ?>

                                      <tr>

                                        <?php if($unit_type == 'free_text') {?>
                                          <td class="first_column text-left"><?=$data['position_txt'] ?? '';?>&nbsp;</td>

                                        <?php } else { ?>
                                          <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                        <?php } ?>

                                        <td><?=$data['no_letter'];?></td>
                                        <td><?=$data['org_thickness'] ?($data['org_thickness']*100)/100:'';?></td>
                                        <td><?=$data['min_thickness'] ?($data['min_thickness']*100)/100:'';?></td>

                                        <td><?=$data['aft_gauged_p'] ?($data['aft_gauged_p']*100)/100:'';?></td>
                                        <td><?=$data['aft_gauged_s'] ?($data['aft_gauged_s']*100)/100:'';?></td>

                                        <td><?=$data['aft_dim_lvl'] ?(($data['aft_dim_lvl']*100)/100).' '.$data['aft_dim_lvl_unit']:'';?></td>

                                        <td><?=$data['aft_dim_p_mm'] ?($data['aft_dim_p_mm']*100)/100:'';?></td>
                                        <td class="<?=$color_1;?>"><?=$data['aft_dim_p_pct'] ? (($data['aft_dim_p_pct']*100)/100).'<span class="percent">%</span>':'';?></td>

                                        <td><?=$data['aft_dim_s_mm'] ?($data['aft_dim_s_mm']*100)/100:'';?></td>

                                        <td class="<?=$color_2;?>"><?=$data['aft_dim_s_pct'] ? (($data['aft_dim_s_pct']*100)/100).'<span class="percent">%</span>':'';?></td>


                                        <td><?=$data['forward_gauged_p'] ?($data['forward_gauged_p']*100)/100:'';?></td>
                                        <td><?=$data['forward_gauged_s'] ?($data['forward_gauged_s']*100)/100:'';?></td>



                                        <td><?=$data['forward_dim_lvl'] ?(($data['forward_dim_lvl']*100)/100).' '.$data['forward_dim_lvl_unit']:'';?></td>

                                        <td><?=$data['forward_dim_p_mm'] ?($data['forward_dim_p_mm']*100)/100:'';?></td>
                                        <td class="<?=$color_3;?>"><?=$data['forward_dim_p_pct'] ? (($data['forward_dim_p_pct']*100)/100).'<span class="percent">%</span>':'';?></td>


                                        <td><?=$data['forward_dim_s_mm'] ?($data['forward_dim_s_mm']*100)/100:'';?></td>
                                        <td class="<?=$color_4;?>"><?=$data['forward_dim_s_pct'] ? (($data['forward_dim_s_pct']*100)/100).'<span class="percent">%</span>':'';?></td>


                                        <td class="<?=$color_5;?>"><?=$data['mean_dim_p'] ? (($data['mean_dim_p']*100)/100).'<span class="percent">%</span>':'';?></td>
                                        <td class="<?=$color_6;?>"><?=$data['mean_dim_s'] ? (($data['mean_dim_s']*100)/100).'<span class="percent">%</span>':'';?></td>



                                      </tr>
                                    <?php }else{ ?>
                                      <tr>
                                        <td><?=$unit['unit_position_text'][$position];?></td>
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

                              <table class="report_signature_table">
                                <tr>
                                  <td class="text-center" style="width:180px;vertical-align: bottom;">
                                    <?=signature($form->id, 'operator');?>
                                    <hr>
                                    Inspektor / Operator
                                  </td>
                                  <td class="text-center" style="vertical-align: middle;">
                                    <?php if($status == 'approved'){?><?=qr_code($form->id, '');?><?php }?>
                                  </td>
                                  <td class="text-center" style="width:180px;vertical-align: bottom;">
                                    <?=signature($form->id, 'surveyor', $status);?>
                                    <hr>
                                    Surveyor
                                  </td>
                                </tr>
                                <tr></tr>
                                <tr>
                                  <td colspan="2"></td>
                                  <td style="padding-top: 10px; font-size: 10px;">
                                    <strong>Remarks:</strong>
                                    <table style="border-collapse: collapse;">
                                      <tr>
                                        <td><span class="green" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">New Plate</td>
                                      </tr>
                                      <tr>
                                        <td><span class="yellow" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">Suspect Area</td>
                                      </tr>
                                      <tr>
                                        <td><span class="red" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">Area to be Repaired</td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                              <hr>
                              <?php


                            }
                            if($form_category->form_data_format == 'two')
                            {


                              $form_data = $form ? $form->form_data_two->keyBy('port_side')->toArray() : [];

                              ?>

                              @if($form->supervisor_verifikasi === 'revised' || $form->surveyor_verifikasi === 'revised')
                                <div id="table-form-{{$form->id}}" class="revision-notes mt-2 mb-3 pt-2" style="background-color: #fff3cd; padding: 10px; border-radius: 5px; border: 1px solid #ffeeba; clear: both;">
                                  <h6 class="mb-2" style="color: #856404;">Catatan Revisi : {{ $form->name }}</h6>
                                  @if($form->supervisor_notes)
                                    <p class="mb-2"><strong>Supervisor:</strong> {!! $form->supervisor_notes !!}</p>
                                  @endif
                                  @if($form->surveyor_notes)
                                    <p class="mb-0"><strong>Surveyor:</strong> {!! $form->surveyor_notes !!}</p>
                                  @endif
                                </div>
                              @endif
                              <div id="form-block-{{ $form->id }}" data-form-name="{{ $form->name }}"></div>
                              <table class="form_table table_two" border="1">
                                <thead>
                                  <tr>
                                    <th colspan="9" class="text-left" style="border-right:none;">
                                      <?=$form->name;?>
                                    </th>
                                    <th colspan="2" class="text-right" style="border-left:none;">
                                      <?php if($canEdit){ ?>
                                        <a href="#" class="btn btn-sm btn-info edit_btn" form_id="<?=base64_encode($form->id);?>"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger delete_btn" form_id="<?=$form->id;?>"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                                      <?php } ?>
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
                                  $unit_type  = $form->form_type->unit_type;


                                  $form_data = $form->form_data_two->keyBy('plate_position')->toArray();
                                  $unit        = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                                  foreach ($unit['unit_position'] as $position) {

                                    if(isset($form_data[$position]))
                                    {
                                      $data = $form_data[$position];
                                      $color_1 = get_color($data['dim_p_pct'], $data['new_plate'], $data['dim_lvl'], $data['dim_lvl_unit'], $data['org_thickness']);
                                      $color_2 = get_color($data['dim_s_pct'], $data['new_plate'], $data['dim_lvl'], $data['dim_lvl_unit'], $data['org_thickness']);
                                      ?>

                                      <tr>

                                        <?php if($unit_type == 'free_text') {?>
                                          <td class="first_column text-left"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                        <?php } else { ?>
                                          <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                        <?php } ?>

                                        <td><?=$data['item_no'] ?? '';?></td>
                                        <td><?=$data['org_thickness'] ?? '';?></td>
                                        <td><?=$data['min_thickness'] ?? '';?></td>
                                        <td><?=$data['gauged_p'] ?? '';?></td>
                                        <td><?=$data['gauged_s'] ?? '';?></td>
                                        <td><?=$data['dim_lvl'] ? $data['dim_lvl'].' '.$data['dim_lvl_unit'] : '';?></td>
                                        <td><?=$data['dim_p_mm'] ?? '';?></td>
                                        <td class="<?=$color_1;?>"><?=$data['dim_p_pct'] ? $data['dim_p_pct'].'<span class="percent">%</span>' : '';?></td>
                                        <td><?=$data['dim_s_mm'] ?? '';?></td>
                                        <td class="<?=$color_2;?>"><?=$data['dim_s_pct'] ? $data['dim_s_pct'].'<span class="percent">%</span>' : '';?></td>


                                      </tr>
                                    <?php }else{ ?>
                                      <tr>
                                        <?php if($unit_type == 'free_text') {?>

                                          <td><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                        <?php } else { ?>
                                          <td><?=$unit['unit_position_text'][$position];?>&nbsp;</td>

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
                                    <td colspan="3">

                                      <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                                    </td>
                                  </tr>


                                </tbody>
                              </table>






                              <table class="report_signature_table">
                                <tr>
                                  <td class="text-center" style="width:180px;vertical-align: bottom;">
                                    <?=signature($form->id, 'operator');?>
                                    <hr>
                                    Inspektor / Operator
                                  </td>
                                  <td class="text-center" style="vertical-align: middle;">
                                    <?php if($status == 'approved'){?><?=qr_code($form->id, '');?><?php }?>
                                  </td>
                                  <td class="text-center" style="width:180px;vertical-align: bottom;">
                                    <?=signature($form->id, 'surveyor', $status);?>
                                    <hr>
                                    Surveyor
                                  </td>
                                </tr>
                                <tr></tr>
                                <tr>
                                  <td colspan="2"></td>
                                  <td style="padding-top: 10px; font-size: 10px;">
                                    <strong>Remarks:</strong>
                                    <table style="border-collapse: collapse;">
                                      <tr>
                                        <td><span class="green" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">New Plate</td>
                                      </tr>
                                      <tr>
                                        <td><span class="yellow" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">Suspect Area</td>
                                      </tr>
                                      <tr>
                                        <td><span class="red" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">Area to be Repaired</td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
<hr>


                              <?php
                            }

                            if($form_category->form_data_format == 'three')
                            {
                              $form_data = $form->form_data_three->keyBy('plate_position')->toArray();
                              ?>
                              <div class="form_three_wrapper">

                                @if($form->supervisor_verifikasi === 'revised' || $form->surveyor_verifikasi === 'revised')
                                <div id="table-form-{{$form->id}}" class="revision-notes mt-2 mb-3 pt-2" style="background-color: #fff3cd; padding: 10px; border-radius: 5px; border: 1px solid #ffeeba; clear: both;">
                                  <h6 class="mb-2" style="color: #856404;">Catatan Revisi : {{ $form->name }}</h6>
                                  @if($form->supervisor_notes)
                                    <p class="mb-2"><strong>Supervisor:</strong> {!! $form->supervisor_notes !!}</p>
                                  @endif
                                  @if($form->surveyor_notes)
                                    <p class="mb-0"><strong>Surveyor:</strong> {!! $form->surveyor_notes !!}</p>
                                  @endif
                                </div>
                              @endif
                                <div id="form-block-{{ $form->id }}" data-form-name="{{ $form->name }}"></div>
                                <table class="form_table" border="1">
                                  <thead>
                                    <tr>
                                      <th colspan="27" class="form_title" style="border-right:none;">
                                        <?=$form->name;?>
                                      </th>
                                      <th colspan="4" class="text-right" style="border-left:none;">
                                        <?php if($canEdit){ ?>
                                          <a href="#" class="btn btn-sm btn-info edit_btn" form_id="<?=base64_encode($form->id);?>"><i class="fa fa-edit"></i> Edit</a>
                                          <a href="#" class="btn btn-sm btn-danger delete_btn" form_id="<?=$form->id;?>"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                                        <?php } ?>
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
                                      <th rowspan="3" class="rotate"><span>No or Letter</span></th>
                                      <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
                                      <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
                                      <th colspan="2">Gauged</th>
                                      <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
                                      <th colspan="2">Diminution<br>P</th>
                                      <th colspan="2">Diminution<br>S</th>

                                      <th rowspan="3" class="rotate"><span><?=$form->form_type->number_wording;?></span></th>
                                      <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
                                      <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
                                      <th colspan="2">Gauged</th>
                                      <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
                                      <th colspan="2">Diminution<br>P</th>
                                      <th colspan="2">Diminution<br>S</th>

                                      <th rowspan="3" class="rotate"><span><?=$form->form_type->number_wording;?></span></th>
                                      <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
                                      <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
                                      <th colspan="2">Gauged</th>
                                      <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
                                      <th colspan="2">Diminution<br>P</th>
                                      <th colspan="2">Diminution<br>S</th>
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
                                    $unit_type  = $form->form_type->unit_type;
                                    $unit       = get_unit_data($form->total_line, $unit_type);
                                    foreach ($unit['unit_position'] as $position) {

                                      if(isset($form_data[$position]))
                                      {
                                        $data = $form_data[$position];
                                        ?>

                                        <tr>

                                          <?php if($unit_type == 'free_text') {?>
                                            <td class="first_column text-left"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                          <?php } else if($unit_type == 'strake') { ?>
                                            <td class="first_column text-left"><?=ucwords(str_replace('_', ' ', $unit['unit_position_text'][$position]));?></td>
                                          <?php } else { ?>
                                            <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                          <?php } ?>


                                          <?php
                                          for($zz=1;$zz<=3;$zz++)
                                          {

                                            $color_1 = get_color($data['dim_p_pct_'.$zz], $data['new_plate'], $data['dim_lvl_'.$zz], $data['dim_lvl_unit_'.$zz], $data['org_thickness_'.$zz]);
                                            $color_2 = get_color($data['dim_s_pct_'.$zz], $data['new_plate'], $data['dim_lvl_'.$zz], $data['dim_lvl_unit_'.$zz], $data['org_thickness_'.$zz]);
                                            ?>

                                            <td><?=$data['item_no_'.$zz] ?? '';?></td>
                                            <td><?=$data['org_thickness_'.$zz] ?? '';?></td>
                                            <td><?=$data['min_thickness_'.$zz] ?? '';?></td>
                                            <td><?=$data['gauged_p_'.$zz] ?? '';?></td>
                                            <td><?=$data['gauged_s_'.$zz] ?? '';?></td>
                                            <td><?=$data['dim_lvl_'.$zz] ? $data['dim_lvl_'.$zz].' '.$data['dim_lvl_unit_'.$zz] : '';?></td>
                                            <td><?=$data['dim_p_mm_'.$zz] ?? '';?></td>
                                            <td class="<?=$color_1;?>"><?=$data['dim_p_pct_'.$zz] ? $data['dim_p_pct_'.$zz].'<span class="percent">%</span>' : '';?></td>
                                            <td><?=$data['dim_s_mm_'.$zz] ?? '';?></td>
                                            <td class="<?=$color_2;?>"><?=$data['dim_s_pct_'.$zz] ? $data['dim_s_pct_'.$zz].'<span class="percent">%</span>' : '';?></td>


                                          <?php }?>


                                        </tr>
                                      <?php }else{ ?>
                                        <tr>
                                          <?php if($unit_type == 'free_text') {?>

                                            <td><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                          <?php } else { ?>
                                            <td><?=$unit['unit_position_text'][$position];?>&nbsp;</td>

                                          <?php } ?>
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
                                      <td colspan="3">

                                        <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                                      </td>
                                    </tr>

                                  </tbody>
                                </table>

                              </div>

                              <table class="report_signature_table">
                                <tr>
                                  <td class="text-center" style="width:180px;vertical-align: bottom;">
                                    <?=signature($form->id, 'operator');?>
                                    <hr>
                                    Inspektor / Operator
                                  </td>
                                  <td class="text-center" style="vertical-align: middle;">
                                    <?php if($status == 'approved'){?><?=qr_code($form->id, '');?><?php }?>
                                  </td>
                                  <td class="text-center" style="width:180px;vertical-align: bottom;">
                                    <?=signature($form->id, 'surveyor', $status);?>
                                    <hr>
                                    Surveyor
                                  </td>
                                </tr>
                                <tr></tr>
                                <tr>
                                  <td colspan="2"></td>
                                  <td style="padding-top: 10px; font-size: 10px;">
                                    <strong>Remarks:</strong>
                                    <table style="border-collapse: collapse;">
                                      <tr>
                                        <td><span class="green" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">New Plate</td>
                                      </tr>
                                      <tr>
                                        <td><span class="yellow" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">Suspect Area</td>
                                      </tr>
                                      <tr>
                                        <td><span class="red" style="display: inline-block; width: 20px; height: 10px;"></span></td>
                                        <td style="padding-left: 2px;">Area to be Repaired</td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>

                              <hr>

                              <?php


                            }
                          }
                        }

                      }?>




                      <hr>

                    </div>
                  </div>
                  <?php $x++; }?>

                <div class="tab-pane fade" id="history">
                  <div class="report_container">
                    <div class="timeline">
                      @foreach($report->reportHistory()->orderBy('created_at', 'desc')->get() as $history)
                      <div class="timeline-item">
                        <div class="timeline-point">
                          @if($history->status == 'submitted')
                            <i class="fas fa-paper-plane text-info"></i>
                          @elseif($history->status == 'revised')
                            <i class="fas fa-exclamation-circle text-warning"></i>
                          @elseif($history->status == 'approved')
                            <i class="fas fa-check-circle text-success"></i>
                          @endif
                        </div>
                        <div class="timeline-content">
                          <div class="d-flex justify-content-between">
                            <h6 class="mb-1">
                              @if($history->status == 'submitted')
                                <span class="badge bg-info">Submitted</span>
                              @elseif($history->status == 'revised')
                                <span class="badge bg-warning">Need Revision</span>
                              @elseif($history->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                              @endif
                            </h6>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y H:i') }}</small>
                          </div>
                          <p class="mb-1">{{ $history->notes }}</p>
                          <small class="text-muted">By: {{ $history->user->name }}</small>
                        </div>
                      </div>
                      @endforeach
                    </div>
                  </div>

                  <style>
                    .timeline {
                      position: relative;
                      padding: 20px 0;
                    }
                    .timeline:before {
                      content: '';
                      position: absolute;
                      top: 0;
                      left: 15px;
                      height: 100%;
                      width: 2px;
                      background: #e9ecef;
                    }
                    .timeline-item {
                      position: relative;
                      padding-left: 40px;
                      margin-bottom: 20px;
                    }
                    .timeline-point {
                      position: absolute;
                      left: 7px;
                      width: 20px;
                      height: 20px;
                      border-radius: 50%;
                      text-align: center;
                      line-height: 20px;
                      background: white;
                    }
                    .timeline-content {
                      padding: 15px;
                      background: #f8f9fa;
                      border-radius: 4px;
                    }
                  </style>
                </div>
                </div>
              </div>


            </div>



          </div>

        </div><!-- end card-body -->
      </div><!-- end card -->
    </div>
    <!-- end col -->
  </div>

















  <div class="modal fade" id="create_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Add New Form</h3>

          <!--begin::Close-->
          <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
            <h4>X</h4>
          </div>
          <!--end::Close-->
        </div>

        <div class="modal-body">

          <form id="create_form" action="<?=url('form_data');?>" method="POST">


            <table class="table modal_table">
              <input type="hidden" name="_token" value="<?=csrf_token();?>">
              <input type="hidden" name="report_id" value="<?=$report_id;?>">
              <input type="hidden" name="action" value="create">
              <tr>
                <td class="align-middle" style="width:122px">Category</td>
                <td class="align-middle" style="width:2px;">:</td>
                <td>
                  <input type="hidden" name="category_id" class="category_id" value="">
                  <input type="hidden" name="ship_type_id" class="ship_type_id" value="">
                  <span class="modal_category"></span>
                </td>
              </tr>
              <tr>
                <td>Form Type</td>
                <td style="width:2px;">:</td>
                <td>
                  <select name="form_type" class="form-control form_type">
                  </select>
                </td>
              </tr>

              <tr>
                <td class="align-middle">Form Name</td>
                <td class="align-middle" style="width:2px;">:</td>
                <td>
                  <input type="text" name="form_name" class="form-control form_name">
                </td>
              </tr>

              <tr class="tr_three" style="display:none;">
                <td class="align-middle">Title 1</td>
                <td class="align-middle" style="width:2px;">:</td>
                <td>
                  <input type="text" name="title_1" class="form-control title_1" value="FIRST TRANSVERSE SECTION AT FRAME">
                </td>
              </tr>

              <tr class="tr_three" style="display:none;">
                <td class="align-middle">Title 2</td>
                <td class="align-middle" style="width:2px;">:</td>
                <td>
                  <input type="text" name="title_2" class="form-control title_2" value="SECOND TRANSVERSE SECTION AT FRAME">
                </td>
              </tr>

              <tr class="tr_three" style="display:none;">
                <td class="align-middle">Title 3</td>
                <td class="align-middle" style="width:2px;">:</td>
                <td>
                  <input type="text" name="title_3" class="form-control title_3" value="THIRD TRANSVERSE SECTION AT FRAME">
                </td>
              </tr>



            </table>

          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary create_form_btn">Create Form</button>
        </div>
      </div>
    </div>
  </div>






  <div class="modal fade" tabindex="-1" id="upload_certificate_modal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <form id="upload_certificate_form" action="<?=url('report_image');?>" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h3 class="modal-title">Add Certificate</h3>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
              <h4>X</h4>
            </div>
            <!--end::Close-->
          </div>

          <div class="modal-body">


            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="upload_certificate">
            <input type="hidden" name="report_id" value="<?=$report_id;?>">

            <table cellpadding="5" style="width:100%">


              <tr>
                <td>Title</td>
                <td style="width:2px;">:</td>
                <td>
                  <input type="text" class="form-control" name="title">
                </td>
              </tr>
              <tr>
                <td style="vertical-align: top;">
                  Upload file
                </td>
                <td style="width:2px;">:</td>
                <td class="file_area">
                  <input type="file" class="image" name="image">
                </td>
              </tr>



            </table>


          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary upload_certificate_btn">Upload Certificate</button>
          </div>

        </form>
      </div>
    </div>
  </div>





  <div class="modal fade" tabindex="-1" id="upload_image_modal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <form id="upload_image_form" action="<?=url('report_image');?>" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h3 class="modal-title">Add Image for <span class="modal_category_name"></span></h3>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
              <h4>X</h4>
            </div>
            <!--end::Close-->
          </div>

          <div class="modal-body">


            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="upload">
            <input type="hidden" name="report_id" value="<?=$report_id;?>">
            <input type="hidden" name="category_id" class="category_id selected_category_id" value="">

            <table cellpadding="5">


              <tr>
                <td>Form Type</td>
                <td style="width:2px;">:</td>
                <td>
                  <select name="form_type_id" class="form-control image_form_type">
                  </select>
                </td>
              </tr>
              <tr>
                <td style="vertical-align: top;">
                  Upload file
                </td>
                <td style="width:2px;">:</td>
                <td class="file_area">
                  <input type="file" class="image" name="image" accept="image/*">
                </td>
              </tr>



            </table>








          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary upload_image_btn">Upload Image</button>
          </div>

        </form>
      </div>
    </div>
  </div>


  @endsection


  @section('css')

  <style type="text/css">

    .tab-pane.fade {
      display: none;
    }
    .tab-pane.show {
      display: block;
    }

    .form_table {
      margin:20px 0 30px 0;
      width: 100%;
    }
    .form_table td , .form_table th {
      text-align: center;
      padding: 2px 6px;
      border:1px solid #666;
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
    button.nav-link:hover {
      cursor: pointer;
    }

    .image_area {
      width: fit-content;
      position: relative;
      float: left;
      margin:20px 20px 0 0;
    }
    .delete_image_button, .delete_certificate_button {
      position: absolute;
      z-index: 1;
      right: 8px;
      top: 8px;
    }
    .image_category {
      height: 150px;
      width: auto;
      border: 1px solid #DDD;
      border-radius: 12px;
      box-shadow:3px 3px #ededed;
    }
    .image_div {
      padding:20px 0;
      float: left;
      width: 100%;
    }

  </style>
  <link href="/assets/modules/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
  @endsection
  @section('js')

  <script src="/assets/modules/select2/dist/js/select2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
  $(document).ready(function(){
    
    // ========== AUTO SCROLL & HIGHLIGHT FUNCTIONS ==========
    // Auto scroll dan highlight form setelah update/back
    function scrollToFormAndHighlight() {
      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab');
      const formId = urlParams.get('form_id');
      
      if (tab && formId) {
        // Klik tab yang sesuai
        $('#tab_' + tab).click();
        
        // Tunggu tab terbuka, lalu scroll ke form
        setTimeout(function() {
          const formElement = $('#form-block-' + formId);
          
          if (formElement.length > 0) {
            // Scroll smooth ke form
            $('html, body').animate({
              scrollTop: formElement.offset().top - 100
            }, 800, function() {
              highlightForm(formElement);
            });
          }
        }, 300);
      }
    }

    // Fungsi untuk highlight form dengan animasi
    function highlightForm($element) {
      const $formTable = $element.next('.form_table');
      
      if ($formTable.length > 0) {
        $formTable.css({
          'box-shadow': '0 0 0 4px rgba(41, 90, 214, 0.5)',
          'transition': 'all 0.3s ease'
        });
        
        setTimeout(function() {
          $formTable.css({
            'box-shadow': 'none'
          });
        }, 2000);
      }
    }

    // Panggil fungsi auto scroll saat halaman load
    scrollToFormAndHighlight();

    // ========== SELECT2 INITIALIZATION ==========
    $('.select2').select2({
      minimumInputLength: 3,
      allowClear: true,
      placeholder: '- Masukkan Nama/NUP -',
      ajax: {
        url: '<?=url('/user_list');?>',
        dataType: 'json',
        delay: 800,
        data: params => ({ search: params.term }),
        processResults: data => ({ results: data })
      }
    });

    // ========== TAB FUNCTIONALITY ==========
    $('.nav-tabs button').click(function(e){
      e.preventDefault();
      $('.nav-link').removeClass('active');
      $(this).addClass('active');
      let target = $(this).attr('target');
      $('.tab-pane').removeClass('show active');
      $(target).addClass('show active');
      $('.nav-tabs').attr('opened_tab', target.substring(1));
    });

    // ========== EDIT BUTTON FUNCTIONALITY ==========
    $('.edit_btn').click(function(e){
      e.preventDefault();
      let form_id = $(this).attr('form_id');
      let opened_tab = $('.nav-tabs').attr('opened_tab');
      window.location.href = `<?=url('/form/edit');?>/${form_id}?tab=${opened_tab}`;
    });

    // ========== DELETE BUTTON FUNCTIONALITY ==========
    $('.delete_btn').click(function(e){
      e.preventDefault();
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          let form_id = $(this).attr('form_id');
          $.ajax({
            url: "<?=url('/form_data')?>",
            type: 'post',
            dataType: "json",
            data: {
              form_id: form_id,
              action: 'delete',
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
              if(response.status === 'success') {
                iziToast.success({
                  title: 'Success',
                  message: response.message
                });
                let opened_tab = $('.nav-tabs').attr('opened_tab');
                window.location.href = `<?=url('/report_detail');?>/<?=base64_encode($report_id);?>?tab=${opened_tab}`;
              } else {
                iziToast.error({
                  title: 'Error',
                  message: response.message
                });
              }
            },
            error: () => {
              iziToast.error({
                title: 'Error',
                message: 'An error occurred'
              });
            }
          });
        }
      });
    });

    // ========== FORM TYPE FUNCTIONALITY ==========
    function check_format() {
      let option = $('.form_type option:selected').attr('format');
      let form_name = $('.form_type option:selected').attr('form_name');
      $('.form_name').val(form_name);
      $('.tr_three').toggle(option === 'three');
      if(option === 'two') {
        $('.form_name').val('TANK/HOLD DESCRIPTION : ');
      }
    }

    $('.form_type').change(check_format);

    // ========== ADD FORM BUTTON FUNCTIONALITY ==========
    $('.add_form_btn').click(function(e){
      e.preventDefault();
      $(".total_line").val(<?=$total_line;?>).change();
      let category = $(this).attr('category_name');
      let category_id = $(this).attr('category_id');
      let ship_type_id = $(this).attr('ship_type_id');
      $('.category_id').val(category_id);
      $('.ship_type_id').val(ship_type_id);
      $('.modal_category').html(category);
      $('#create_modal').modal('show');

      $.ajax({
        url: "<?=url('/report_data')?>",
        type: 'post',
        dataType: "json",
        data: {
          category_id: category_id,
          ship_type_id: ship_type_id,
          action: 'get_form_type',
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if(response.status === 'success') {
            $('.form_type option').remove();
            response.data.forEach((data, index) => {
              $('.form_type').append(`<option value="${data.id}" format="${data.form_data_format}" form_name="${data.name} - " ${index === 0 ? 'selected' : ''}>${data.name}</option>`);
            });
            check_format();
          }
        },
        error: () => {
          iziToast.error({
            title: 'Error',
            message: 'An error occurred'
          });
        }
      });
    });

    // ========== CREATE FORM BUTTON FUNCTIONALITY ==========
    $('.create_form_btn').click(function(e){
      e.preventDefault();
      if(!$('.form_name').val()) {
        iziToast.error({
          title: 'Error',
          message: 'Please insert form name!'
        });
        $('.operator').focus();
      } else {
        $('#create_form').submit();
      }
    });

    // ========== ADD IMAGE BUTTON FUNCTIONALITY ==========
    $('.add_image_button').click(function(e){
      e.preventDefault();
      $('.modal_category_name').html($(this).attr('category_name'));
      $('#upload_image_form .category_id').val($(this).attr('category_id'));
      let category_id = $(this).attr('category_id');
      let ship_type_id = $(this).attr('ship_type_id');
      $('.category_id').val(category_id);
      $('.ship_type_id').val(ship_type_id);

      $.ajax({
        url: "<?=url('/report_data')?>",
        type: 'post',
        dataType: "json",
        data: {
          category_id: category_id,
          ship_type_id: ship_type_id,
          action: 'get_form_type',
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if(response.status === 'success') {
            $('.image_form_type option').remove();
            response.data.forEach((data, index) => {
              $('.image_form_type').append(`<option value="${data.id}" format="${data.form_data_format}" form_name="${data.name} - " ${index === 0 ? 'selected' : ''}>${data.name}</option>`);
            });
          }
        },
        error: () => {
          iziToast.error({
            title: 'Error',
            message: 'An error occurred'
          });
        }
      });

      $('#upload_image_modal').modal('show');
    });

    // ========== ADD CERTIFICATE BUTTON FUNCTIONALITY ==========
    $('.add_certificate_button').click(() => $('#upload_certificate_modal').modal('show'));

    // ========== DELETE CERTIFICATE BUTTON FUNCTIONALITY ==========
    $('.delete_certificate_button').click(function(e){
      e.preventDefault();
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          let image_id = $(this).attr('image_id');
          $.ajax({
            url: "<?=url('/report_image')?>",
            type: 'post',
            dataType: "json",
            data: {
              image_id: image_id,
              report_id: '<?=$report_id;?>',
              action: 'delete_certificate',
              _token: '{{ csrf_token() }}'
            },
            success: function(data) {
              if(data.status === 'success') {
                iziToast.success({
                  title: 'Success',
                  message: data.message
                });
                $('.image_area_'+image_id).remove();
                refreshFsLightbox();
              }
            },
            error: () => {
              iziToast.error({
                title: 'Error',
                message: 'An error occurred'
              });
            }
          });
        }
      });
    });

    // ========== DELETE IMAGE BUTTON FUNCTIONALITY ==========
    $('.delete_image_button').click(function(e){
      e.preventDefault();
      deleteImage($(this).attr('image_id'));
    });

    // Function to handle image deletion
    function deleteImage(image_id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "<?=url('/report_image')?>",
            type: 'post',
            dataType: "json",
            data: {
              image_id: image_id,
              report_id: '<?=$report_id;?>',
              action: 'delete',
              _token: '{{ csrf_token() }}'
            },
            success: function(data) {
              if(data.status === 'success') {
                iziToast.success({
                  title: 'Success',
                  message: data.message
                });
                $('.image_area_'+image_id).remove();
                refreshFsLightbox();
              }
            },
            error: () => {
              iziToast.error({
                title: 'Error',
                message: 'An error occurred'
              });
            }
          });
        }
      });
    }

    // ========== UPLOAD IMAGE FORM SUBMISSION ==========
    $('#upload_image_form').submit(function(e){
      e.preventDefault();
      let category_id = $('.selected_category_id').val();
      $.ajax({
        url: "<?=url('/report_image')?>",
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(data) {
          if(data.status === 'success') {
            iziToast.success({
              title: 'Success',
              message: data.message
            });
            let image = `
              <div class="image_area image_area_${data.image_id}">
                <button class="btn btn-sm btn-danger delete_image_button" image_id="${data.image_id}" style="display:inline;">
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
                <a data-fslightbox href="${data.image}">
                  <img class="image_category image_category_${data.image_id}" src="${data.image_resized}">
                </a>
              </div>`;
            $(`.image_div_${category_id}_${data.form_type_id}`).append(image);
            $('.delete_image_button[image_id="'+data.image_id+'"]').click(function(e){
              e.preventDefault();
              deleteImage($(this).attr('image_id'));
            });
            $('#upload_image_modal').modal('hide');
            refreshFsLightbox();
          } else {
            iziToast.error({
              title: 'Error',
              message: data.message
            });
          }
        },
        error: function (err) {
          if (err.status === 422) {
            $.each(err.responseJSON.errors, function (i, error) {
              let el = $('#upload_image_form').find('.'+i+'');
              el.after($('<div class="error_message">'+error[0]+'</div>'));
            });
            $('.error_message').delay(5000).fadeOut('slow');
          }
        }
      });
    });

    // ========== UPLOAD CERTIFICATE FORM SUBMISSION ==========
    $('#upload_certificate_form').submit(function(e){
      e.preventDefault();
      $.ajax({
        url: "<?=url('/report_image')?>",
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(data) {
          if(data.status === 'success') {
            iziToast.success({
              title: 'Success',
              message: data.message
            });
            let image = `
              <div class="image_area image_area_${data.image_id}">
                <button class="btn btn-sm btn-danger delete_certificate_button" image_id="${data.image_id}" style="display:'inline';">
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
                <a data-fslightbox href="<?=url('storage');?>/${data.image}">
                  <img class="image_category image_category_${data.image_id}" src="<?=url('storage');?>/${data.image}">
                </a>
              </div>`;
            $('.certificate_div').append(image);
            $('#upload_certificate_modal').modal('hide');
            refreshFsLightbox();
          } else {
            iziToast.error({
              title: 'Error',
              message: data.message
            });
          }
        },
        error: function (err) {
          if (err.status === 422) {
            $.each(err.responseJSON.errors, function (i, error) {
              let el = $('#upload_image_form').find('.'+i+'');
              el.after($('<div class="error_message">'+error[0]+'</div>'));
            });
            $('.error_message').delay(5000).fadeOut('slow');
          }
        }
      });
    });

    // ========== TAB ACTIVATION FROM URL PARAMETER ==========
    <?php if(isset($_GET['tab'])){?>
      $('#tab_<?=$_GET['tab'];?>').click();
    <?php }?>

    // ========== FLOATING TABS FUNCTIONS ==========
    // ========== FLOATING TABS FUNCTIONS WITH FIXED ACTION BUTTONS ==========
function renderFloatingTabs() {
  var $activeTabBtn = $('.nav-link.active');
  var openedTab = $activeTabBtn.attr('target') || "";
  
  if (!openedTab || openedTab === '#main' || openedTab === '#history') {
    $('#floating-form-tabs').hide().removeClass('active').html('');
    $('#shortcutToggle').hide();
    return;
  }
  
  var $forms = $(openedTab + ' [id^=form-block-]');
  
  // Dapatkan data dari button Add Image & Add New Form yang aktif
  var $addImageBtn = $(openedTab).closest('.tab-pane').find('.add_image_button');
  var $addFormBtn = $(openedTab).closest('.tab-pane').find('.add_form_btn');
  
  var categoryId = $addImageBtn.attr('category_id');
  var categoryName = $addImageBtn.attr('category_name');
  var shipTypeId = $addImageBtn.attr('ship_type_id');
  
  if ($forms.length === 0 && !categoryId) {
    $('#floating-form-tabs').hide().removeClass('active').html('');
    $('#shortcutToggle').hide();
    return;
  }
  
  var html = '';
  
  // Container untuk Action Buttons (Fixed di atas)
  html += '<div class="shortcut-action-buttons">';
  
  // Tambahkan Action Buttons jika ada categoryId
  if (categoryId) {
    html += `
      <button type="button" 
        class="shortcut-add-image-btn" 
        title="Add Image"
        data-category-id="${categoryId}"
        data-category-name="${categoryName}"
        data-ship-type-id="${shipTypeId}">
        <i class="fa fa-image"></i>
      </button>
      <button type="button" 
        class="shortcut-add-form-btn" 
        title="Add New Form"
        data-category-id="${categoryId}"
        data-category-name="${categoryName}"
        data-ship-type-id="${shipTypeId}">
        <i class="fa fa-plus"></i>
      </button>
    `;
  }
  
  html += '</div>';
  
  // Container untuk Form Navigation (Scrollable)
  html += '<div class="shortcut-form-list">';
  
  // Tambahkan Form Navigation Buttons
  $forms.each(function(idx, el){
    var formName = $(el).data('form-name') || 'Form ' + (idx+1);
    html += `
      <button type="button" class="form-tab-btn"
        title="${formName}"
        onclick="scrollToForm('${el.id}')">
        <span class='badge bg-primary mb-1 shortcut-badge-only'>${idx+1}</span>
      </button>
    `;
  });
  
  html += '</div>';
  
  $('#floating-form-tabs').html(html).show();
  $('#shortcutToggle').show();
  
  // Bind click events untuk action buttons
  bindActionButtons();
}

// ========== BIND ACTION BUTTONS EVENTS ==========
function bindActionButtons() {
  // Add Image Button Click
  $('.shortcut-add-image-btn').off('click').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var categoryId = $(this).data('category-id');
    var categoryName = $(this).data('category-name');
    var shipTypeId = $(this).data('ship-type-id');
    
    $('.modal_category_name').html(categoryName);
    $('#upload_image_form .category_id').val(categoryId);
    $('.ship_type_id').val(shipTypeId);
    
    // Load form types
    $.ajax({
      url: "<?=url('/report_data')?>",
      type: 'post',
      dataType: "json",
      data: {
        category_id: categoryId,
        ship_type_id: shipTypeId,
        action: 'get_form_type',
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.status === 'success') {
          $('.image_form_type option').remove();
          response.data.forEach((data, index) => {
            $('.image_form_type').append(`<option value="${data.id}" format="${data.form_data_format}" form_name="${data.name} - " ${index === 0 ? 'selected' : ''}>${data.name}</option>`);
          });
        }
      }
    });
    
    $('#upload_image_modal').modal('show');
  });
  
  // Add New Form Button Click
  $('.shortcut-add-form-btn').off('click').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var categoryId = $(this).data('category-id');
    var categoryName = $(this).data('category-name');
    var shipTypeId = $(this).data('ship-type-id');
    
    $('.category_id').val(categoryId);
    $('.ship_type_id').val(shipTypeId);
    $('.modal_category').html(categoryName);
    
    // Load form types
    $.ajax({
      url: "<?=url('/report_data')?>",
      type: 'post',
      dataType: "json",
      data: {
        category_id: categoryId,
        ship_type_id: shipTypeId,
        action: 'get_form_type',
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.status === 'success') {
          $('.form_type option').remove();
          response.data.forEach((data, index) => {
            $('.form_type').append(`<option value="${data.id}" format="${data.form_data_format}" form_name="${data.name} - " ${index === 0 ? 'selected' : ''}>${data.name}</option>`);
          });
          check_format();
        }
      }
    });
    
    $('#create_modal').modal('show');
  });
}

    // Render floating tabs saat halaman load
    setTimeout(renderFloatingTabs, 200);
    
    // Re-render saat ganti tab
    $('.nav-link').on('click', function() {
      setTimeout(renderFloatingTabs, 150);
    });
    
    // Toggle button click event
    $('#shortcutToggle').on('click', function(e) {
      e.stopPropagation();
      $(this).toggleClass('active');
      $('#floating-form-tabs').toggleClass('active');
    });
    
    // Tutup panel saat klik di luar
    $(document).on('click', function(event) {
      if (!$(event.target).closest('.shortcut-container').length) {
        $('#shortcutToggle').removeClass('active');
        $('#floating-form-tabs').removeClass('active');
      }
    });

  }); // END $(document).ready()

  // ========== GLOBAL FUNCTION FOR SCROLL TO FORM ==========
  function scrollToForm(formBlockId) {
    var el = document.getElementById(formBlockId);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      $('.form-tab-btn').removeClass('active');
      var $tabPane = $('.tab-pane.show.active');
      var idx = $tabPane.find('[id^=form-block-]').toArray().findIndex(div => div.id === formBlockId);
      if (idx !== -1) {
        $('.form-tab-btn').eq(idx).addClass('active');
        setTimeout(() => $('.form-tab-btn').eq(idx).removeClass('active'), 1500);
      }
    }
  }
</script>
@endsection
