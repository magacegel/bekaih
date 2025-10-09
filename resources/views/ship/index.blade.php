@extends('layout.default')

@section('mainframe')

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h3>Ship</h3>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{url('administrator')}}">Dashboard</a></div>
        <div class="breadcrumb-item">Ship</div>
      </div>
    </div>

    <div class="section-body">

      <div class="card">

        <div class="card-header">
          <div class="col-10 mr-0 pr-0">
            <h3 class="card-title" style="inline-block"></h3>
          </div>
          <div class="col-2 d-inline">


            <a href="#" class="btn btn-sm btn-primary add_ship_btn">
              <i class="fa fa-plus"></i>
              Add New
            </a>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="ship_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th style="width:20px;">No</th>
                <th>Ship Name</th>
                <th>Class Identity</th>
                <th style="width: 85px;">Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

        </div>
      </div>
    </div>
  </section>
</div>



<div class="modal fade" tabindex="-1" id="create_ship_modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Add New Ship</h3>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Close-->
      </div>

      <div class="modal-body">

        <form id="create_ship" action="<?=url('ship_data');?>" method="POST">


          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="create">


            <tr>
              <td class="align-middle">Ship Name</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="ship_name" class="form-control ship_name">
              </td>
            </tr>

            <tr>
              <td class="align-middle">Class Identity</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="class_identity" class="form-control class_identity">
              </td>
            </tr>
          </table>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary create_ship_btn">Create Ship</button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('css')


@endsection
@section('js')


<script type="text/javascript">
  $(document).ready(function(){



    $('.add_ship_btn').click(function(e){
      e.preventDefault();

      $('#create_ship_modal').modal('show');

    });



    $('.create_ship_btn').click(function(e){
      e.preventDefault();


      next = true;
      if(!$('.ship_name').val())
      {
        alert('Please insert ship name!');
        $('.ship_name').focus();
        next = false;
      }
      else if(!$('.class_identity').val())
      {
        alert('Please insert class identity!');
        $('.class_identity').focus();
        next = false;
      }


      if(next)
      {

        form_data = $('#create_ship').serializeArray();

        $.ajax({
          url: "<?=url('/ship_data')?>",
          type: 'post',
          dataType: "json",
          data: form_data, 
          success: function(data) {

            if(data.status=='success')
            {

              iziToast.success({position: "topRight", title: 'Success', message: data.message});


              $('#ship_table').DataTable().ajax.reload();

              $('.ship_name').val('');
              $('.class_identity').val('');

              $('#create_ship_modal').modal('hide');
            }

          },
          error: function(d) {
            alert('error');
          },
        });




      }
    });


    $('#ship_table').DataTable({
      processing: true,
      serverSide: false,
      ajax: '<?=url('ship_datatables');?>',
      columns: [      
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },  
        { data: 'name', name: 'name'}, 
        { data: 'class_identity', name: 'class_identity' },
        { data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });


  // Harus menunggu data-tables di load dulu, baru jQuery bisa jalan  
    $('#ship_table').on( 'draw.dt', function () {

      $('.b_detail, .b_edit, .b_delete').tooltip({show: {effect:"none", delay:0}});

      // $(".b_detail").click(function(event){
      //   event.preventDefault();     
      //   window.location.href = '{{URL::to('/') }}/ship_detail/'+$(this).attr('id');
      // });        

      $(".b_delete").click(function(event){
        event.preventDefault();     

        report_count = $(this).attr('report_count');
        if(report_count > 0)
        {
          s=report_count>1?'s':'';
          text = 'There are '+report_count+' report'+s+' in this ship.\nDo you really want to delete this ship?';
        }
        else
        {
          text = 'Do you really want to delete this ship?';
        }
        if(confirm(text))
        {
          ship_id = $(this).attr('id');

          $.ajax({
            url: "<?=url('/ship_data')?>",
            type: 'post',
            dataType: "json",
            data: {
              "action":'delete',
              "ship_id":ship_id,
              "_token":'{{ csrf_token() }}',
            }, 
            success: function(data) {

              if(data.status=='success')
              {

                iziToast.success({position: "topRight", title: 'Success', message: data.message});

                $('#ship_table').DataTable().ajax.reload();
              }

            },
            error: function(d) {
              alert('error');
            },
          });

        }

      });        

    });


  });
</script>

@endsection
