@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<!-- FilePond CSS -->
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
@endsection

@section('content')
@php
    $canEdit = auth()->user()->hasAnyRole(['superadmin', 'administrator']) ||
               (auth()->user()->hasRole('supervisor') && auth()->user()->company_id == $company->id);
    $readonlyform = !$canEdit;
@endphp
    @if(session('warning'))
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="ri-error-warning-line me-2"></i>
                        {{ session('warning') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Perusahaan</h5>
                    <div class="mt-3">
                        <p><strong>Nama Perusahaan:</strong><br>
                            {{ strtoupper($company->name) }} {{ strtoupper($company->branch ?? "") }}
                        </p>
                        <form id="companyForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="address" class="form-label"><strong>Alamat:</strong></label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $company->address }}" {{ $readonlyform ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label"><strong>Kota:</strong></label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $company->city ?? '' }}" {{ $readonlyform ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label for="zip_code" class="form-label"><strong>Kode Pos:</strong></label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ $company->zip_code ?? '' }}" {{ $readonlyform ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label"><strong>Logo Perusahaan:</strong></label>
                                <div class="mb-2">
                                    @if($company->logo)
                                        @php
                                            $doConfig = config('filesystems.disks.digitalocean');
                                            $baseUrl = $doConfig['url'] ?? $doConfig['bucket_endpoint'] ?? '';
                                            $logoUrl = rtrim($baseUrl, '/') . '/' . ltrim($company->logo, '/');
                                        @endphp
                                        <img id="logoPreview" src="{{ $logoUrl }}" alt="Logo Perusahaan" class="img-thumbnail" style="max-width: 200px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <p style="display: none;">Logo tidak dapat dimuat</p>
                                    @else
                                        <p>Belum ada logo</p>
                                    @endif
                                </div>
                                @if(!$readonlyform)
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <small class="form-text text-muted">Unggah gambar baru untuk mengubah logo</small>
                                @endif
                            </div>
                            @if(!$readonlyform)
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Sertifikat</h5>
                    <form id="certificateForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="certificate_number" class="form-label"><strong>Nomor Sertifikat:</strong></label>
                            <input type="text" class="form-control" id="certificate_number" name="certificate_number" value="{{ $company->activeCertificate->number ?? '' }}" {{ $readonlyform ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="approval_number" class="form-label"><strong>Nomor Persetujuan:</strong></label>
                            <input type="text" class="form-control" id="approval_number" name="approval_number" value="{{ $company->activeCertificate->approval_number ?? '' }}" {{ $readonlyform ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="approval_date" class="form-label"><strong>Tanggal Persetujuan:</strong></label>
                            <input type="date" class="form-control" id="approval_date" name="approval_date" value="{{ $company->activeCertificate && $company->activeCertificate->approval_date ? $company->activeCertificate->approval_date->format('Y-m-d') : '' }}" {{ $readonlyform ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="expired_date" class="form-label"><strong>Tanggal Kadaluarsa:</strong></label>
                            <input type="date" class="form-control" id="expired_date" name="expired_date" value="{{ $company->activeCertificate && $company->activeCertificate->expired_date ? $company->activeCertificate->expired_date->format('Y-m-d') : '' }}" {{ $readonlyform ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="certificate_file" class="form-label"><strong>File Sertifikat:</strong></label>
                            <div id="certificatePreview" class="mb-2">
                                @if($company->activeCertificate && $company->activeCertificate->certificate_file)
                                    <a href="{{ $company->activeCertificate->certificate_url }}" target="_blank" class="btn btn-sm btn-primary">Lihat Sertifikat</a>
                                    @if(!$company->activeCertificate->certificateFileExists())
                                        <div class="alert alert-warning mt-2">
                                            <small>⚠️ File sertifikat mungkin tidak tersedia di storage</small>
                                        </div>
                                    @endif
                                @else
                                    <p>Belum ada file sertifikat</p>
                                @endif
                            </div>
                            @if(!$readonlyform)
                                <input type="file" class="filepond" name="certificate_file" accept="application/pdf">
                                <small class="form-text text-muted">Unggah file PDF baru untuk menambah atau mengubah sertifikat</small>
                            @endif
                        </div>
                        @if(!$readonlyform)
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="companyTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="inspector-tab" data-bs-toggle="tab" data-bs-target="#inspector" type="button" role="tab">Data Inspektor</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="equipment-tab" data-bs-toggle="tab" data-bs-target="#equipment" type="button" role="tab">Data Equipment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="certificates-tab" data-bs-toggle="tab" data-bs-target="#certificates" type="button" role="tab">Riwayat Sertifikat</button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="companyTabContent">
                        <div class="tab-pane fade show active" id="inspector" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Data Inspektor</h5>
                                @if(auth()->user()->hasAnyRole(['superadmin', 'administrator']))
                                <button type="button" class="btn btn-primary" id="btnAddInspector">
                                    <i class="fas fa-plus me-2"></i>Tambah Inspektor
                                </button>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>No. Telepon</th>
                                            <th>Jabatan</th>
                                            <th>No. Sertifikat</th>
                                            <th>Masa Berlaku</th>
                                            <th>File Sertifikat</th>
                                            @if(auth()->user()->hasRole('supervisor'))
                                            <th>Aksi</th>
                                            @endif
                                            @if(auth()->user()->hasAnyRole(['superadmin', 'administrator']))
                                            <th>Kelola</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($company->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->username }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>
                                                    @if($user->getRoleNames()->count() > 0)
                                                        {{ ucfirst($user->getRoleNames()->first()) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                {{ $user->latestQualification ? $user->latestQualification->qualification : 'N/A' }} | {{ $user->latestQualification ? $user->latestQualification->certificate_number : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $user->latestQualification ? \Carbon\Carbon::parse($user->latestQualification->expired_date)->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if($user->latestQualification && $user->latestQualification->certificate_file)
                                                        @php
                                                            $doConfig = config('filesystems.disks.digitalocean');
                                                            $baseUrl = $doConfig['url'] ?? $doConfig['bucket_endpoint'] ?? '';
                                                            $userCertUrl = rtrim($baseUrl, '/') . '/' . ltrim($user->latestQualification->certificate_file, '/');
                                                        @endphp
                                                        <a href="{{ $userCertUrl }}" target="_blank" class="btn btn-sm btn-primary">Lihat Sertifikat</a>
                                                    @else
                                                        <span class="text-muted">Tidak ada file</span>
                                                    @endif
                                                </td>
                                                @if(auth()->user()->hasRole('supervisor'))
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info btn-upload-certificate"
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}">
                                                        <i class="fas fa-upload"></i> Upload Sertifikat Terbaru
                                                    </button>
                                                </td>
                                                @endif
                                                @if(auth()->user()->hasAnyRole(['superadmin', 'administrator']))
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-warning btn-edit-inspector"
                                                                data-user-id="{{ $user->id }}"
                                                                data-user-name="{{ $user->name }}"
                                                                data-user-username="{{ $user->username }}"
                                                                data-user-email="{{ $user->email }}"
                                                                data-user-phone="{{ $user->phone }}"
                                                                data-user-ktp="{{ $user->ktp }}"
                                                                data-user-role="{{ $user->getRoleNames()->first() ?? '' }}">
                                                            <i class="fas fa-user-edit"></i> User
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-info btn-manage-competency"
                                                                data-user-id="{{ $user->id }}"
                                                                data-user-name="{{ $user->name }}">
                                                            <i class="fas fa-certificate"></i> Kompetensi
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-inspector"
                                                                data-user-id="{{ $user->id }}"
                                                                data-user-name="{{ $user->name }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="equipment" role="tabpanel">
                            <h5>Data Equipment</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Manufaktur</th>
                                            <th>Model</th>
                                            <th>Serial</th>
                                            <th>Toleransi</th>
                                            <th>Tipe Probe</th>
                                            <th>Sertifikat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($company->equipments as $equipment)
                                            <tr>
                                                <td>{{ $equipment->name }}</td>
                                                <td>{{ $equipment->manufactur }}</td>
                                                <td>{{ $equipment->model }}</td>
                                                <td>{{ $equipment->serial }}</td>
                                                <td>{{ $equipment->tolerancy }}</td>
                                                <td>{{ $equipment->probe_type }}</td>
                                                <td>
                                                    @if($equipment->activeCertifications->count() > 0)
                                                        @foreach($equipment->activeCertifications as $cert)
                                                            @php
                                                                $doConfig = config('filesystems.disks.digitalocean');
                                                                $baseUrl = $doConfig['url'] ?? $doConfig['bucket_endpoint'] ?? '';
                                                                $equipCertUrl = rtrim($baseUrl, '/') . '/' . ltrim($cert->url, '/');
                                                            @endphp
                                                            <a href="{{ $equipCertUrl }}" target="_blank" class="btn btn-sm btn-primary mb-1">Lihat Sertifikat</a>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Tidak ada sertifikat aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="certificates" role="tabpanel">
                            <h5>Riwayat Sertifikat Perusahaan</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nomor Sertifikat</th>
                                            <th>Nomor Persetujuan</th>
                                            <th>Tanggal Persetujuan</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>File Sertifikat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="certificateHistoryTable">
                                        @foreach($company->certificates->sortByDesc('created_at') as $certificate)
                                            @if($certificate)
                                                <tr>
                                                    <td>{{ $certificate->number }}</td>
                                                    <td>{{ $certificate->approval_number }}</td>
                                                    <td>{{ $certificate->approval_date ? \Carbon\Carbon::parse($certificate->approval_date)->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>{{ $certificate->expired_date ? \Carbon\Carbon::parse($certificate->expired_date)->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>
                                                        @if($certificate->certificate_file)
                                                            <a href="{{ $certificate->certificate_url }}" target="_blank" class="btn btn-sm btn-primary">Lihat Sertifikat</a>
                                                            @if(!$certificate->certificateFileExists())
                                                                <div class="text-warning"><small>⚠️ File tidak tersedia</small></div>
                                                            @endif
                                                        @else
                                                            <p>Tidak ada file</p>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Sertifikat -->
    <div class="modal fade" id="uploadCertificateModal" tabindex="-1" aria-labelledby="uploadCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCertificateModalLabel">Upload Sertifikat Inspektor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="certificateUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="inspector_user_id" name="user_id">

                        <div class="mb-3">
                            <label for="qualification" class="form-label">Kualifikasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="qualification" name="qualification" required>
                        </div>

                        <div class="mb-3">
                            <label for="certificate_number" class="form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="certificate_number" name="certificate_number" required>
                        </div>

                        <div class="mb-3">
                            <label for="expired_date" class="form-label">Tanggal Kadaluarsa <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="expired_date" name="expired_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="certificate_file" class="form-label">File Sertifikat <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="certificate_file" name="certificate_file" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Format yang diterima: PDF, JPG, JPEG, PNG (Maks: 5MB)</small>
                        </div>

                        <div id="previous_certificates" class="mt-4">
                            <h6>Riwayat Sertifikat</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kualifikasi</th>
                                            <th>No. Sertifikat</th>
                                            <th>Kadaluarsa</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody id="certificates_history">
                                        <!-- Data will be loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit User Inspektor -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Tambah Inspektor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="userForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id">
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="user_email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_phone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="user_phone" name="phone">
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_ktp" class="form-label">No. KTP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_ktp" name="ktp" maxlength="16" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_role" name="role" required>
                                <option value="">Pilih Role</option>
                                @foreach($availableRoles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                                @endforeach
                            </select>
                            @if(auth()->user()->hasRole('superadmin'))
                                <small class="text-muted">Super Admin dapat memberikan role Administrator dan role lainnya</small>
                            @elseif(auth()->user()->hasRole('administrator'))
                                <small class="text-muted">Administrator dapat memberikan role Inspektor dan Supervisor</small>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_password" class="form-label">Password <span class="text-danger" id="password_required">*</span></label>
                            <input type="password" class="form-control" id="user_password" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password (untuk edit)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="user_password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pengelolaan Kompetensi -->
    <div class="modal fade" id="competencyModal" tabindex="-1" aria-labelledby="competencyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="competencyModalLabel">Kelola Kompetensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="competency_user_id" name="user_id">
                    
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="competencyTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="add-competency-tab" data-bs-toggle="tab" data-bs-target="#add-competency" type="button" role="tab">Tambah Kompetensi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-competency-tab" data-bs-toggle="tab" data-bs-target="#history-competency" type="button" role="tab">Riwayat Kompetensi</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3" id="competencyTabContent">
                        <!-- Tab Tambah Kompetensi -->
                        <div class="tab-pane fade show active" id="add-competency" role="tabpanel">
                            <form id="competencyForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="qualification" class="form-label">Kualifikasi <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="qualification" name="qualification" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="certificate_number" class="form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="certificate_number" name="certificate_number" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expired_date" class="form-label">Tanggal Kadaluarsa <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="expired_date" name="expired_date" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="certificate_file" class="form-label">File Sertifikat <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="certificate_file" name="certificate_file" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <small class="text-muted">Format yang diterima: PDF, JPG, JPEG, PNG (Maks: 5MB)</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Tambah Kompetensi</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Tab Riwayat Kompetensi -->
                        <div class="tab-pane fade" id="history-competency" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kualifikasi</th>
                                            <th>No. Sertifikat</th>
                                            <th>Kadaluarsa</th>
                                            <th>File</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="competency_history_list">
                                        <!-- Data will be loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<!-- FilePond JS -->
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Gambar %1 dari %2"
    });

    // Register FilePond plugins
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

    // Function to show certificate error
    function showCertificateError() {
        iziToast.error({
            title: 'Error',
            message: 'Sertifikat tidak dapat diakses. Mungkin file rusak atau tidak ada.',
            position: 'topRight'
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#companyForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '{{ route("company.save", ["id" => $company->id]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.status === 'success') {
                        iziToast.success({
                            title: 'Sukses',
                            message: response.message,
                            position: 'topRight'
                        });

                        // Perbarui gambar preview logo jika ada perubahan
                        if (response.data.logo) {
                            try {
                                // Reload the page to get fresh temporary URLs
                                location.reload();
                            } catch (error) {
                                console.log('Error updating logo preview:', error);
                            }
                        }
                        // Bersihkan input file logo
                        $('#logo').val('');
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: 'Terjadi kesalahan: ' + response.message,
                            position: 'topRight'
                        });
                        console.log('Error:', response);
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan saat mengirim permintaan.',
                        position: 'topRight'
                    });
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            });
        });
    });
</script>

<script>
$(document).ready(function() {
    $('#certificateForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Get the file from FilePond
        const pondFiles = pond.getFiles();
        if (pondFiles.length > 0) {
            formData.append('certificate_file', pondFiles[0].file);
        }

        $.ajax({
            url: '{{ route("company.certificate.save", ["id" => $company->id]) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status === 'success') {
                    iziToast.success({
                        title: 'Sukses',
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reset FilePond
                    pond.removeFiles();

                    // Reload page to get fresh certificate data and URLs
                    setTimeout(() => {
                        location.reload();
                    }, 1000);

                } else {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan: ' + response.message,
                        position: 'topRight'
                    });
                    console.log('Error:', response);
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    title: 'Error',
                    message: 'Terjadi kesalahan saat mengirim permintaan.',
                    position: 'topRight'
                });
                console.log('Status:', status);
                console.log('Error:', error);
                console.log('Response:', xhr.responseText);
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // Tombol untuk membuka modal upload sertifikat
    $('.btn-upload-certificate').click(function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');

        // Set user ID di form
        $('#inspector_user_id').val(userId);

        // Update judul modal
        $('#uploadCertificateModalLabel').text('Upload Sertifikat untuk ' + userName);

        // Load riwayat sertifikat
        loadUserCertificates(userId);

        // Buka modal
        $('#uploadCertificateModal').modal('show');
    });

    // Fungsi untuk memuat riwayat sertifikat
    function loadUserCertificates(userId) {
        $.ajax({
            url: "{{ url('user-certificates') }}/" + userId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let html = '';
                    const certificates = response.data;

                    if (certificates.length > 0) {
                        certificates.forEach(function(cert) {
                            const expiredDate = new Date(cert.expired_date).toLocaleDateString('id-ID');

                            html += '<tr>';
                            html += '<td>' + cert.qualification + '</td>';
                            html += '<td>' + cert.certificate_number + '</td>';
                            html += '<td>' + expiredDate + '</td>';
                            html += '<td>';
                            if (cert.certificate_file) {
                                // Construct the URL manually
                                const doBaseUrl = '{{ config("filesystems.disks.digitalocean.url") ?? config("filesystems.disks.digitalocean.bucket_endpoint") ?? "" }}';
                                const certUrl = doBaseUrl.replace(/\/$/, '') + '/' + cert.certificate_file.replace(/^\//, '');
                                html += '<a href="' + certUrl + '" target="_blank" class="btn btn-sm btn-primary">Lihat</a>';
                            } else {
                                html += '<span class="text-muted">Tidak ada file</span>';
                            }
                            html += '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center">Belum ada sertifikat</td></tr>';
                    }

                    $('#certificates_history').html(html);
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    title: 'Error',
                    message: 'Gagal memuat data sertifikat',
                    position: 'topRight'
                });
                console.log('Error:', error);
            }
        });
    }

    // Submit form upload sertifikat
    $('#certificateUploadForm').submit(function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "{{ url('upload-certificate') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    iziToast.success({
                        title: 'Sukses',
                        message: response.message,
                        position: 'topRight'
                    });

                    // Tutup modal
                    $('#uploadCertificateModal').modal('hide');

                    // Refresh halaman untuk menampilkan sertifikat baru
                    location.reload();
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    for (const key in errors) {
                        errorMessage += errors[key][0] + '<br>';
                    }

                    iziToast.error({
                        title: 'Validasi Error',
                        message: errorMessage,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan saat mengupload sertifikat',
                        position: 'topRight'
                    });
                }

                console.log('Error:', xhr.responseText);
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // ===== USER MANAGEMENT =====
    // Tambah Inspektor (User baru)
    $('#btnAddInspector').click(function() {
        $('#userModal').modal('show');
        $('#userModalLabel').text('Tambah Inspektor');
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#user_username').val('');
        $('#password_required').show();
        $('#user_password').prop('required', true);
    });

    // Edit User Data
    $(document).on('click', '.btn-edit-inspector', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        const userUsername = $(this).data('user-username');
        const userEmail = $(this).data('user-email');
        const userPhone = $(this).data('user-phone') || '';
        const userKtp = $(this).data('user-ktp') || '';
        const userRole = $(this).data('user-role') || '';

        $('#user_id').val(userId);
        $('#user_name').val(userName);
        $('#user_username').val(userUsername);
        $('#user_email').val(userEmail);
        $('#user_phone').val(userPhone);
        $('#user_ktp').val(userKtp);
        $('#user_role').val(userRole);
        
        $('#password_required').hide();
        $('#user_password').prop('required', false);
        $('#userModalLabel').text('Edit Data User: ' + userName);
        $('#userModal').modal('show');
    });

    // Submit Form User
    $('#userForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#user_id').val() !== '';
        const url = isEdit ? 
            "{{ url('company/inspector/update') }}/" + $('#user_id').val() :
            "{{ url('company/inspector/store') }}";

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    iziToast.success({
                        title: 'Sukses',
                        message: response.message,
                        position: 'topRight'
                    });
                    
                    $('#userModal').modal('hide');
                    
                    // Reload page to see changes
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    for (const key in errors) {
                        errorMessage += errors[key][0] + '<br>';
                    }

                    iziToast.error({
                        title: 'Validasi Error',
                        message: errorMessage,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan saat menyimpan data user',
                        position: 'topRight'
                    });
                }

                console.log('Error:', xhr.responseText);
            }
        });
    });

    // ===== COMPETENCY MANAGEMENT =====
    // Kelola Kompetensi
    $(document).on('click', '.btn-manage-competency', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');

        $('#competency_user_id').val(userId);
        $('#competencyModalLabel').text('Kelola Kompetensi: ' + userName);
        
        // Load existing competencies
        loadUserCompetencies(userId);
        
        $('#competencyModal').modal('show');
        
        // Reset form and show add tab
        $('#competencyForm')[0].reset();
        $('#add-competency-tab').tab('show');
    });

    // Submit Form Kompetensi
    $('#competencyForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const userId = $('#competency_user_id').val();
        formData.append('user_id', userId);

        $.ajax({
            url: "{{ url('company/competency/store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    iziToast.success({
                        title: 'Sukses',
                        message: response.message,
                        position: 'topRight'
                    });
                    
                    // Reset form and reload competency history
                    $('#competencyForm')[0].reset();
                    loadUserCompetencies(userId);
                    
                    // Switch to history tab
                    $('#history-competency-tab').tab('show');
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    for (const key in errors) {
                        errorMessage += errors[key][0] + '<br>';
                    }

                    iziToast.error({
                        title: 'Validasi Error',
                        message: errorMessage,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan saat menyimpan kompetensi',
                        position: 'topRight'
                    });
                }

                console.log('Error:', xhr.responseText);
            }
        });
    });

    // Load User Competencies
    function loadUserCompetencies(userId) {
        $.ajax({
            url: "{{ url('user-certificates') }}/" + userId,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let html = '';
                    const competencies = response.data;

                    if (competencies.length > 0) {
                        competencies.forEach(function(comp) {
                            const expiredDate = new Date(comp.expired_date).toLocaleDateString('id-ID');
                            const isExpired = new Date(comp.expired_date) < new Date();
                            const statusClass = isExpired ? 'text-danger' : 'text-success';
                            const statusText = isExpired ? '(Expired)' : '(Active)';

                            html += '<tr>';
                            html += '<td>' + comp.qualification + '</td>';
                            html += '<td>' + comp.certificate_number + '</td>';
                            html += '<td class="' + statusClass + '">' + expiredDate + ' ' + statusText + '</td>';
                            html += '<td>';
                            if (comp.certificate_file) {
                                const doBaseUrl = '{{ config("filesystems.disks.digitalocean.url") ?? config("filesystems.disks.digitalocean.bucket_endpoint") ?? "" }}';
                                const certUrl = doBaseUrl.replace(/\/$/, '') + '/' + comp.certificate_file.replace(/^\//, '');
                                html += '<a href="' + certUrl + '" target="_blank" class="btn btn-sm btn-primary">Lihat</a>';
                            } else {
                                html += '<span class="text-muted">Tidak ada file</span>';
                            }
                            html += '</td>';
                            html += '<td>';
                            html += '<button type="button" class="btn btn-sm btn-danger btn-delete-competency" data-competency-id="' + comp.id + '">';
                            html += '<i class="fas fa-trash"></i></button>';
                            html += '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="5" class="text-center">Belum ada kompetensi</td></tr>';
                    }

                    $('#competency_history_list').html(html);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error loading competencies:', error);
            }
        });
    }

    // Delete Competency
    $(document).on('click', '.btn-delete-competency', function() {
        const competencyId = $(this).data('competency-id');
        
        if (confirm('Apakah Anda yakin ingin menghapus kompetensi ini?')) {
            $.ajax({
                url: "{{ url('company/competency/delete') }}/" + competencyId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        iziToast.success({
                            title: 'Sukses',
                            message: response.message,
                            position: 'topRight'
                        });
                        
                        // Reload competencies
                        const userId = $('#competency_user_id').val();
                        loadUserCompetencies(userId);
                        
                        // Also reload page to update main table
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan saat menghapus kompetensi',
                        position: 'topRight'
                    });
                }
            });
        }
    });

    // ===== DELETE USER =====
    // Delete Inspektor
    $(document).on('click', '.btn-delete-inspector', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        
        if (confirm('Apakah Anda yakin ingin menghapus inspektor: ' + userName + '?\n\nSemua data kompetensi akan ikut terhapus.')) {
            $.ajax({
                url: "{{ url('company/inspector/delete') }}/" + userId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        iziToast.success({
                            title: 'Sukses',
                            message: response.message,
                            position: 'topRight'
                        });
                        
                        // Reload page to see changes
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan saat menghapus inspektor',
                        position: 'topRight'
                    });
                    console.log('Error:', xhr.responseText);
                }
            });
        }
    });
});
</script>

{{--todo: remove data dump--}}
<!--

$company = {!! $company->toJson(JSON_PRETTY_PRINT) !!};

@if($company->activeCertificate)
$company->activeCertificate = {!! $company->activeCertificate->toJson(JSON_PRETTY_PRINT) !!};
@else
$company->activeCertificate kosong
@endif

-->

@endsection
