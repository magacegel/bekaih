@extends('layouts.master')

@section('content')


<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Ship Type Management
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Ship Type Management</li>
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
          <a href="#" class="btn btn-sm btn-primary add_form_btn">
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
                <th>Ship Type</th>
                <th style="width: 245px;">Action</th>
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


  .header_table, .title_table, .form_table, .signature_table {
    width: 100%;
  }
  .title_table, .form_table {
    margin-bottom: 25px;
  }
  .form_table td , .form_table th {
    text-align: center;
  }
  .preview_txt {
    color: red;
    font-weight: bold;
  }

  @media (min-width: 1200px)
  {
    .modal-xl {
      max-width: 1140px !important;
    }
  }

  @media (min-width: 992px)
  {
    .modal-xl {
      max-width: 800px;
    }  
  }  
</style>

<div class="modal fade" id="create_form_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Form Type</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>

      <form id="create_form" action="<?=url('ship_type_data');?>" method="POST">
        <div class="modal-body">



          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="create">


            <tr class="tr_add_new">
              <td class="align-middle">Ship Type</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="ship_type" class="form-control ship_type">
              </td>
            </tr>

          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Ship Type</button>
        </div>
      </form>
    </div>
  </div>
</div>



<div class="modal fade" id="edit_form_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Form Type</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>

      <form id="edit_form" action="<?=url('ship_type_data');?>" method="POST">
        <div class="modal-body">



          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="ship_type_id" class="ship_type_id" value="">
            <input type="hidden" name="action" value="edit">


            <tr class="tr_add_new">
              <td class="align-middle">Ship Type</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="ship_type" class="form-control ship_type">
              </td>
            </tr>

          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Ship Type</button>
        </div>
      </form>
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



    $('.add_form_btn').click(function(e){
      e.preventDefault();

      $('#create_form_modal input[type=text]').val('');
      $('#create_form_modal').modal('show');
    });



    $('#my_table').DataTable({
      processing: true,
      serverSide: false,
      ajax: '<?=url('settings/ship_type_datatables');?>',
      columns: [      
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },  
        { data: 'type', name: 'type' },
        { data: 'action', name: 'action', orderable: false, searchable: false},
        ],
      columnDefs: [
      {
        targets: 0,
        className: 'text-center',
        width: '20px',
      },
      {
        targets: 2,
        className: 'text-center',
        width: '132px',
      },
      ],
    });


    // Harus menunggu data-tables di load dulu, baru jQuery bisa jalan  
    $('#my_table').on( 'draw.dt', function () {

      $('.b_detail, .b_edit, .b_preview, .b_delete').tooltip({show: {effect:"none", delay:0}});



      $(".b_delete").click(function(event){
        event.preventDefault();     

        form_count = $(this).attr('form_count');
        if(form_count > 0)
        {
          s=form_count>1?'s':'';
          text = 'There are '+form_count+' form'+s+' in this report.\nDo you really want to delete this ship type?';
        }
        else
        {
          text = 'Do you really want to delete this ship type?\nThis process can not be undone!';
        }
        if(confirm(text))
        {
          ship_type_id = $(this).attr('id');

          $.ajax({
            url: "<?=url('/settings/ship_type_data')?>",
            type: 'post',
            dataType: "json",
            data: {
              "action":'delete',
              "ship_type_id":ship_type_id,
              "_token":'{{ csrf_token() }}',
            }, 
            success: function(data) {

              if(data.status=='success')
              {
                iziToast.success({position: "topRight", title: 'Success', message: data.message});
                $('#my_table').DataTable().ajax.reload();
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


      $(".b_edit").click(function(event){
        event.preventDefault();

        id = $(this).attr('id');

        $('#edit_form .ship_type_id').val(id);

        $.ajax({
          url: "<?=url('/settings/ship_type_data')?>",
          type: 'post',
          dataType: "json",
          data: {
            "action":'detail',
            "ship_type_id":id,
            "_token":'{{ csrf_token() }}',
          }, 
          success: function(response) {

            if(response.status=='success')
            {
              $('#edit_form_modal .ship_type').val(response.data.type);

              $('#edit_form_modal').modal('show');
            }
            else
            {
              iziToast.error({position: "topRight", title: 'Error', message: response.message});
            }

          },
          error: function(d) {
            alert('error');
          },
        });

      });    


    });







    $('#create_form').submit(function(e){
      e.preventDefault();
      // $('.page-loader-wrapper').show();

      $.ajax({
        url: "/settings/ship_type_data",
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
            $('#create_form_modal').modal('hide');
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
              var el = $('#create_form_modal').find('.'+i+'');
              el.after($('<div class="error_message">'+error[0]+'</div>'));
            });

              // Menyembunyikan error message setelah beberapa waktu
            $('.error_message').delay(5000).fadeOut('slow');
          }
        }

      });



    });




    $('#edit_form').submit(function(e){
      e.preventDefault();
      // $('.page-loader-wrapper').show();

      $.ajax({
        url: "/settings/ship_type_data",
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
            $('#edit_form_modal').modal('hide');
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
              var el = $('#edit_form_modal').find('.'+i+'');
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

@endsection
