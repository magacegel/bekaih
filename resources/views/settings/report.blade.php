@extends('layouts.master')

@section('content')


<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Report Settings
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Report Settings</li>
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

          <div class="data_area">

          </div>


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
  .data_area {
    width: 90%;
    margin:0 auto;
  }
  .data_area .ship_type {
    font-size: 18px;
    font-weight: bold;
    background: #fff2f2;
    padding:5px 15px;
    border-radius: 8px;
    margin:6px;
    border:1px solid #fde5e5;
  }
  .data_area .category {
    font-size: 14px;
    font-weight: bold;
    background: #f2f2ff;
    padding:5px 15px;
    border-radius: 8px;
    margin:6px;
    border:1px solid #e8e8ff;
  }
  .data_area .form_type {
    font-size: 14px;
    background: #FEFEFE;
    padding:10px 10px 10px 25px;
    border-radius: 8px;
    margin:6px;
    border:1px solid #E7E7E7;
  }
  .data_area .form_type:hover {
    background: #FAFAFA;
    border-color: #E7E7E7;
  }
</style>

<div class="modal fade" id="create_form_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Item</h4>

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

                <select name="ship_type" class="form-control ship_type" style="display: inline-block;">
                  <?php foreach($ship_types as $ship_type){?>
                    <option value="<?=$ship_type->id;?>"><?=$ship_type->type;?></option>
                  <?php }?>
                </select>                


              </td>
            </tr>


            <tr class="tr_add_new">
              <td class="align-middle">Category</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="category" class="form-control category" style="display: inline-block;">
                  <?php foreach($categories as $category){?>
                    <option value="<?=$category->id;?>"><?=$category->name;?></option>
                  <?php }?>
                </select>                


              </td>
            </tr>




            <tr class="tr_add_new">
              <td class="align-middle">Form Type</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="form_type" class="form-control form_type" style="display: inline-block;">
                  <?php foreach($form_types as $form_type){?>
                    <option value="<?=$form_type->id;?>"><?=$form_type->name;?></option>
                  <?php }?>
                </select>                


              </td>
            </tr>




          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Item</button>
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

    show_table();


    $('.add_form_btn').click(function(e){
      e.preventDefault();

      $('#create_form_modal input[type=text]').val('');
      $('#create_form_modal').modal('show');
    });





    $(".b_delete").click(function(event){
      event.preventDefault();     

      form_count = $(this).attr('form_count');
      if(form_count > 0)
      {
        s=form_count>1?'s':'';
        text = 'There are '+form_count+' form'+s+' in this report.\nDo you really want to delete this Report?';
      }
      else
      {
        text = 'Do you really want to delete this Report?\nThis process can not be undone!';
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







    $('#create_form').submit(function(e){
      e.preventDefault();
      // $('.page-loader-wrapper').show();

      $.ajax({
        url: "/settings/report_data",
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
              // Refresh table
            show_table();
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






  });
  function show_table()
  {


    $.ajax({
      url: "/settings/report_data",
      type: 'post',
      dataType: "json",
      data: {
        "action":'list',
        "_token":'{{ csrf_token() }}',
      }, 
      success: function(response) {

        if(response.status == 'success')
        {

          $('.data_area .data_line').remove();
          $.each(response.data, function (i, data) {


            if(data.type == "ship_type")
            {
              $('.data_area').append('<div class="data_line ship_type">'+data.title+'</div>')
            }
            if(data.type == "category")
            {
              $('.data_area').append('<div class="data_line category">'+data.title+'</div>')
            }
            if(data.type == "form_type")
            {
              $('.data_area').append('<div class="data_line form_type">'+data.title+' &nbsp;       <button type="button" class="btn btn-danger btn-sm b_delete" onClick="delete_item(\''+data.id+'\')" title="Delete" style="float:right;margin-top:-3px;"><i class="fa fa-trash" aria-hidden="true"></i></button></div>')
            }

          });

        }

      },
      error: function(d) {
        alert('error');
      },
    });


  }

  function delete_item(id)
  {
    text = 'Do you really want to delete this Item?\nThis process can not be undone!';

    if(confirm(text))
    {
      $.ajax({
        url: "<?=url('/settings/report_data')?>",
        type: 'post',
        dataType: "json",
        data: {
          "action":'delete',
          "ship_type_category_id":id,
          "_token":'{{ csrf_token() }}',
        }, 
        success: function(data) {

          if(data.status=='success')
          {
            iziToast.success({position: "topRight", title: 'Success', message: data.message});
            show_table();
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

  }
</script>

@endsection
