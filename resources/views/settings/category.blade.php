@extends('layouts.master')

@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Category Management
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Category Management</li>
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
                <th>Name</th>
                <th>Abbreviation</th>
                <th>Title</th>
                <th>Order</th>
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
        <h4 class="modal-title">Add New Category</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>

      <form id="create_form" action="<?=url('category_data');?>" method="POST">
        <div class="modal-body">



          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="create">


            <tr class="tr_add_new">
              <td class="align-middle" style="width:150px;">Category Name</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="name" class="form-control name">
              </td>
            </tr>
            <tr class="tr_add_new">
              <td class="align-middle">Abbreviation</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="abbreviation" class="form-control abbreviation">
              </td>
            </tr>
            <tr class="tr_add_new align-top">
              <td>Title</td>
              <td style="width:2px;">:</td>
              <td>
                <textarea name="title" class="form-control title"></textarea>
              </td>
            </tr>
            <tr class="tr_add_new align-top">
              <td>Description</td>
              <td style="width:2px;">:</td>
              <td>
                <textarea name="description" class="form-control description"></textarea>
              </td>
            </tr>
            <tr class="tr_add_new">
              <td class="align-middle">Order</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="order" class="form-control order">
              </td>
            </tr>
          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Category</button>
        </div>
      </form>
    </div>
  </div>
</div>



<div class="modal fade" id="edit_form_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Category</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>

      <form id="edit_form" action="<?=url('category_data');?>" method="POST">
        <div class="modal-body">



          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="category_id" class="category_id" value="">
            <input type="hidden" name="action" value="edit">


            <tr class="tr_add_new">
              <td class="align-middle" style="width:150px;">Category Name</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="name" class="form-control name">
              </td>
            </tr>
            <tr class="tr_add_new">
              <td class="align-middle">Abbreviation</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="abbreviation" class="form-control abbreviation">
              </td>
            </tr>
            <tr class="tr_add_new align-top">
              <td>Title</td>
              <td style="width:2px;">:</td>
              <td>
                <textarea name="title" class="form-control title"></textarea>
              </td>
            </tr>
            <tr class="tr_add_new align-top">
              <td>Description</td>
              <td style="width:2px;">:</td>
              <td>
                <textarea name="description" class="form-control description"></textarea>
              </td>
            </tr>
            <tr class="tr_add_new">
              <td class="align-middle">Order</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="order" class="form-control order">
              </td>
            </tr>

          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Category</button>
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
      ajax: '<?=url('settings/category_datatables');?>',
      columns: [      
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },  
        { data: 'name', name: 'name' },
        { data: 'abbreviation', name: 'abbreviation' },
        { data: 'title', name: 'title' },
        { data: 'order', name: 'order' },
        { data: 'action', name: 'action', orderable: false, searchable: false},
        ],
      columnDefs: [
      {
        targets: 0,
        className: 'text-center',
        width: '20px',
      },
      {
        targets: 5,
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
          text = 'There are '+form_count+' form'+s+' in this report.\nDo you really want to delete this Category?';
        }
        else
        {
          text = 'Do you really want to delete this Category?\nThis process can not be undone!';
        }
        if(confirm(text))
        {
          category_id = $(this).attr('id');

          $.ajax({
            url: "<?=url('/settings/category_data')?>",
            type: 'post',
            dataType: "json",
            data: {
              "action":'delete',
              "category_id":category_id,
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

        $('#edit_form .category_id').val(id);

        $.ajax({
          url: "<?=url('/settings/category_data')?>",
          type: 'post',
          dataType: "json",
          data: {
            "action":'detail',
            "category_id":id,
            "_token":'{{ csrf_token() }}',
          }, 
          success: function(response) {

            if(response.status=='success')
            {
              $('#edit_form_modal .name').val(response.data.name);
              $('#edit_form_modal .abbreviation').val(response.data.abbreviation);
              $('#edit_form_modal .title').val(response.data.title);
              $('#edit_form_modal .description').val(response.data.description);
              $('#edit_form_modal .order').val(response.data.order);

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
        url: "/settings/category_data",
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
        url: "/settings/category_data",
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
