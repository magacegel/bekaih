@extends('layouts.master')

@section('content')





<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        User Management
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">User Management</li>
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

        <div class="text-right" style="padding-right: 28px;">
          <a href="#" class="btn btn-sm btn-primary add_user_btn">
            <i class="fa fa-plus"></i>
            Add New
          </a>
        </div>

        <!-- <h5 class="fw-semibold">Add New Report</h5> -->

        <div class="card-body table-responsive">
          <table border="1" id="my_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Role</th>
                <th>User Type</th>
                <th style="width: 150px;">Action</th>
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



<style type="text/css">
  .tr_non_bki {
    display: none;
  }
</style>

<div class="modal fade" id="create_user_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New User</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>

      <form id="create_user_form" action="<?=url('user_data');?>" method="POST">
        <div class="modal-body">
          <input type="hidden" name="_token" value="<?=csrf_token();?>">
          <input type="hidden" name="action" value="create">
          
          <div class="mb-3">
            <label for="create_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="create_name" name="name" required>
          </div>
          
          <div class="mb-3">
            <label for="create_username" class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="create_username" name="username" required>
          </div>
          
          <div class="mb-3">
            <label for="create_email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="create_email" name="email" required>
          </div>
          
          <div class="mb-3">
            <label for="create_phone" class="form-label">No. Telepon</label>
            <input type="text" class="form-control" id="create_phone" name="phone">
          </div>
          
          <div class="mb-3">
            <label for="create_ktp" class="form-label">No. KTP <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="create_ktp" name="ktp" maxlength="16" required>
          </div>

          <div class="mb-3">
            <label for="create_company_id" class="form-label">Company <span class="text-danger">*</span></label>
            <select name="company_id" id="create_company_id" class="form-control" required>
              <option value="">Pilih Company</option>
              @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
              @endforeach
            </select>
            <small class="text-muted">Pilih perusahaan tempat user akan ditempatkan</small>
          </div>
          
          <div class="mb-3">
            <label for="create_role" class="form-label">Role <span class="text-danger">*</span></label>
            <select class="form-control" id="create_role" name="role" required>
              <option value="">Pilih Role</option>
              @foreach($roles as $role)
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
            <label for="create_password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="create_password" name="password" required>
          </div>
          
          <div class="mb-3">
            <label for="create_password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required>
          </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create User</button>
      </div>
    </form>
  </div>
</div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="edit_user_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit User</h4>
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
      </div>

      <form id="edit_user_form" action="<?=url('user_data');?>" method="POST">
        <div class="modal-body">
          <input type="hidden" name="_token" value="<?=csrf_token();?>">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="user_id" id="edit_user_id">

          <div class="mb-3">
            <label for="edit_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          
          <div class="mb-3">
            <label for="edit_username" class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" id="edit_username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_phone" class="form-label">No. Telepon</label>
            <input type="text" name="phone" id="edit_phone" class="form-control">
          </div>
          
          <div class="mb-3">
            <label for="edit_ktp" class="form-label">No. KTP <span class="text-danger">*</span></label>
            <input type="text" name="ktp" id="edit_ktp" class="form-control" maxlength="16" required>
          </div>

          <div class="mb-3">
            <label for="edit_company_id" class="form-label">Company <span class="text-danger">*</span></label>
            <select name="company_id" id="edit_company_id" class="form-control" required>
              <option value="">Pilih Company</option>
              @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_role" class="form-label">Role <span class="text-danger">*</span></label>
            <select name="role" id="edit_role" class="form-control" required>
              <option value="">Pilih Role</option>
              @foreach($roles as $role)
                <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="mb-3">
            <label for="edit_password" class="form-label">Password</label>
            <input type="password" name="password" id="edit_password" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
          </div>
          
          <div class="mb-3">
            <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection


@section('css')


@endsection
@section('js')

<script type="text/javascript">
  $(document).ready(function(){

    $('.add_user_btn').click(function(e){
      e.preventDefault();
      // Reset form
      $('#create_user_form')[0].reset();
      $('.error_message').remove();
      $('#create_user_modal').modal('show');
    });



    $('#my_table').DataTable({
      processing: true,
      serverSide: false,
      ajax: '<?=url('user_datatables');?>',
      columns: [      
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },  
        { data: 'name', name: 'name' },
        { data: 'username', name: 'username' },
        { data: 'email', name: 'email' },
        { data: 'phone', name: 'phone' },
        { data: 'company', name: 'company' },
        { data: 'role', name: 'role' },
        { data: 'usertype', name: 'usertype' },
        { data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });



  // Harus menunggu data-tables di load dulu, baru jQuery bisa jalan  
    $('#my_table').on( 'draw.dt', function () {

      $('.b_detail, .b_edit, .b_delete').tooltip({show: {effect:"none", delay:0}});

      $(".b_detail").click(function(event){
        // event.preventDefault();     
      });        


      $(".b_delete").click(function(event){
        event.preventDefault();     

        var text = 'Do you really want to delete this user?\nThis process can not be undone!';
        
        if(confirm(text))
        {
          user_id = $(this).data('id');

          $.ajax({
            url: "<?=url('/user_data')?>",
            type: 'post',
            dataType: "json",
            data: {
              "action":'delete',
              "user_id":user_id,
              "_token":'{{ csrf_token() }}',
            }, 
            success: function(data) {

              if(data.status=='success')
              {
                iziToast.success({position: "topRight", title: 'Success', message: data.message});
                $('#my_table').DataTable().ajax.reload();
                $('#create_user_modal').modal('hide');

              }
              else
              {
                iziToast.error({position: "topRight", title: 'Error', message: data.message});
              }

            },
            error: function(d) {
              alert('error');
            },
          });

        }

      });

      // Handle edit button click
      $(".b_edit").click(function(event){
        event.preventDefault();
        
        var userId = $(this).data('id');
        
        // Fetch user data
        $.ajax({
          url: "<?=url('/user_data')?>/" + userId,
          type: 'GET',
          dataType: "json",
          success: function(data) {
            if(data.status == 'success') {
              // Populate form fields - sesuai dengan struktur Company module
              $('#edit_user_id').val(data.data.id);
              $('#edit_name').val(data.data.name);
              $('#edit_username').val(data.data.username);
              $('#edit_email').val(data.data.email);
              $('#edit_phone').val(data.data.phone);
              $('#edit_ktp').val(data.data.ktp);
              $('#edit_company_id').val(data.data.company_id);
              $('#edit_role').val(data.data.role);
              
              // Show modal
              $('#edit_user_modal').modal('show');
            } else {
              iziToast.error({position: "topRight", title: 'Error', message: data.message});
            }
          },
          error: function(d) {
            iziToast.error({position: "topRight", title: 'Error', message: 'Failed to fetch user data'});
          }
        });
      });        

    });

    // Handle create user form submit
    $('#create_user_form').submit(function(e){
      e.preventDefault();
      // $('.page-loader-wrapper').show();

      $.ajax({
        url: "/user_data",
        type: 'POST',
        data: new FormData( this ),
        processData: false,
        contentType: false,

        success: function(data) {
          // $('.page-loader-wrapper').hide();

          if(data.status == 'success')
          {
              // toastr.success(data.message, 'Success');
            iziToast.success({position: "topRight", title: 'Success', message: data.message});
            $('#create_user_modal').modal('hide');
              // Refresh datatables
            $('#my_table').DataTable().ajax.reload();
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
              // Tampilkan toast
            iziToast.error({position: "topRight", title: 'Error', message: err.responseJSON.message});             
              // Tampilkan error di tiap form
            $.each(err.responseJSON.errors, function (i, error) {
              var el = $('#create_user_modal').find('.'+i+'');
              el.after($('<div class="error_message">'+error[0]+'</div>'));
            });

              // Menyembunyikan error message setelah beberapa waktu
            $('.error_message').delay(5000).fadeOut('slow');
          }
        }

      });
    });

    // Handle edit user form submit
    $('#edit_user_form').submit(function(e){
      e.preventDefault();

      $.ajax({
        url: "/user_data",
        type: 'POST',
        data: $(this).serialize(),
        dataType: "json",
        success: function(data) {
          if(data.status == 'success') {
            iziToast.success({position: "topRight", title: 'Success', message: data.message});
            $('#edit_user_modal').modal('hide');
            $('#my_table').DataTable().ajax.reload();
          } else {
            iziToast.error({position: "topRight", title: 'Error', message: data.message});
          }
        },
        error: function (err) {
          if (err.status == 422) {
            iziToast.error({position: "topRight", title: 'Error', message: err.responseJSON.message});
            $.each(err.responseJSON.errors, function (i, error) {
              var el = $('#edit_user_modal').find('[name="'+i+'"]');
              el.after($('<div class="error_message text-danger">'+error[0]+'</div>'));
            });
            $('.error_message').delay(5000).fadeOut('slow');
          } else {
            iziToast.error({position: "topRight", title: 'Error', message: 'Terjadi kesalahan sistem'});
          }
        }
      });
    });

  });
</script>

@endsection
