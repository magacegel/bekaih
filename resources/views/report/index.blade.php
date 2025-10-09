@extends('layouts.master')

@section('content')







<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Add New Report
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Report</li>
        </ol>
      </div>

    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-lg-12">
    <div class="card">

      <div class="card-body">

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- Disable tombol jika sertifikat expired -->
        @php
        $user = auth()->user();
        $company = $user->company;
        
        $companyExpiredDate = $company->activeCertificate->expired_date ? 
            \Carbon\Carbon::parse($company->activeCertificate->expired_date) : null;
        $hasExpiredCompanyCert = $companyExpiredDate && $companyExpiredDate->lt(\Carbon\Carbon::now());

        $userExpiredDate = $user->latestQualification ? 
            \Carbon\Carbon::parse($user->latestQualification->expired_date) : null;
        $hasExpiredUserCert = $userExpiredDate && $userExpiredDate->lt(\Carbon\Carbon::now());

        $isExpired = $hasExpiredCompanyCert || $hasExpiredUserCert;
        @endphp

        <div class="text-right" style="padding-right: 28px;">
          <a href="#" class="btn btn-sm btn-primary add_report_btn" @if($isExpired) disabled @endif>
            <i class="fa fa-plus"></i>
            Add New
          </a>
        </div>

        <!-- <h5 class="fw-semibold">Add New Report</h5> -->

        <div class="card-body table-responsive">
          <table border="1" id="my_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th>Ship Name</th>
                <th>Report Name</th>
                <th>Report No.</th>
                <th>Tanggal Mulai Pengukuran</th>
                <th>Tanggal Selesai Pengukuran</th>
                <th>Tanggal Submit Laporan</th>
                <th>Status</th>
                <?php if(auth()->user()->hasAnyRole(['superadmin', 'administrator'])){ ?>
                  <th>Creator</th>
                <?php } ?>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

        </div>

      </div><!-- end card-body -->
    </div><!-- end card -->
  </div>
  <!-- end col -->
</div>


<!-- Main Content -->




<div class="modal fade" id="create_report_modal">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Report</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>

      <div class="modal-body">

        <form id="create_report" action="<?=url('report_data');?>" method="POST">

          <div class="row">
            <div class="col-md-5">
              <table class="table modal_table">
                <input type="hidden" name="_token" value="<?=csrf_token();?>">
                <input type="hidden" name="action" value="create">

                <tr>
                  <td class="align-middle" style="width:190px">Nama Kapal</td>
                  <td class="align-middle" style="width:2px;">:</td>
                  <td>
                    <select name="select_name" class="form-control select2_kapal" style="width:100%;">
                      <option value="">- Masukkan Nama Kapal -</option>
                    </select>
                    <input type="hidden" name="nama_kapal" class="nama_kapal">
                  </td>
                </tr>

                <!-- Hidden field untuk jenis form kapal - otomatis pilih yang pertama -->
                <input type="hidden" name="ship_type_id" value="<?php echo isset($ship_types[0]) ? $ship_types[0]->id : ''; ?>" />
              <tr>
                <td class="align-middle" style="width:190px">Klasifikasi Kapal</td>
                <td class="align-middle" style="width:2px;">:</td>
                <td>
                 <select name="ship_classification" class="form-control select2_classification" style="width:100%;">
                  <option value="" selected>- Masukkan Klasifikasi Kapal -</option>
                  <option value="BKI">BKI</option>
                  <option value="ABS">ABS</option>
                  <option value="BV">BV</option>
                  <option value="CCS">CCS</option>
                  <option value="CRS">CRS</option>
                  <option value="DNV">DNV</option>
                  <option value="IRS">IRS</option>
                  <option value="KR">KR</option>
                  <option value="LR">LR</option>
                  <option value="NK">NK</option>
                  <option value="PRS">PRS</option>
                  <option value="RINA">RINA</option>
                  <option value="TURK">TURK</option>
                </select>
              </td>
            </tr>
            <tr>
              <td class="align-middle" style="width:190px">Jenis Survey</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="kind_of_survey" class="form-control kind_of_survey" style="width:100%;" list="surveyOptions" placeholder="Masukkan jenis survey">
                <datalist id="surveyOptions">
                  <option value="Special Survey">
                  <option value="Docking Survey">
                  <option value="Intermediate Survey">
                </datalist>
              </td>
            </tr>
            <tr>
              <td class="align-middle">Nomor Registrasi</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="no_reg_txt"></span>
                <input type="hidden" name="no_reg" class="no_reg">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Nomor IMO</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="no_imo_txt"></span>
                <input type="hidden" name="no_imo" class="no_imo">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Tipe Kapal</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="ship_type_txt"></span>
                <input type="hidden" name="ship_type" class="ship_type">
              </td>
            </tr>
            <tr>
              <td class="align-middle">GT/HP</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="ship_weight_txt"></span>
                <input type="hidden" name="ship_weight" class="ship_weight">
              </td>
            </tr>
            <tr>
              <td class="align-middle">LOA</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="ship_loa_txt"></span>
                <input type="hidden" name="ship_loa" class="ship_loa">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Breadth</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="ship_breadth_txt"></span>
                <input type="hidden" name="ship_breadth" class="ship_breadth">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Height</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="ship_height_txt"></span>
                <input type="hidden" name="ship_height" class="ship_height">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Pemilik</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="ship_owner_txt"></span>
                <input type="hidden" name="ship_owner" class="ship_owner">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Kota</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span class="owner_city_txt"></span>
                <input type="hidden" name="owner_city" class="owner_city">
              </td>
            </tr>
          </table>
        </div>
        
        <div class="col-md-7">
          <table class="table modal_table">
            <tr>
              <td class="align-middle">Nama Report</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="report_name" class="form-control report_name" value="Ultrasonic Thickness Measurement Report">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Nomor Report</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="report_number" placeholder="Masukkan Nomor Report" class="form-control report_number">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Operator</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span><?=auth()->user()->name;?></span>
              </td>
            </tr>
            <tr>
              <td class="align-middle">Operator Qualification</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <span><?=auth()->user()->qualification;?></span>
              </td>
            </tr>
            <tr>
              <td class="align-middle">Surveyor</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="surveyor" class="form-control select2_surveyor" style="width:100%;">
                  <option value="">- Masukkan Nama/NUP -</option>
                </select>
              </td>
            </tr>
            <tr>
              <td class="align-middle">Lokasi Pengukuran</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <div class="row mb-2">
                  <div class="col-md-6">
                    <select name="province" id="provinsi" class="form-control" required>
                      <option value="">Pilih Provinsi</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select name="city" id="kota" class="form-control" required>
                      <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                  </div>
                </div>
                <textarea name="location" placeholder="Masukkan Detail Lokasi Pengukuran (Nama Galangan/Pelabuhan, dll)" class="form-control measurement_location" rows="2"></textarea>
              </td>
            </tr>
            <tr>
              <td class="align-middle">Tanggal Pengukuran</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="date" name="date_of_measurement" id="date_of_measurement" class="form-control measurement_date">
              </td>
            </tr>
            <tr>
              <td class="align-middle">Tanggal Selesai Pengukuran</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="date" name="end_date_measurement" id="end_date_measurement" class="form-control measurement_date" disabled>
                <small class="text-muted">Pilih tanggal mulai pengukuran terlebih dahulu</small>
              </td>
            </tr>
            <tr>
              <td class="align-middle">Equipment Pengukuran</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="equipment_id" class="form-control select2_equipment" style="width:100%;">
                  <option value="">- Pilih Equipment -</option>
                  @if(auth()->user()->company_id)
                    @forelse(auth()->user()->company->equipments as $equipment)
                      <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                    @empty
                    @endforelse
                  @endif
                  @if(auth()->user()->hasAnyRole(['supervisor', 'superadmin']))
                    <option value="new">+ Buat Equipment Baru</option>
                  @endif
                </select>
              </td>
            </tr>
            <tr id="new_equipment_name" style="display:none;">
              <td class="align-middle">Nama Equipment Baru</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="new_equipment_name" class="form-control" placeholder="Masukkan nama equipment baru">
              </td>
            </tr>
            <tr id="new_equipment_manufacturer" style="display:none;">
              <td class="align-middle">Manufacturer</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="new_equipment_manufacturer" class="form-control" placeholder="Masukkan manufacturer equipment">
              </td>
            </tr>
            <tr id="new_equipment_model" style="display:none;">
              <td class="align-middle">Model</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="new_equipment_model" class="form-control" placeholder="Masukkan model equipment">
              </td>
            </tr>
            <tr id="new_equipment_serial" style="display:none;">
              <td class="align-middle">Serial Number</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="new_equipment_serial" class="form-control" placeholder="Masukkan nomor seri equipment">
              </td>
            </tr>
            <tr id="new_equipment_tolerance" style="display:none;">
              <td class="align-middle">Toleransi</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <div class="input-group">
                  <input type="text" name="new_equipment_tolerance" class="form-control" placeholder="Masukkan toleransi equipment">
                  <div class="input-group-append">
                    <span class="input-group-text">mm</span>
                  </div>
                </div>
              </td>
            </tr>
            <tr id="new_equipment_probe_type" style="display:none;">
              <td class="align-middle">Tipe Probe</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="new_equipment_probe_type" class="form-control" placeholder="Masukkan tipe probe equipment">
              </td>
            </tr>
          </table>
        </div>
      </div>

    </form>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary create_form_btn">Create Form</button>
  </div>
</div>
</div>
</div>


@endsection


@section('css')


<link href="assets/modules/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
@endsection
@section('js')

<script src="assets/modules/select2/dist/js/select2.js"></script>

<script type="text/javascript">
$(document).ready(function(){

  // Event handler untuk tanggal pengukuran
  $('#date_of_measurement').on('change', function() {
    var startDate = $(this).val();
    var endDateInput = $('#end_date_measurement');
    
    if(startDate) {
      // Enable input tanggal selesai
      endDateInput.prop('disabled', false);
      
      // Set minimum date untuk tanggal selesai
      endDateInput.attr('min', startDate);
      
      // Reset value jika tanggal selesai lebih kecil dari tanggal mulai
      if(endDateInput.val() && endDateInput.val() < startDate) {
        endDateInput.val('');
      }
    } else {
      // Disable dan reset input tanggal selesai jika tanggal mulai kosong
      endDateInput.prop('disabled', true).val('');
    }
  });

  // Event handler untuk tanggal selesai (validasi tambahan)
  $('#end_date_measurement').on('change', function() {
    var endDate = $(this).val();
    var startDate = $('#date_of_measurement').val();
    
    if(endDate < startDate) {
      alert('Tanggal selesai tidak boleh lebih awal dari tanggal pengukuran');
      $(this).val('');
    }
  });

  // Initialize Select2 for surveyor selection
  $('.select2_surveyor').select2({
    minimumInputLength: 3,
    allowClear: true,
    dropdownParent: $('#create_report_modal .modal-content'),
    placeholder: '- Masukkan Nama/NUP -',
    ajax: {
      dataType: 'json',
      url: '<?=url('/user_list');?>',
      delay: 800,
      data: function(params) {
        return {
          search: params.term
        }
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
    }
  });

  // Ship type sudah di-hidden, tidak perlu Select2

  // Initialize Select2 for ship classification selection
  $('.select2_classification').select2({
    allowClear: true, 
    dropdownParent: $('#create_report_modal .modal-content'),
    placeholder: '- Masukkan Klasifikasi Kapal -'
  });

  // Initialize Select2 for equipment selection
  $('.select2_equipment').select2({
    allowClear: true, 
    dropdownParent: $('#create_report_modal .modal-content'),
    placeholder: '- Pilih Equipment -'
  });

  // Initialize Select2 for ship selection
  $('.select2_kapal').select2({
    minimumInputLength: 3,
    allowClear: true,
    dropdownParent: $('#create_report_modal .modal-content'),
    placeholder: '- Masukkan Nama Kapal -',
    ajax: {
      dataType: 'json',
      url: '<?=url('/ship_list');?>',
      delay: 800,
      data: function(params) {
        return {
          search: params.term
        }
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
    }
  }).on('select2:select', function (evt) {
    var no_reg = $(".select2_kapal option:selected").val();
    fetchShipData(no_reg);
  });

  function fetchShipData(no_reg) {
    $.ajax({
      url: "<?=url('/ship_list')?>",
      type: 'get',
      dataType: "json",
      data: { no_reg: no_reg },
      success: function(data) {
        populateShipData(data);
      },
      error: function(xhr, status, error) {
        console.error('Error fetching ship data:', error);
      },
    });
  }

  function populateShipData(data) {
    $('.nama_kapal').val(data.NMKPL);
    $('.no_reg_txt').html(data.NOREG);
    $('.no_reg').val(data.NOREG);
    $('.no_imo_txt').html(data.NOIMO);
    $('.no_imo').val(data.NOIMO);
    $('.ship_type_txt').html(data.TYSHP);
    $('.ship_type').val(data.TYSHP);
    
    // Ship type sudah di-hidden, tidak perlu selectShipType
    $('select[name="ship_classification"]').val(data.KLSF || 'BKI').trigger('change');
    
    $('.ship_weight_txt').html(data.BRT);
    $('.ship_weight').val(data.BRT);
    $('.ship_loa_txt').html(data.LOA);
    $('.ship_loa').val(data.LOA);
    $('.ship_breadth_txt').html(data.BMLD);
    $('.ship_breadth').val(data.BMLD);
    $('.ship_height_txt').html(data.HMLD);
    $('.ship_height').val(data.HMLD);
    $('.ship_owner_txt').html(data.PEMILIK);
    $('.ship_owner').val(data.PEMILIK);
    $('.owner_city_txt').html(data.KOTA);
    $('.owner_city').val(data.KOTA);
  }

  // Function selectShipType dihapus karena ship_type_id sudah di-hidden

  $('select[name="equipment_id"]').on('change', function() {
    var newEquipmentFields = $('#new_equipment_name, #new_equipment_manufacturer, #new_equipment_model, #new_equipment_serial, #new_equipment_tolerance, #new_equipment_probe_type');
    $(this).val() === 'new' ? newEquipmentFields.show() : newEquipmentFields.hide();
  });

  $('.add_report_btn').click(function(e){
    e.preventDefault();
    var category = $(this).attr('category');
    var category_id = $(this).attr('category_id');
    $('.category_id').val(category_id);
    $('.modal_category').html(category);
    $('#create_report_modal').modal('show');
  });

  $('.create_form_btn').click(function(e){
    e.preventDefault();
    if(validateForm()) {
      submitForm();
    }
  });

  function validateForm() {
    var requiredFields = [
      {name: 'nama_kapal', message: 'Please choose ship!'},
      {name: 'ship_classification', message: 'Please choose ship classification!'},
      {name: 'report_name', message: 'Please insert report name!'},
      {name: 'report_number', message: 'Please insert report number!'},
      {name: 'surveyor', message: 'Please choose surveyor!'},
      {name: 'location', message: 'Please insert measurement location!'},
      {name: 'date_of_measurement', message: 'Please insert measurement date!'},
      {name: 'equipment_id', message: 'Please choose equipment!'}
    ];

    for (var i = 0; i < requiredFields.length; i++) {
      var field = requiredFields[i];
      if (!$('[name="' + field.name + '"]').val()) {
        alert(field.message);
        $('[name="' + field.name + '"]').focus();
        return false;
      }
    }

    // Validasi tambahan untuk tanggal selesai pengukuran
    var startDate = $('#date_of_measurement').val();
    var endDate = $('#end_date_measurement').val();
    
    if (endDate && startDate && endDate < startDate) {
      alert('Tanggal selesai pengukuran tidak boleh lebih awal dari tanggal mulai pengukuran!');
      $('#end_date_measurement').focus();
      return false;
    }

    return true;
  }

  $('.add_report_btn').click(function(){
    // Reset dan sembunyikan field
    $('#kota').empty().prop('disabled', true).closest('.form-group').hide();
    $('#location').val('').closest('.form-group').hide();
    
    // Inisialisasi select2 setelah modal ditampilkan
    $('#create_report_modal').on('shown.bs.modal', function() {
      loadProvinsi();
    });
  });

  // Load data provinsi
  function loadProvinsi(){
    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
      .then(response => response.json())
      .then(provinces => {
        let provinsiSelect = $('#provinsi');
        provinsiSelect.empty().append('<option value="">Pilih Provinsi</option>');
        provinces.forEach(function(province) {
          provinsiSelect.append(new Option(province.name, province.id));
        });

        // Destroy existing select2 jika ada
        if(provinsiSelect.hasClass('select2-hidden-accessible')) {
          provinsiSelect.select2('destroy');
        }

        // Reinisialisasi select2
        provinsiSelect.select2({
          placeholder: 'Pilih Provinsi',
          width: '100%',
          dropdownParent: $('#create_report_modal') // Penting: set parent ke modal
        });
        
        provinsiSelect.closest('.form-group').show();
      });
  }

  // Event listener untuk perubahan provinsi 
  $('#provinsi').on('select2:select', function(){
    let provinsiId = $(this).val();
    let kotaSelect = $('#kota');
    
    // Reset dan sembunyikan field setelahnya
    $('#location').val('').closest('.form-group').hide();
    
    if(provinsiId) {
      fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinsiId}.json`)
        .then(response => response.json())
        .then(regencies => {
          kotaSelect.empty().append('<option value="">Pilih Kota/Kabupaten</option>');
          regencies.forEach(function(regency) {
            kotaSelect.append(new Option(regency.name, regency.id));
          });

          // Destroy existing select2 jika ada
          if(kotaSelect.hasClass('select2-hidden-accessible')) {
            kotaSelect.select2('destroy');
          }

          kotaSelect.prop('disabled', false).select2({
            placeholder: 'Pilih Kota/Kabupaten',
            width: '100%',
            dropdownParent: $('#create_report_modal') // Penting: set parent ke modal
          });
          
          kotaSelect.closest('.form-group').show();
        });
    } else {
      kotaSelect.empty()
        .append('<option value="">Pilih Kota/Kabupaten</option>')
        .prop('disabled', true)
        .closest('.form-group').hide();
    }
  });

  // Event listener untuk perubahan kota
  $('#kota').on('select2:select', function(){
    if($(this).val()) {
      $('#location').closest('.form-group').show();
    } else {
      $('#location').val('').closest('.form-group').hide();
    }
  });

  function submitForm() {
    var form_data = $('#create_report').serializeArray();
    // Ubah value provinsi dan kota menjadi nama sebelum submit
    var provinsiSelect = $('#provinsi');
    var kotaSelect = $('#kota');
    
    // Dapatkan nama provinsi dan kota yang dipilih
    var provinsiName = provinsiSelect.find('option:selected').text();
    var kotaName = kotaSelect.find('option:selected').text();
    
    // Cek apakah province dan city sudah ada di form_data
    var hasProvince = false;
    var hasCity = false;
    
    form_data.forEach(function(item) {
      if(item.name === 'province') {
        item.value = provinsiName;
        hasProvince = true;
      }
      if(item.name === 'city') {
        item.value = kotaName; 
        hasCity = true;
      }
    });

    // Tambahkan jika belum ada
    if(!hasProvince) {
      form_data.push({name: 'province', value: provinsiName});
    }
    if(!hasCity) {
      form_data.push({name: 'city', value: kotaName});
    }
    
    $.ajax({
      url: "<?=url('/report_data')?>",
      type: 'post',
      dataType: "json",
      data: form_data,
      success: function(data) {
        if(data.status=='success') {
          iziToast.success({position: "topRight", title: 'Success', message: data.message});
          $('#my_table').DataTable().ajax.reload();
          $('.ship_id, .report_name, .report_number').val('');
          $('#create_report_modal').modal('hide');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error submitting form:', error);
        alert('An error occurred while submitting the form. Please try again.');
      },
    });
  }
});

// Initialize DataTable
var dataTable = $('#my_table').DataTable({
  processing: true,
  serverSide: false,
  ajax: '<?=url('report_datatables');?>',
  columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'ship.name', name: 'ship.name'},
    { data: 'name', name: 'name' },
    { data: 'report_number', name: 'report_number' },
    { 
      data: 'date_of_measurement', 
      name: 'date_of_measurement',
      render: function(data) {
        if (!data || data === null || data === '') return '-';
        var momentDate = moment(data);
        return momentDate.isValid() ? momentDate.format('DD MMM YYYY') : '-';
      }
    },
    { 
      data: 'end_date_measurement', 
      name: 'end_date_measurement',
      render: function(data) {
        if (!data || data === null || data === '') return '-';
        var momentDate = moment(data);
        return momentDate.isValid() ? momentDate.format('DD MMM YYYY') : '-';
      }
    },
    { 
      data: 'submit_date', 
      name: 'submit_date',
      render: function(data) {
        if (!data || data === null || data === '') return '-';
        var momentDate = moment(data);
        return momentDate.isValid() ? momentDate.format('DD MMM YYYY') : '-';
      }
    },
    { 
      data: null, 
      name: 'status',
      render: function(data, type, row) {
        if (row.submit_date) {
          if (row.supervisor_verifikasi === 'approved' && row.surveyor_verifikasi === 'approved') {
            return '<span class="badge badge-success">REPORT SELESAI</span>';
          } else if (row.supervisor_verifikasi === 'approved' && row.surveyor_verifikasi === 'revised') {
            return '<span class="badge badge-warning text-black">BUTUH REVISI</span>';
          } else if (row.supervisor_verifikasi === 'approved') {
            return '<span class="badge badge-info">REVIEW SURVEYOR</span>';
          } else if (row.supervisor_verifikasi === 'revised') {
            return '<span class="badge badge-warning text-black">BUTUH REVISI</span>';
          } else {
            return '<span class="badge badge-primary">REVIEW SUPERVISOR</span>';
          }
        } else {
          return '<kbd class="bg-secondary">DRAFT</kbd>';
        }
      }
    },
    <?php if(auth()->user()->hasAnyRole(['superadmin', 'administrator'])){ ?>
      { data: 'user.name', name: 'user.name' },
    <?php } ?>
    { data: 'action', name: 'action', orderable: false, searchable: false},
  ],
  columnDefs: [
    { targets: 0, className: 'text-center', width: '20px' },
    { targets: [4, 5, 6], width: '120px', className: 'text-center' },
    { targets: 7, className: 'text-center', width: '100px' },
    { 
      targets: <?php echo (auth()->user()->hasAnyRole(['superadmin', 'administrator'])) ? 9 : 8; ?>,
      className: 'text-start',
      width: '150px'
    },
  ]
});

// Event handlers after DataTable is drawn
dataTable.on('draw.dt', function () {
  $('.b_detail, .b_edit, .b_delete').tooltip({show: {effect:"none", delay:0}});

  $(".b_detail").click(function(event){
    event.preventDefault();
    window.location.href = '{{URL::to('/') }}/report_detail/'+$(this).attr('id');
  });

  $(".b_print").click(function(event){
    event.preventDefault();
    window.open('{{URL::to('/') }}/report_print_tcpdf/'+$(this).attr('id'));
  });

  $(".b_delete").click(function(event){
    event.preventDefault();
    var form_count = $(this).attr('form_count');
    var confirmText = form_count > 0 
      ? `There ${form_count > 1 ? 'are' : 'is'} ${form_count} form${form_count > 1 ? 's' : ''} in this report.\nDo you really want to delete this form?`
      : 'Do you really want to delete this form?';
    
    if(confirm(confirmText)) {
      deleteReport($(this).attr('id'));
    }
  });
});

function deleteReport(report_id) {
  $.ajax({
    url: "<?=url('/report_data')?>",
    type: 'post',
    dataType: "json",
    data: {
      "action": 'delete',
      "report_id": report_id,
      "_token": '{{ csrf_token() }}',
    },
    success: function(data) {
      if(data.status=='success') {
        iziToast.success({position: "topRight", title: 'Success', message: data.message});
        dataTable.ajax.reload();
      }
    },
    error: function(xhr, status, error) {
      console.error('Error deleting report:', error);
      alert('An error occurred while deleting the report. Please try again.');
    },
  });
}
</script>

@endsection
