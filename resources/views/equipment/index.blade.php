@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Daftar Equipment
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Equipment</li>
        </ol>
      </div>

    </div>
  </div>
</div>
<!-- end page title -->


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->hasAnyRole(['superadmin', 'supervisor']))
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                        Tambah Equipment
                    </button>
                </div>
                @endif
                <table id="equipment-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Manufaktur</th>
                            <th>Model</th>
                            <th>Serial</th>
                            <th>Toleransi</th>
                            <th>Tipe Probe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipments as $equipment)
                        <tr>
                            <td>{{ $equipment->name }}</td>
                            <td>{{ $equipment->manufactur }}</td>
                            <td>{{ $equipment->model }}</td>
                            <td>{{ $equipment->serial }}</td>
                            <td>{{ $equipment->tolerancy }}</td>
                            <td>{{ $equipment->probe_type }}</td>
                            <td>
                                <a href="{{ route('equipment.show', $equipment->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                @if(auth()->user()->hasAnyRole(['superadmin', 'supervisor']))
                                <button class="btn btn-danger btn-sm delete-equipment" data-id="{{ $equipment->id }}">Hapus</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Equipment -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEquipmentModalLabel">Tambah Equipment Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">  
        <form id="addEquipmentForm" enctype="multipart/form-data" action="{{ route('equipment.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <h6 class="mb-3">Data Equipment</h6>
              <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="manufacturer" class="form-label">Manufaktur</label>
                <input type="text" class="form-control" id="manufacturer" name="manufacturer" required>
              </div>
              <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" required>
              </div>
              <div class="mb-3">
                <label for="serial" class="form-label">Serial</label>
                <input type="text" class="form-control" id="serial" name="serial" required>
              </div>
              <div class="mb-3">
                <label for="tolerance" class="form-label">Toleransi</label>
                <div class="input-group">
                  <span class="input-group-text">±</span>
                  <input type="text" class="form-control" id="tolerance" name="tolerance" required>
                  <span class="input-group-text">mm</span>
                </div>
              </div>
              <div class="mb-3">
                <label for="probe_type" class="form-label">Tipe Probe</label>
                <input type="text" class="form-control" id="probe_type" name="probe_type" required>
              </div>
            </div>
            <div class="col-md-6">
              <h6 class="mb-3">Data Sertifikat</h6>
              <div class="mb-3">
                <label for="certificate_number" class="form-label">Nomor Sertifikat</label>
                <input type="text" class="form-control" id="certificate_number" name="certificate_number" required>
              </div>
              <div class="mb-3">
                <label for="certificate_date" class="form-label">Tanggal Sertifikat</label>
                <input type="date" class="form-control" id="certificate_date" name="certificate_date" required>
              </div>
              <div class="mb-3">
                <label for="calibration_certificate" class="form-label">Sertifikat Kalibrasi</label>
                <input type="file" class="filepond" name="calibration_certificate" accept="application/pdf" required>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary" id="saveNewEquipment">Simpan</button>
      </div>
    </form>
    </div>
  </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- FilePond core JS -->
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

<!-- FilePond Plugin JS -->
<!-- Image Preview Plugin -->
<script src="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.js"></script>

<!-- File Type Validation Plugin -->
<script src="https://unpkg.com/filepond-plugin-file-validate-type@^1/dist/filepond-plugin-file-validate-type.js"></script>

<!-- File Size Validation Plugin -->
<script src="https://unpkg.com/filepond-plugin-file-validate-size@^2/dist/filepond-plugin-file-validate-size.js"></script>

<script>
// Setup axios with CSRF token
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

$(document).ready(function() {
    $('#equipment-table').DataTable({
      responsive: true,
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
        zeroRecords: `<div class="text-center">
                          <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                          </lord-icon>
                          <h5 class="mt-2">Maaf! Tidak Ada Data Equipment Ditemukan</h5>
                          <p class="text-muted mb-0">Kami tidak menemukan
                              data equipment untuk pencarian Anda.</p>
                      </div>`,
      },
      columnDefs: [
        { orderable: false, targets: -1 }
      ]
    });

    $('.delete-equipment').click(function() {
        var equipmentId = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`{{ route('equipment.delete', '') }}/${equipmentId}`)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Swal.fire(
                                'Terhapus!',
                                response.data.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.log('Error response:', error.response);
                        let errorMessage = 'Terjadi kesalahan saat menghapus equipment.';
                        
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        
                        Swal.fire(
                            'Error!',
                            errorMessage,
                            'error'
                        );
                    });
            }
        });
    });

    // Mendaftarkan plugin FilePond
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType
    );

    // Inisialisasi FilePond untuk elemen input dengan class filepond
    const pond = FilePond.create(document.querySelector('.filepond'), {
        storeAsFile: true,
        allowMultiple: false,
        allowReplace: true,
        labelIdle: 'Seret & Lepas file PDF Anda atau <span class="filepond--label-action"> Jelajahi </span>',
        maxFileSize: '20MB',
        acceptedFileTypes: ['application/pdf'],
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            // Do custom type detection here and return with promise
            resolve(type);
        })
    });

    // Menambahkan event listener ke formulir
    $('#addEquipmentForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        // Get the file from FilePond
        const pondFiles = pond.getFiles();
        if (pondFiles.length > 0) {
            formData.append('calibration_certificate', pondFiles[0].file);
        }
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire(
                        'Berhasil!',
                        'Equipment baru telah ditambahkan.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                    
                    // Reset FilePond
                    pond.removeFiles();
                    
                } else {
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan: ' + response.message,
                        'error'
                    );
                }
            },
            error: function(xhr, status, error) {
                Swal.fire(
                    'Error!',
                    'Terjadi kesalahan saat menambahkan equipment.',
                    'error'
                );
                console.log('Status:', status);
                console.log('Error:', error);
                console.log('Response:', xhr.responseText);
            }
        });
    });
});
</script>
@endsection