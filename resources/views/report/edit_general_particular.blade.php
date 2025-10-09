@extends('layouts.master')

@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Edit General Particular
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{url('report')}}">Report</a></li>
          <li class="breadcrumb-item active">Edit General Particular</li>
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
        <form action="{{ route('report.update_general_particular', base64_encode($report->id)) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama Report</label>
            <div class="col-sm-10">
              <input type="text" name="report_name" class="form-control" value="{{ $report->name }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nomor Report</label>
            <div class="col-sm-10">
              <input type="text" name="report_number" class="form-control" value="{{ $report->report_number }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Jenis Survey</label>
            <div class="col-sm-10">
              <input type="text" name="kind_of_survey" class="form-control" value="{{ $report->kind_of_survey }}" list="surveyOptions" required>
              <datalist id="surveyOptions">
                <option value="Special Survey">
                <option value="Docking Survey">
                <option value="Intermediate Survey">
              </datalist>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Lokasi Pengukuran</label>
            <div class="col-sm-10">
              <textarea name="location" class="form-control measurement_location" rows="2" placeholder="Masukkan Detail Lokasi Pengukuran (Nama Galangan/Pelabuhan, dll)" required>{{ $report->location }}</textarea>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Provinsi</label>
            <div class="col-sm-10">
              <input type="text" name="province" class="form-control" value="{{ $report->province }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Kota/Kabupaten</label>
            <div class="col-sm-10">
              <input type="text" name="city" class="form-control" value="{{ $report->city }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Pengukuran</label>
            <div class="col-sm-10">
              <input type="date" name="date_of_measurement" id="date_of_measurement" class="form-control measurement_date" value="{{ $report->date_of_measurement?->format('Y-m-d') }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Surveyor</label>
            <div class="col-sm-10">
              <select name="surveyor" class="form-control select2_surveyor" style="width:100%;" required>
                <option value="">- Masukkan Nama/NUP -</option>
                @if($report->surveyor)
                  <option value="{{ $report->surveyor_nup }}" selected>{{ $report->surveyor->nup . ' - ' . $report->surveyor->nama }}</option>
                @endif
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Selesai Pengukuran</label>
            <div class="col-sm-10">
              <input type="date" name="end_date_measurement" id="end_date_measurement" class="form-control measurement_date" value="{{ $report->end_date_measurement?->format('Y-m-d') }}" required>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-10 offset-sm-2">
              <button type="submit" class="btn btn-primary">Update</button>
              <a href="{{ route('report.report_detail', base64_encode($report->id)) }}" class="btn btn-secondary">Cancel</a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="/assets/modules/select2/dist/js/select2.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  // Initialize Select2 for surveyor selection with AJAX search
  $('.select2_surveyor').select2({
    minimumInputLength: 3,
    allowClear: true,
    placeholder: '- Masukkan Nama/NUP -',
    ajax: {
      dataType: 'json',
      url: '<?=url('/user_list');?>',
      delay: 800,
      data: function(params) {
        return {
          search: params.term,
          role: 'surveyor' // Tambahkan parameter untuk filter role surveyor
        }
      },
      processResults: function (data) {
        return {
          results: $.map(data, function(item) {
            return {
              id: item.id,
              text: item.text || `${item.name} (${item.nup || 'No NUP'})` // Format tampilan nama surveyor
            }
          })
        };
      },
      cache: true
    }
  });

  // Jika ada surveyor yang sudah terpilih sebelumnya, set data awal
  @if($report->surveyor_id && $report->surveyor)
    var initialSurveyor = {
      id: '{{ $report->surveyor_id }}',
      text: '{{ $report->surveyor }}'
    };
    
    var initialOption = new Option(initialSurveyor.text, initialSurveyor.id, true, true);
    $('.select2_surveyor').append(initialOption).trigger('change');
  @endif

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
});
</script>
@endsection

@section('css')
<link href="/assets/modules/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
@endsection