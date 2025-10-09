@extends('layouts.master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css">
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<style>
    .invalid-feedback {
        display: none;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
</style>
@endsection

@section('content')
@php
    $canEdit = auth()->user()->hasAnyRole(['superadmin', 'administrator']) ||
               (auth()->user()->hasRole('supervisor') && auth()->user()->company_id == $equipment->company_id);
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Equipment</h4>
            </div>
            <div class="card-body">
                <form id="equipmentForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ $equipment->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $equipment->name }}" {{ $canEdit ? '' : 'readonly' }}>
                            </div>
                            <div class="mb-3">
                                <label for="manufacturer" class="form-label">Manufaktur:</label>
                                <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="{{ $equipment->manufactur }}" {{ $canEdit ? '' : 'readonly' }}>
                            </div>
                            <div class="mb-3">
                                <label for="model" class="form-label">Model:</label>
                                <input type="text" class="form-control" id="model" name="model" value="{{ $equipment->model }}" {{ $canEdit ? '' : 'readonly' }}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="serial" class="form-label">Serial:</label>
                                <input type="text" class="form-control" id="serial" name="serial" value="{{ $equipment->serial }}" {{ $canEdit ? '' : 'readonly' }}>
                            </div>
                            <div class="mb-3">
                                <label for="tolerance" class="form-label">Toleransi (mm):</label>
                                <input type="number" class="form-control" id="tolerance" name="tolerance" value="{{ $equipment->tolerancy }}" step="1" min="0" {{ $canEdit ? '' : 'readonly' }}>
                                <div class="invalid-feedback" id="tolerance-error">
                                    Toleransi harus berupa bilangan bulat (tidak boleh desimal).
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="probe_type" class="form-label">Tipe Probe:</label>
                                <input type="text" class="form-control" id="probe_type" name="probe_type" value="{{ $equipment->probe_type }}" {{ $canEdit ? '' : 'readonly' }}>
                            </div>
                        </div>
                    </div>
                    @if($canEdit)
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@php
    $activeCertificate = $equipment->certifications->where('active', true)->first();
@endphp

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $activeCertificate ? 'Sertifikat Kalibrasi' : 'Tambah Sertifikat Kalibrasi' }}</h4>
            </div>
            <div class="card-body">
                @if($canEdit)
                    <form id="certificateForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                        @if($activeCertificate)
                            <input type="hidden" name="certificate_id" value="{{ $activeCertificate->id }}">
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="certificate_date" class="form-label">Tanggal Kedaluwarsa:</label>
                                    <input type="date" class="form-control" id="certificate_date" name="certificate_date" 
                                           value="{{ $activeCertificate ? $activeCertificate->certificate_date : '' }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="certificate_number" class="form-label">Nomor Sertifikat:</label>
                                    <input type="text" class="form-control" id="certificate_number" name="certificate_number" 
                                           value="{{ $activeCertificate ? $activeCertificate->certificate_number : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="certificate_file" class="form-label">
                                        File Sertifikat {{ $activeCertificate ? '(Opsional - kosongkan jika tidak ingin mengubah file)' : '' }}:
                                    </label>
                                    <input type="file" class="filepond" id="certificate_file" name="certificate_file" 
                                           data-max-file-size="3MB" accept="application/pdf" {{ $activeCertificate ? '' : 'required' }}>
                                </div>
                                @if($activeCertificate && $activeCertificate->url)
                                    <div class="mb-3">
                                        <label class="form-label">File Sertifikat Saat Ini:</label>
                                        <div class="d-flex gap-2">
                                            <a href="{{ Storage::disk('digitalocean')->temporaryUrl($activeCertificate->url, now()->addMinutes(10)) }}" 
                                               target="_blank" class="btn btn-info">
                                               <i class="fas fa-eye me-1"></i>Lihat Sertifikat
                                            </a>
                                            <a href="{{ Storage::disk('digitalocean')->temporaryUrl($activeCertificate->url, now()->addMinutes(10)) }}" 
                                               download class="btn btn-secondary">
                                               <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            {{ $activeCertificate ? 'Update Sertifikat' : 'Simpan Sertifikat' }}
                        </button>
                    </form>
                @else
                    @if($activeCertificate)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Tanggal Kedaluwarsa:</strong></label>
                                    <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($activeCertificate->certificate_date)->locale('id')->isoFormat('DD MMMM YYYY') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Nomor Sertifikat:</strong></label>
                                    <p class="form-control-plaintext">{{ $activeCertificate->certificate_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if($activeCertificate->url)
                                    <div class="mb-3">
                                        <label class="form-label"><strong>File Sertifikat:</strong></label>
                                        <div class="d-flex gap-2">
                                            <a href="{{ Storage::disk('digitalocean')->temporaryUrl($activeCertificate->url, now()->addMinutes(10)) }}" 
                                               target="_blank" class="btn btn-info">
                                               <i class="fas fa-eye me-1"></i>Lihat Sertifikat
                                            </a>
                                            <a href="{{ Storage::disk('digitalocean')->temporaryUrl($activeCertificate->url, now()->addMinutes(10)) }}" 
                                               download class="btn btn-secondary">
                                               <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label class="form-label"><strong>File Sertifikat:</strong></label>
                                        <p class="text-muted">Tidak ada file sertifikat</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-muted">Belum ada sertifikat kalibrasi untuk equipment ini.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script>
    $(document).ready(function() {
        // Register FilePond plugins
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType
        );

        // Initialize FilePond - hanya jika element ada
        const filepondElement = document.querySelector('input[type="file"].filepond');
        let pond = null;
        if (filepondElement) {
            pond = FilePond.create(filepondElement, {
                acceptedFileTypes: ['application/pdf'],
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    // Do custom type detection here and return with promise
                    resolve(type);
                })
            });
        }

        @if($canEdit)
        // Validasi toleransi - tidak boleh desimal
        $('#tolerance').on('input', function() {
            var value = $(this).val();
            var $input = $(this);
            var $errorDiv = $('#tolerance-error');
            
            // Cek apakah nilai mengandung desimal
            if (value && (value.includes('.') || value.includes(','))) {
                $input.addClass('is-invalid');
                $errorDiv.show();
            } else {
                $input.removeClass('is-invalid');
                $errorDiv.hide();
            }
        });

        $('#equipmentForm').submit(function(e) {
            e.preventDefault();
            
            // Validasi toleransi sebelum submit
            var tolerance = $('#tolerance').val();
            if (tolerance && (tolerance.includes('.') || tolerance.includes(','))) {
                Swal.fire('Error', 'Toleransi harus berupa bilangan bulat (tidak boleh desimal)', 'error');
                return;
            }

            var formData = new FormData(this);

            // Tampilkan loading
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Sedang memproses data equipment',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/equipment/save/{{ $equipment->id }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.close();
                    if(response.status === 'success') {
                        Swal.fire('Sukses', response.message || 'Data equipment berhasil diperbarui', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Terjadi kesalahan saat memperbarui data', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.log('XHR Status:', xhr.status);
                    console.log('XHR Response:', xhr.responseText);
                    console.log('Error:', error);
                    
                    let errorMessage = 'Terjadi kesalahan saat mengirim permintaan.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Handle validation errors
                        let errors = [];
                        for (let field in xhr.responseJSON.errors) {
                            errors.push(xhr.responseJSON.errors[field].join(', '));
                        }
                        errorMessage = 'Kesalahan validasi: ' + errors.join('; ');
                    } else if (xhr.status === 404) {
                        errorMessage = 'Equipment tidak ditemukan.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Anda tidak memiliki izin untuk mengubah data ini.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
                    }
                    
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        });

        $('#certificateForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var hasExistingCertificate = $('#certificateForm input[name="certificate_id"]').length > 0;
            var certificateId = hasExistingCertificate ? $('#certificateForm input[name="certificate_id"]').val() : null;

            // Periksa apakah instance FilePond ada dan memiliki file
            if (pond && pond.getFiles().length > 0) {
                const file = pond.getFiles()[0].file;
                formData.append('certificate_file', file);
            } else if (!hasExistingCertificate) {
                // Jika tidak ada sertifikat dan tidak ada file yang diupload
                Swal.fire('Error', 'File sertifikat wajib diisi', 'error');
                return;
            }

            // Tampilkan loading
            Swal.fire({
                title: 'Menyimpan...',
                text: hasExistingCertificate ? 'Sedang memperbarui sertifikat' : 'Sedang menyimpan sertifikat',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            var url = hasExistingCertificate ? 
                '/equipment/certificate/update/' + certificateId : 
                '/equipment/certificate/add';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.close();
                    if(response.status === 'success') {
                        var message = hasExistingCertificate ? 
                            'Sertifikat berhasil diperbarui' : 
                            'Sertifikat berhasil ditambahkan';
                        Swal.fire('Sukses', message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Terjadi kesalahan saat memproses sertifikat', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.log('XHR Status:', xhr.status);
                    console.log('XHR Response:', xhr.responseText);
                    console.log('Error:', error);
                    
                    let errorMessage = 'Terjadi kesalahan saat mengirim permintaan.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Handle validation errors
                        let errors = [];
                        for (let field in xhr.responseJSON.errors) {
                            errors.push(xhr.responseJSON.errors[field].join(', '));
                        }
                        errorMessage = 'Kesalahan validasi: ' + errors.join('; ');
                    }
                    
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        });
        @endif
    });
</script>
@endsection
