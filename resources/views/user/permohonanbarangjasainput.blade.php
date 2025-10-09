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
                    <input id="tipeinput" name="tipeinput" type="text" class="form-control" value="{{ $post }}">
                    <div class="card">
                        <div class="card-header">
                        <h4>Data Pemohon</h4>
                        </div>
                        <div class="card-body">
                            <form id="tambahorder" method="POST" action="{{url('a-tambahpemohonbarangjasa')}}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Penyedia Barang/Jasa</label>
                                    <div class="col-sm-9">
                                    <input id="pemohon" name="pemohon" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-9">
                                    <input id="alamat" name="alamat" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Paket Pengadaan</label>
                                    <div class="col-sm-9">
                                    <input id="paket_pengadaan" name="paket_pengadaan" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Pengguna Barang</label>
                                    <div class="col-sm-9">
                                    <input id="keperluan" name="keperluan" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">No Dokumen</label>
                                    <div class="col-sm-9">
                                    <input id="no_dokumen" name="no_dokumen" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nama PIC/Direktur</label>
                                    <div class="col-sm-9">
                                    <input id="direktur" name="direktur" type="text" class="form-control">
                                    </div>
                                </div>
                                <div>
                                    @if($post == "ADDPEMOHON")
                                    <button class="btn btn-primary" type="submit" onclick="return handleSave()">Save & Next</button>
                                    @elseif($post == "EDITPEMOHON")
                                    <button class="btn btn-primary" type="submit" onclick="return handleSave()">Update</button>
                                    @else
                                    <button class="btn btn-primary" type="submit" onclick="return handleDetail()">Detail</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
        title: "Are you sure Confirm & Save?",
        text: "You wont be able to revert this!",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Save It!",
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
