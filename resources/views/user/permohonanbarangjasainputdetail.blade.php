@extends('layout.default')

@section('mainframe')

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Tambah Permohonan Barang & Jasa</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{url('administrator')}}">Dashboard</a></div>
                <div class="breadcrumb-item">Tambah Permohonan Barang & Jasa</div>
            </div>
        </div>
        <div class="section-body">
            {{-- <h2 class="section-title">Advanced Forms</h2> --}}
            <p class="section-title" style="font-size:10pt;">Simpan Terlebih Dahulu Permohonan Induknya Baru Dapat Mengisi Barang dan Jasanya</p>

            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Detail Pemohon</h5>
                            @foreach ($no_ids as $no_id)
                            <div class="form-group row col-12">
                                <div class="col-6">
                                    <input id="idpemohon" name="idpemohon" type="hidden" class="form-control" value="{{ $no_id->id }}">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Penyedia Barang/Jasa</label>
                                        <div class="col-8">
                                            <label class="col-3 col-form-label">{{ $no_id->perusahaan }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Paket Pengadaan</label>
                                        <div class="col-8">
                                            <label class="col-3 col-form-label">{{ $no_id->paket_pengadaan }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">No Dokumen</label>
                                        <div class="col-8">
                                            <label class="col-3 col-form-label">{{ $no_id->no_dokumen }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Alamat</label>
                                        <div class="col-8">
                                            <label class="col-3 col-form-label">{{ $no_id->alamat }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Pengguna Barang</label>
                                        <div class="col-8">
                                            <label class="col-3 col-form-label">{{ $no_id->keperluan }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Nama PIC/Direktur</label>
                                        <div class="col-8">
                                            <label class="col-3 col-form-label">{{ $no_id->pic }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="card-body">
                        <h5>6 Kategori</h5>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-3">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    <li class="nav-item">
                                    <a class="nav-link active" id="tab1-tab4" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Material Terpakai</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" id="tab2-tab4" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Peralatan Terpasang</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" id="tab3-tab4" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Personil Konsultan</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" id="tab4-tab4" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">Alat Kerja</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" id="tab5-tab4" data-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false">Konstruksi Fabrikasi</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" id="tab6-tab4" data-toggle="tab" href="#tab6" role="tab" aria-controls="tab6" aria-selected="false">Jasa Umum</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-9">
                            <div class="tab-content no-padding" id="myTab2Content">
                                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab4">
                                    <div class="card-header">
                                        <div class="col-10 mr-0 pr-0">
                                            <h3 class="card-title" style="inline-block"></h3>
                                        </div>
                                        <div class="col-2 dropdown d-inline">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalMaterialTerpakai">Tambah</button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table id="member" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode Permohonan</th>
                                                    <th>Penyedia Barang</th>
                                                    <th>Alamat</th>
                                                    <th>Paket Pengadaan</th>
                                                    <th>Pengguna Barang</th>
                                                    <th>No Dokumen</th>
                                                    <th>Verifikator</th>
                                                    <th>Barang</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="buttons row">
                                                            <a href="#" class="btn btn-icon btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-warning"><i class="far fa-file"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab4">
                                    <div class="card-header">
                                        <div class="col-10 mr-0 pr-0">
                                            <h3 class="card-title" style="inline-block"></h3>
                                        </div>
                                        <div class="col-2 dropdown d-inline">
                                            <button class="btn btn-primary" type="button" id="generate">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table id="member" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode Permohonan</th>
                                                    <th>Penyedia Barang</th>
                                                    <th>Alamat</th>
                                                    <th>Paket Pengadaan</th>
                                                    <th>Pengguna Barang</th>
                                                    <th>No Dokumen</th>
                                                    <th>Verifikator</th>
                                                    <th>Barang</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="buttons row">
                                                            <a href="#" class="btn btn-icon btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-warning"><i class="far fa-file"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab4">
                                    <div class="card-header">
                                        <div class="col-10 mr-0 pr-0">
                                            <h3 class="card-title" style="inline-block"></h3>
                                        </div>
                                        <div class="col-2 dropdown d-inline">
                                            <button class="btn btn-primary" type="button" id="generate">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table id="member" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode Permohonan</th>
                                                    <th>Penyedia Barang</th>
                                                    <th>Alamat</th>
                                                    <th>Paket Pengadaan</th>
                                                    <th>Pengguna Barang</th>
                                                    <th>No Dokumen</th>
                                                    <th>Verifikator</th>
                                                    <th>Barang</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="buttons row">
                                                            <a href="#" class="btn btn-icon btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-warning"><i class="far fa-file"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab4">
                                    <div class="card-header">
                                        <div class="col-10 mr-0 pr-0">
                                            <h3 class="card-title" style="inline-block"></h3>
                                        </div>
                                        <div class="col-2 dropdown d-inline">
                                            <button class="btn btn-primary" type="button" id="generate">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table id="member" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode Permohonan</th>
                                                    <th>Penyedia Barang</th>
                                                    <th>Alamat</th>
                                                    <th>Paket Pengadaan</th>
                                                    <th>Pengguna Barang</th>
                                                    <th>No Dokumen</th>
                                                    <th>Verifikator</th>
                                                    <th>Barang</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="buttons row">
                                                            <a href="#" class="btn btn-icon btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-warning"><i class="far fa-file"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5-tab4">
                                    <div class="card-header">
                                        <div class="col-10 mr-0 pr-0">
                                            <h3 class="card-title" style="inline-block"></h3>
                                        </div>
                                        <div class="col-2 dropdown d-inline">
                                            <button class="btn btn-primary" type="button" id="generate">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table id="member" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode Permohonan</th>
                                                    <th>Penyedia Barang</th>
                                                    <th>Alamat</th>
                                                    <th>Paket Pengadaan</th>
                                                    <th>Pengguna Barang</th>
                                                    <th>No Dokumen</th>
                                                    <th>Verifikator</th>
                                                    <th>Barang</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="buttons row">
                                                            <a href="#" class="btn btn-icon btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-warning"><i class="far fa-file"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab6" role="tabpanel" aria-labelledby="tab6-tab4">
                                    <div class="card-header">
                                        <div class="col-10 mr-0 pr-0">
                                            <h3 class="card-title" style="inline-block"></h3>
                                        </div>
                                        <div class="col-2 dropdown d-inline">
                                            <button class="btn btn-primary" type="button" id="generate">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table id="member" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Kode Permohonan</th>
                                                    <th>Penyedia Barang</th>
                                                    <th>Alamat</th>
                                                    <th>Paket Pengadaan</th>
                                                    <th>Pengguna Barang</th>
                                                    <th>No Dokumen</th>
                                                    <th>Verifikator</th>
                                                    <th>Barang</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="buttons row">
                                                            <a href="#" class="btn btn-icon btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                            <a href="#" class="btn btn-icon btn-sm btn-warning"><i class="far fa-file"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalMaterialTerpakai">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalPeralatanTerpasang">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalPersonilKonsultan">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalAlatKerja">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalKonsultasiFabrikasi">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalJasaUmum">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection


@section('css')


@endsection
@section('js')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function handleSave(input) {
        event.preventDefault();

        Swal.fire({
        title: "Are you sure Confirm & Send?",
        text: "You wont be able to revert this!",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Send It!",
        cancelButtonText: "No, Cancel!",
        reverseButtons: true
        }).then(function(result) {
            if (result.value === true) {
                tambahorder.submit();
                return true;
            } else if (result.dismiss === "cancel") {
                return false;
            }
        });
    }

    // $("#tambahorder").submit(function(e) {
    //     //alert("test");
    //     e.preventDefault(); // avoid to execute the actual submit of the form.

    //     var form = $(this);
    //     var actionUrl = form.attr('action');

    //     $.ajax({
    //         type: "POST",
    //         url: "{{url('a-tambahpemohonbarangjasa')}}",
    //         data: form.serialize(), // serializes the form's elements.
    //         success: function(data)
    //         {
    //             iziToast.success({
    //                 title: 'Hello, world!',
    //                 message: 'This awesome plugin is made by iziToast',
    //                 position: 'topRight'
    //             });
    //             alert(data); // show response from the php script.
    //         }
    //     });
    // });

    // if ($("#tambahorder").length > 0) {
    //     $("#tambahorder").validate({
    //         rules: {
    //             name: {
    //                 required: true,
    //                 maxlength: 50
    //                 },
    //             email: {
    //                 required: true,
    //                 maxlength: 50,
    //                 email: true,
    //                 },
    //             message: {
    //                 required: true,
    //                 maxlength: 300
    //                 },
    //             },
    //         messages: {
    //             name: {
    //                 required: "Please enter name",
    //                 maxlength: "Your name maxlength should be 50 characters long."
    //                 },
    //             email: {
    //                 required: "Please enter valid email",
    //                 email: "Please enter valid email",
    //                 maxlength: "The email name should less than or equal to 50 characters",
    //                 },
    //             message: {
    //                 required: "Please enter message",
    //                 maxlength: "Your message name maxlength should be 300 characters long."
    //                 },
    //             },
    //         submitHandler: function(form) {
    //             $.ajaxSetup({
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 }
    //             });
    //             $('#submit').html('Please Wait...');
    //             $("#submit"). attr("disabled", true);
    //             $.ajax({
    //                 url: "{{url('store')}}",
    //                 type: "POST",
    //                 data: $('#tambahorder').serialize(),
    //                 success: function( response ) {
    //                     $('#submit').html('Submit');
    //                     $("#submit"). attr("disabled", false);
    //                     alert('Ajax form has been submitted successfully');
    //                     document.getElementById("tambahorder").reset();
    //                     iziToast.success({
    //                         title: 'Hello, world!',
    //                         message: 'This awesome plugin is made by iziToast',
    //                         position: 'topRight'
    //                     });
    //                 }
    //             });
    //         }
    //     })
    // }
</script>
@endsection
