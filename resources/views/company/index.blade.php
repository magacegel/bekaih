@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<link href="https://unpkg.com/filepond@4.30.4/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview@4.6.11/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-file-validate-type@1.2.8/dist/filepond-plugin-file-validate-type.css" rel="stylesheet">
@endsection

@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Manajemen Perusahaan
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Manajemen Perusahaan</li>
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
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="card-title">Daftar Perusahaan</h5>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
            <i class="fas fa-plus me-2"></i>Tambah Perusahaan
          </button>
        </div>
        
        <div class="table-responsive">
          <table id="companyTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>Alamat</th>
                <th>Sertifikat Penyedia Jasa</th>
                <th>Tanggal Persetujuan</th>
                <th>Tanggal Kadaluarsa</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Perusahaan -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCompanyModalLabel">Tambah Perusahaan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCompanyForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="branch" class="form-label">Cabang</label>
                <input type="text" class="form-control" id="branch" name="branch">
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="city" class="form-label">Kota</label>
                <input type="text" class="form-control" id="city" name="city" required>
              </div>
              <div class="mb-3">
                <label for="zip_code" class="form-label">Kode Pos</label>
                <input type="text" class="form-control" id="zip_code" name="zip_code" required>
              </div>
              <div class="mb-3">
                <label for="logo" class="form-label">Logo Perusahaan</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="certificate_number" class="form-label">Nomor Sertifikat</label>
                <input type="text" class="form-control" id="certificate_number" name="certificate_number">
              </div>
              <div class="mb-3">
                <label for="approval_number" class="form-label">Nomor Persetujuan</label>
                <input type="text" class="form-control" id="approval_number" name="approval_number">
              </div>
              <div class="mb-3">
                <label for="approval_date" class="form-label">Tanggal Persetujuan</label>
                <input type="date" class="form-control" id="approval_date" name="approval_date">
              </div>
              <div class="mb-3">
                <label for="expired_date" class="form-label">Tanggal Kadaluarsa</label>
                <input type="date" class="form-control" id="expired_date" name="expired_date">
              </div>
              <div class="mb-3">
                <label for="certificate_file" class="form-label">File Sertifikat</label>
                <input type="file" class="filepond" id="certificate_file" name="certificate_file" data-allow-reorder="true" data-max-file-size="3MB" data-max-files="3" accept="application/pdf">
              </div>
            </div>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Company -->
<div class="modal fade" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCompanyModalLabel">Edit Perusahaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCompanyForm" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" id="edit_company_id" name="id">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_name" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" id="edit_name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="edit_branch" class="form-label">Cabang</label>
                <input type="text" class="form-control" id="edit_branch" name="branch" required>
              </div>
              <div class="mb-3">
                <label for="edit_address" class="form-label">Alamat</label>
                <textarea class="form-control" id="edit_address" name="address" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="edit_city" class="form-label">Kota</label>
                <input type="text" class="form-control" id="edit_city" name="city" required>
              </div>
              <div class="mb-3">
                <label for="edit_zip_code" class="form-label">Kode Pos</label>
                <input type="text" class="form-control" id="edit_zip_code" name="zip_code" required>
              </div>
              <div class="mb-3">
                <label for="edit_logo" class="form-label">Logo Perusahaan</label>
                <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                <div id="logo_preview" class="mt-2"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_certificate_number" class="form-label">Nomor Sertifikat</label>
                <input type="text" class="form-control" id="edit_certificate_number" name="certificate_number">
              </div>
              <div class="mb-3">
                <label for="edit_approval_number" class="form-label">Nomor Persetujuan</label>
                <input type="text" class="form-control" id="edit_approval_number" name="approval_number">
              </div>
              <div class="mb-3">
                <label for="edit_approval_date" class="form-label">Tanggal Persetujuan</label>
                <input type="date" class="form-control" id="edit_approval_date" name="approval_date">
              </div>
              <div class="mb-3">
                <label for="edit_expired_date" class="form-label">Tanggal Kadaluarsa</label>
                <input type="date" class="form-control" id="edit_expired_date" name="expired_date">
              </div>
              <div class="mb-3">
                <label for="edit_certificate_file" class="form-label">File Sertifikat</label>
                <input type="file" class="filepond" id="edit_certificate_file" name="certificate_file" data-allow-reorder="true" data-max-file-size="3MB" data-max-files="3" accept="application/pdf">
                <div id="certificate_preview" class="mt-2"></div>
              </div>
            </div>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview@4.6.11/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type@1.2.8/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond@4.30.4/dist/filepond.js"></script>

<script>
  $(document).ready(function() {
    var companyTable = $('#companyTable').DataTable({
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: {
            url: "{{ route('company.data') }}",
            error: function (xhr, error, thrown) {
                console.error('Error:', error);
                iziToast.error({
                    title: 'Error',
                    message: 'Terjadi kesalahan saat memuat data. Silakan coba lagi.',
                    position: 'topRight'
                });
            }
        },
        columns: [
            {data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            {data: 'name', name: 'name'},
            {data: null, name: 'address', render: function(data, type, row) {
                return `${row.address}, ${row.city} ${row.zip_code}`;
            }},
            {data: null, name: 'certificate', render: function(data, type, row) {
                return `${row.certificate_number} / ${row.approval_number}`;
            }},
            {data: 'approval_date', name: 'approval_date'},
            {data: 'expired_date', name: 'expired_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                // return `<a href="{{ url('company/show') }}/${row.id}" class="btn btn-sm btn-info">Lihat</a>
                //         <button class="btn btn-sm btn-warning edit-company" data-id="${row.id}">Edit</button>
                //         <button class="btn btn-sm btn-danger delete-company" data-id="${row.id}">Hapus</button>`;
                return `<a href="{{ url('company/show') }}/${row.id}" class="btn btn-sm btn-info">Lihat</a>
                        <button class="btn btn-sm btn-danger delete-company" data-id="${row.id}">Hapus</button>`;
            }}
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
            zeroRecords: `<div class="text-center">
                              <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                              </lord-icon>
                              <h5 class="mt-2">Maaf! Tidak Ada Data Perusahaan Ditemukan</h5>
                              <p class="text-muted mb-0">Kami tidak menemukan
                                  data perusahaan untuk pencarian Anda.</p>
                          </div>`,
        },
        initComplete: function(settings, json) {
            if (json.error) {
                console.error('DataTables error:', json.error);
                iziToast.error({
                    title: 'Error',
                    message: 'Terjadi kesalahan saat memuat data. Silakan coba lagi.',
                    position: 'topRight'
                });
            }
        }
    });

    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true,
      'albumLabel': "Gambar %1 dari %2"
    });

    // Register the plugins
    FilePond.registerPlugin(
      FilePondPluginImagePreview,
      FilePondPluginFileValidateType
    );

    // Create a FilePond instance
    const pond = FilePond.create(document.querySelector('.filepond'), {
      storeAsFile: true,
      labelIdle: 'Seret & Lepas file PDF Anda atau <span class="filepond--label-action"> Jelajahi </span>',
      maxFileSize: '20MB',
      acceptedFileTypes: ['application/pdf'],
      fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
        // Do custom type detection here and return with promise
        resolve(type);
      })
    });

    $('#addCompanyForm').submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      
      $.ajax({
        url: "{{ route('company.store') }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if(response.status === 'success') {
            $('#addCompanyModal').modal('hide');
            iziToast.success({
              title: 'Sukses',
              message: response.message,
              position: 'topRight'
            });
            companyTable.ajax.reload();
          } else {
            iziToast.error({
              title: 'Error',
              message: 'Terjadi kesalahan: ' + response.message,
              position: 'topRight'
            });
          }
        },
        error: function(xhr, status, error) {
          iziToast.error({
            title: 'Error',
            message: 'Terjadi kesalahan saat mengirim permintaan.',
            position: 'topRight'
          });
          console.log(xhr.responseText);
        }
      });
    });

    // Fungsi untuk melihat detail perusahaan
    $(document).on('click', '.view-company', function() {
      var companyId = $(this).data('id');
      $.ajax({
        url: "{{ url('company') }}/" + companyId,
        type: 'GET',
        success: function(response) {
          // Tampilkan detail perusahaan dalam modal
          // Implementasikan sesuai kebutuhan
        },
        error: function(xhr, status, error) {
          iziToast.error({
            title: 'Error',
            message: 'Terjadi kesalahan saat mengambil data perusahaan.',
            position: 'topRight'
          });
        }
      });
    });

    // Fungsi untuk mengedit perusahaan
    $(document).on('click', '.edit-company', function() {
      var companyId = $(this).data('id');
      // Implementasikan logika untuk mengambil data perusahaan dan menampilkan form edit
    });

    // Fungsi untuk menghapus perusahaan
    $(document).on('click', '.delete-company', function() {
      var companyId = $(this).data('id');
      if (confirm('Apakah Anda yakin ingin menghapus perusahaan ini?')) {
        $.ajax({
          url: "{{ url('company') }}/" + companyId,
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if(response.status === 'success') {
              iziToast.success({
                title: 'Sukses',
                message: response.message,
                position: 'topRight'
              });
              companyTable.ajax.reload();
            } else {
              iziToast.error({
                title: 'Error',
                message: 'Terjadi kesalahan: ' + response.message,
                position: 'topRight'
              });
            }
          },
          error: function(xhr, status, error) {
            iziToast.error({
              title: 'Error',
              message: 'Terjadi kesalahan saat menghapus perusahaan.',
              position: 'topRight'
            });
          }
        });
      }
    });
  });
</script>
@endsection
