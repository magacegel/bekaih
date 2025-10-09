@extends('layouts.master')

@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">User Profile</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">User Profile</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- end page title -->

@if(session('warning'))
<div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
    <strong>Perhatian!</strong> {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h5 class="fw-semibold">User Information</h5>

        <form id="profile_form" enctype="multipart/form-data">
          <input type="hidden" name="user_id" class="user_id" value="{{ Auth::user()->id }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="action" value="update_profile">

          <table class="table">
            <tr>
              <td colspan="3">
                <small><strong>Perhatian!</strong> Semua field dengan tanda bintang (<span class="text-danger">*</span>) wajib diisi.</small>
              </td>
            </tr>
            <tr>
              <td>Role</td>
              <td>:</td>
              <td>{{ ucfirst(Auth::user()->getRoleNames()->first() ?? '-') }}</td>
            </tr>
            <tr>
              <td>Name</td>
              <td>:</td>
              <td>
                <input type="text" name="name" class="form-control name" readonly value="{{ Auth()->user()->name ?? '' }}">
              </td>
            </tr>

            @if(Auth()->user()->user_type == 'bki')
            <tr>
              <td>NUP</td>
              <td>:</td>
              <td>{{ Auth()->user()->cif ?? '-' }}</td>
            </tr>
            @endif

            <tr>
              <td>No. KTP <span style="color: red;">*</span></td>
              <td>:</td>
              <td>
                <input type="text" name="ktp" class="form-control ktp" placeholder="16-Digit No. KTP" value="{{ Auth()->user()->ktp ?? '' }}" required>
              </td>
            </tr>

            <tr>
              <td>Username</td>
              <td>:</td>
              <td>
                <input type="text" name="username" class="form-control username" placeholder="{{ Auth::user()->username ?? '' }}" value="{{ Auth::user()->username ?? '' }}" readonly>
              </td>
            </tr>

            <tr>
              <td>Email <span style="color: red;">*</span></td>
              <td>:</td>
              <td>
                <input type="email" name="email" class="form-control email" placeholder="example@gmail.com" value="{{ Auth()->user()->email ?? '' }}" required>
              </td>
            </tr>

            <tr>
              <td>Phone</td>
              <td>:</td>
              <td>
                <input type="text" name="phone" class="form-control phone" placeholder="6281234567890" value="{{ Auth()->user()->phone ?? '' }}">
              </td>
            </tr>

            <tr>
              <td>Entitas Perusahaan</td>
              <td>:</td>
              <td>
                <input type="text" name="cabang" readonly class="form-control cabang" value="{{ Auth()->user()->company->name ?? '-' }} - {{ Auth()->user()->company->branch ?? '-' }}">
              </td>
            </tr>

            <tr>
              <td>Profile Image</td>
              <td>:</td>
              <td>
                @php
                $profile = Auth()->user()->profile_image ? '/storage/uploads/profile_image/'.Auth()->user()->profile_image : '/images/no_profile.png';
                @endphp
                <img class="profile_image" src="{{ $profile }}" style="width: 200px;height: auto;">

                <input accept="image/*" type="file" id="profile_image" class="form-control profile" name="profile_image">
              </td>
            </tr>

            <tr>
              <td>Signature <span style="color: red;">*</span></td>
              <td>:</td>
              <td>
                <div class="signature-container d-flex">
                  <div class="signature-preview mr-3 pe-lg-3 border-end">
                    <h6>Current Signature</h6>
                    @php
                    try {
                        $signature = Auth()->user()->signature
                            ? Storage::disk('digitalocean')->temporaryUrl(Auth()->user()->signature, now()->addMinutes(5))
                            : '/images/no_signature.png';
                    } catch (\Exception $e) {
                        $signature = '/images/no_signature.png';
                    }
                    @endphp
                    <img class="signature_image" src="{{ $signature }}" alt="Current Signature" style="width: 200px; height: auto; border: 1px solid #ddd;">
                  </div>
                  <div class="signature-options">
                    <div class="signature-draw mb-3">
                      <h6>Draw Signature</h6>
                      <canvas id="signature_canvas" width="200" height="100" style="border:1px solid #000000;"></canvas>
                      <div class="signature-buttons mt-2">
                        <button type="button" id="clear_signature" class="btn btn-sm btn-secondary">Clear</button>
                        <button type="button" id="save_signature" class="btn btn-sm btn-primary">Generate Signature</button>
                      </div>
                      <input type="hidden" id="signature_data" name="signature_data">
                    </div>
                    <div class="signature-upload">
                      <h6>Upload Signature</h6>
                      <input accept="image/*" type="file" id="signature" class="form-control signature" name="signature">
                    </div>
                  </div>
                </div>
              </td>
            </tr>

          </table>
          <br>
          <div class="text-center">
            <input class="btn btn-primary" type="submit" value="Simpan">
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="fw-semibold">Latest Competency</h5>
        @if(Auth()->user()->latestQualification)
          @if(\Carbon\Carbon::parse(Auth()->user()->latestQualification->expired_date)->lt(\Carbon\Carbon::now()))
          <div class="alert alert-warning mb-3">
            <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian!</strong> Sertifikat kompetensi Anda telah kadaluarsa. Silakan hubungi Supervisor atau HR perusahaan Anda untuk pembaruan sertifikat.
          </div>
          @endif
        @else
        <div class="alert alert-info mb-3">
          <i class="fas fa-info-circle"></i> <strong>Informasi:</strong> Anda belum memiliki sertifikat kompetensi. Silakan hubungi Supervisor atau HR perusahaan Anda untuk pemrosesan sertifikat.
        </div>
        @endif
        @if(Auth()->user()->latestQualification)
          <table class="table table-sm mt-3">
            <tr>
                <th>Certificate No</th>
                <td>{{ Auth()->user()->latestQualification->certificate_number }}</td>
              </tr>
              <tr>
                <th>Qualification</th>
                <td>{{ Auth()->user()->latestQualification->qualification }}</td>
              </tr>
              <tr>
                <th>Expiry Date</th>
                <td>{{ \Carbon\Carbon::parse(Auth()->user()->latestQualification->expired_date)->format('d M Y') }}</td>
              </tr>
            </table>
        @endif
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="fw-semibold">Change Password</h5>
        <form id="change_password_form" enctype="multipart/form-data" class="form-horizontal mt-3">
          <input type="hidden" name="user_id" class="user_id" value="{{ Auth::User()->id }}">
          <input type="hidden" name="action" value="change_password">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="mb-3">
            <label for="old_password" class="form-label">Old Password</label>
            <input type="password" class="form-control old_password" id="old_password" name="old_password" placeholder="Old Password" required>
          </div>
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control new_password" id="new_password" name="new_password" placeholder="New Password" required>
          </div>
          <div class="mb-3">
            <label for="new_password_confirm" class="form-label">New Password (Confirm)</label>
            <input type="password" class="form-control new_password_confirm" id="new_password_confirm" name="new_password_confirm" placeholder="New Password (Confirm)" required>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
@section('js')

<script type="text/javascript">
  $(document).ready(function(){

    $('#profile_form').submit(function(e){
      e.preventDefault();
      $('.page-loader-wrapper').show();

      $.ajax({
        url: "/profile",
        type: 'POST',
        data: new FormData( this ),
        processData: false,
        contentType: false,

        success: function(data) {
          console.log("datanya", data);
          $('.page-loader-wrapper').hide();

          if(data.status == 'success')
          {
            iziToast.success({position: "topRight", title: 'Berhasil', message: data.message});
          }
          else
          {
            iziToast.error({position: "topRight", title: 'Gagal', message: data.message});
          }

          if(data.profile_image)
          {
            $('.profile_image').attr('src', '{{ url('/') }}/storage/uploads/profile_image/'+data.profile_image);
          // Membersihkan input file untuk profile_image
            $('#profile_image').val('');
          }

          if(data.signature)
          {
            $('.signature_image').attr('src', '{{ url('/') }}/storage/uploads/signature/'+data.signature);
            // Membersihkan input file untuk signature
            $('#signature').val('');
          }

          setTimeout(function() {
            window.location.reload();
          }, 1000);
        },

        error: function (err) {
          $('.page-loader-wrapper').hide();
          // Status code is 422 artinya error di validation
          if (err.status == 422) {
            // Tampilkan error di tiap form
            $.each(err.responseJSON.errors, function (i, error) {
              var el = $('#profile_form').find('.'+i+'');
              el.after($('<div class="error_message">'+error[0]+'</div>'));
            });
            // Menyembunyikan error message setelah beberapa waktu
            $('.error_message').delay(5000).fadeOut('slow');
          }
        }

      });
    });



  $('#change_password_form').submit(function(e){
    e.preventDefault();
    $('.page-loader-wrapper').show();

      $.ajax({
          url: "/profile",
          type: 'POST',
          data: new FormData( this ),
          processData: false,
          contentType: false,

          success: function(data) {
            $('.page-loader-wrapper').hide();

            if(data.status == 'success')
            {
            iziToast.success({position: "topRight", title: 'Success', message: data.message});
            }
            else
            {
            iziToast.error({position: "topRight", title: 'Error', message: data.message});
            }
          },

          error: function (err) {
            $('.page-loader-wrapper').hide();

            // Status code is 422 artinya error di validation
            if (err.status == 422) {
              // Tampilkan error di tiap form
              $.each(err.responseJSON.errors, function (i, error) {
                  var el = $('#change_password_form').find('.'+i+'');
                  el.after($('<div class="error_message">'+error[0]+'</div>'));
              });
              // Menyembunyikan error message setelah beberapa waktu
              $('.error_message').delay(5000).fadeOut('slow');
            }
          }

      });



  });

  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signature_canvas');
    const context = canvas.getContext('2d');
    let isDrawing = false;
    let canEdit = true;

    function enableDrawing() {
      canvas.addEventListener('mousedown', startDrawing);
      canvas.addEventListener('mousemove', draw);
      canvas.addEventListener('mouseup', stopDrawing);
      canvas.addEventListener('mouseout', stopDrawing);
      canvas.style.opacity = '1';
      canvas.style.cursor = 'crosshair';
      $('.signature-upload').show(); // Changed from signature_upload to signature-upload
    }

    function disableDrawing() {
      canvas.removeEventListener('mousedown', startDrawing);
      canvas.removeEventListener('mousemove', draw);
      canvas.removeEventListener('mouseup', stopDrawing);
      canvas.removeEventListener('mouseout', stopDrawing);
      canvas.style.opacity = '0.2';
      canvas.style.cursor = 'not-allowed';
      $('.signature-upload').hide(); // Changed from signature_upload to signature-upload
    }

    function startDrawing(e) {
      if (!canEdit) return;
      isDrawing = true;
      context.beginPath();
      context.moveTo(e.offsetX, e.offsetY);
    }

    function draw(e) {
      if (!isDrawing || !canEdit) return;
      context.lineTo(e.offsetX, e.offsetY);
      context.stroke();
    }

    function stopDrawing() {
      isDrawing = false;
      context.closePath();
    }

    enableDrawing();

    document.getElementById('clear_signature').addEventListener('click', function() {
      context.clearRect(0, 0, canvas.width, canvas.height);
      document.getElementById('signature_data').value = '';
      if (!canEdit) {
        enableDrawing();
        canEdit = true;
      }
    });

    document.getElementById('save_signature').addEventListener('click', function() {
      const dataURL = canvas.toDataURL('image/png');
      document.getElementById('signature_data').value = dataURL;
      disableDrawing();
      canEdit = false;
    });
  });
</script>
@endsection
