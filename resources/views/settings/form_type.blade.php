@extends('layouts.master')

@section('content')


<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        Form Type Management
      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Form Type Management</li>
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
                <th>Form Data Format</th>
                <th>Unit Type</th>
                <th>Unit Prefix</th>
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
    border: 1px solid #222;
  }
  .preview_txt {
    color: red;
    font-weight: bold;
  }
  .image_popup {
    margin-bottom: 15px;
  }
  .preview_three {
    width: 1090px;
    overflow-x: auto ;
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
      <div class="modal-body" style="padding-bottom: 0px;">
        <table class="table modal_table">
          <tr>
            <td class="align-middle" style="width:190px">Form Data Format</td>
            <td class="align-middle" style="width:12px;">:</td>
            <td>
              <select name="data_format" class="form-control data_format" style="width:60%;">
                <option value="">---</option>
                <option value="one">Format one</option>
                <option value="two">Format two</option>
                <option value="three">Format three</option>
              </select>
            </td>
          </tr>
        </table>
      </div>

      <form id="create_form_one" action="<?=url('form_type_data');?>" method="POST" style="display: none;">
        <div class="modal-body">

          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="form_data_format" class="form_data_format" value="one">


            <tr class="tr_add_new">
              <td colspan="3">
                <div class="image_popup">
                  <img src="/images/image_one.png" style="width: 100%;">
                </div>
              </td>
            </tr>


            <tr>
              <td class="align-middle" style="width:190px">Form Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="form_title" class="form-control form_title">
              </td>
            </tr>

            <tr class="tr_add_new">
              <td class="align-middle" style="width:190px">Unit Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="unit_type" class="form-control unit_type" style="width:120px;display: inline-block;">
                  <option value="free_text">Free Text</option>
                  <option value="plate">Plate</option>
                  <option value="prefix">Prefix</option>
                </select>

                <input type="text" name="unit_prefix" class="form-control unit_prefix" placeholder="" style="width:200px;display: inline-block;">

              </td>
            </tr>


          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Form</button>
        </div>
      </form>


      <form id="create_form_two" action="<?=url('form_type_data');?>" method="POST" style="display: none;">
        <div class="modal-body">

          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="form_data_format" class="form_data_format" value="two">


            <tr class="tr_add_new">
              <td colspan="3">
                <div class="image_popup">
                  <img src="/images/image_two.png" style="width: 100%;">
                </div>
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Form Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="form_title" class="form-control form_title">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Unit Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="unit_title" class="form-control unit_title">
              </td>
            </tr>

            <tr class="tr_add_new">
              <td class="align-middle">Number Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="number_wording" class="form-control number_wording" placeholder="No or Letter">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Gauged P Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="gauged_p_title" class="form-control gauged_p_title" placeholder="Port">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Gauged S Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="gauged_s_title" class="form-control gauged_s_title" placeholder="Starboard">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Dimunition P Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="dim_p_title" class="form-control dim_p_title" placeholder="Diminution P">
              </td>
            </tr>


            <tr>
              <td class="align-middle" style="width:190px">Dimunition S Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="dim_s_title" class="form-control dim_s_title" placeholder="Diminution S">
              </td>
            </tr>


            <tr class="tr_add_new">
              <td class="align-middle" style="width:190px">Unit Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="unit_type" class="form-control unit_type" style="width:120px;display: inline-block;">
                  <option value="free_text">Free Text</option>
                  <option value="plate">Plate</option>
                  <option value="prefix">Prefix</option>
                </select>

                <input type="text" name="unit_prefix" class="form-control unit_prefix" placeholder="" style="width:200px;display: inline-block;">

              </td>
            </tr>


          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Form</button>
        </div>
      </form>


      <form id="create_form_three" action="<?=url('form_type_data');?>" method="POST" style="display: none;">
        <div class="modal-body">

          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="form_data_format" class="form_data_format" value="three">


            <tr class="tr_add_new">
              <td colspan="3">
                <div class="image_popup">
                  <img src="/images/image_three.png" style="width: 100%;">
                </div>
              </td>
            </tr>


            <tr>
              <td class="align-middle" style="width:190px">Form Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="form_title" class="form-control form_title">
              </td>
            </tr>

            <tr class="tr_add_new">
              <td class="align-middle">Number Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="number_wording" class="form-control number_wording" placeholder="No or Letter">
              </td>
            </tr>


            <tr class="tr_add_new">
              <td class="align-middle" style="width:250px">Unit Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="unit_type" class="form-control unit_type" style="width:150px;display: inline-block;">
                  <option value="strake">Default Strake</option>
                  <option value="free_text">Free Text</option>
                  <option value="plate">Plate</option>
                  <option value="prefix">Prefix</option>
                </select>

                <input type="text" name="unit_prefix" class="form-control unit_prefix" placeholder="" style="width:200px;display: inline-block;">

              </td>
            </tr>


          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Form</button>
        </div>
      </form>

<!-- 
      <form id="create_form_one" action="<?=url('form_type_data');?>" method="POST" style="display: none;">
        <div class="modal-body">

          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="form_data_format" class="form_data_format" value="one">


            <tr class="tr_add_new">
              <td colspan="3">
                <div class="image_popup">
                  <img src="/images/image_one.png" style="width: 100%;">
                </div>
              </td>
            </tr>


            <tr>
              <td class="align-middle" style="width:190px">Form Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="form_title" class="form-control form_title">
              </td>
            </tr>

            <tr class="tr_add_new">
              <td class="align-middle" style="width:190px">Unit Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="unit_type" class="form-control unit_type" style="width:120px;display: inline-block;">
                  <option value="free_text">Free Text</option>
                  <option value="plate">Plate</option>
                  <option value="prefix">Prefix</option>
                </select>

                <input type="text" name="unit_prefix" class="form-control unit_prefix" placeholder="" style="width:200px;display: inline-block;">

              </td>
            </tr>


          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Form</button>
        </div>
      </form>
    -->




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


      <form id="edit_form_one" action="<?=url('form_type_data');?>" method="POST" style="display: none;">
        <div class="modal-body">

          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="form_type_id" class="form_type_id" value="">
            <input type="hidden" name="form_data_format" class="form_data_format" value="one">

            <tr class="tr_add_new">
              <td colspan="3">
                <div class="image_popup">
                  <img src="/images/image_one.png" style="width: 100%;">
                </div>
              </td>
            </tr>


            <tr>
              <td class="align-middle" style="width:190px">Form Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="form_title" class="form-control form_title">
              </td>
            </tr>

            <tr class="tr_add_new">
              <td class="align-middle" style="width:190px">Unit Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="unit_type" class="form-control unit_type" style="width:120px;display: inline-block;">
                  <option value="free_text">Free Text</option>
                  <option value="plate">Plate</option>
                  <option value="prefix">Prefix</option>
                </select>

                <input type="text" name="unit_prefix" class="form-control unit_prefix" placeholder="" style="width:200px;display: inline-block;">

              </td>
            </tr>


          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Form</button>
        </div>
      </form>

      <form id="edit_form_two" action="<?=url('form_type_data');?>" method="POST" style="display: none;">
        <div class="modal-body">

          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="form_type_id" class="form_type_id" value="">
            <input type="hidden" name="form_data_format" class="form_data_format" value="two">


            <tr class="tr_add_new">
              <td colspan="3">
                <div class="image_popup">
                  <img src="/images/image_two.png" style="width: 100%;">
                </div>
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Form Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="form_title" class="form-control form_title">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Unit Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="unit_title" class="form-control unit_title">
              </td>
            </tr>

            <tr class="tr_add_new">
              <td class="align-middle">Number Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="number_wording" class="form-control number_wording" placeholder="No or Letter">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Gauged P Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="gauged_p_title" class="form-control gauged_p_title" placeholder="Port">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Gauged S Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="gauged_s_title" class="form-control gauged_s_title" placeholder="Starboard">
              </td>
            </tr>

            <tr>
              <td class="align-middle" style="width:190px">Dimunition P Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="dim_p_title" class="form-control dim_p_title" placeholder="Diminution P">
              </td>
            </tr>


            <tr>
              <td class="align-middle" style="width:190px">Dimunition S Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="dim_s_title" class="form-control dim_s_title" placeholder="Diminution S">
              </td>
            </tr>



            <tr class="tr_add_new">
              <td class="align-middle" style="width:190px">Unit Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="unit_type" class="form-control unit_type" style="width:120px;display: inline-block;">
                  <option value="free_text">Free Text</option>
                  <option value="plate">Plate</option>
                  <option value="prefix">Prefix</option>
                </select>

                <input type="text" name="unit_prefix" class="form-control unit_prefix" placeholder="" style="width:200px;display: inline-block;">

              </td>
            </tr>


          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Form</button>
        </div>
      </form>


      <form id="edit_form_three" action="<?=url('form_type_data');?>" method="POST" style="display: none;">
        <div class="modal-body">

          <table class="table modal_table">
            <input type="hidden" name="_token" value="<?=csrf_token();?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="form_type_id" class="form_type_id" value="">
            <input type="hidden" name="form_data_format" class="form_data_format" value="three">

            <tr class="tr_add_new">
              <td colspan="3">
                <div class="image_popup">
                  <img src="/images/image_three.png" style="width: 100%;">
                </div>
              </td>
            </tr>


            <tr>
              <td class="align-middle" style="width:190px">Form Title</td>
              <td class="align-middle" style="width:12px;">:</td>
              <td>
                <input type="text" name="form_title" class="form-control form_title">
              </td>
            </tr>

            <tr class="tr_add_new">
              <td class="align-middle">Number Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <input type="text" name="number_wording" class="form-control number_wording" placeholder="No or Letter">
              </td>
            </tr>


            <tr class="tr_add_new">
              <td class="align-middle" style="width:190px">Unit Wording</td>
              <td class="align-middle" style="width:2px;">:</td>
              <td>
                <select name="unit_type" class="form-control unit_type" style="width:150px;display: inline-block;">
                  <option value="strake">Default Strake</option>
                  <option value="free_text">Free Text</option>
                  <option value="plate">Plate</option>
                  <option value="prefix">Prefix</option>
                </select>

                <input type="text" name="unit_prefix" class="form-control unit_prefix" placeholder="" style="width:200px;display: inline-block;">

              </td>
            </tr>


          </table>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Form</button>
        </div>
      </form>

    </div>
  </div>
</div>




<div class="modal fade" id="preview_modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Preview Form</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>


      <div class="modal-body">


        <div class="preview_div preview_one" style="display: none;">


          <table class="form_table" border="1">
            <thead>
              <tr>
                <th>
                  <span class="preview_txt">STRAKE POSITION</span>
                </th>
                <th colspan="18" class="form_title">
                  <span class="preview_txt name"></span> - XXXX

                </th>
                <th rowspan="4" colspan="2">Max<br>Allwb.<br>Dim. mm</th>
              </tr>
              <tr>
                <th rowspan="3"><span class="preview_txt unit_title"></span></th>
                <th rowspan="3">No<br>or<br>Letter</th>
                <th rowspan="3">Org.<br>Thk.<br>mm</th>
                <th colspan="7"><span class="preview_txt left_title"></span></th>
                <th colspan="7"><span class="preview_txt right_title"></span></th>
                <th colspan="2">Mean Dim %</th>
              </tr>
              <tr>

                <th colspan="2">GAUGED</th>
                <th rowspan="2">Pmsb<br>Dim Lvl</th>
                <th colspan="2">Dimunition S</th>
                <th colspan="2">Dimunition P</th>

                <th colspan="2">GAUGED</th>
                <th rowspan="2">Pmsb<br>Dim Lvl</th>
                <th colspan="2">Dimunition S</th>
                <th colspan="2">Dimunition P</th>

                <th rowspan="2">S</th>
                <th rowspan="2">P</th>

              </tr>
              <tr>
                <th>S</th>
                <th>P</th>
                <th>mm</th>
                <th>%</th>
                <th>mm</th>
                <th>%</th>

                <th>S</th>
                <th>P</th>
                <th>mm</th>
                <th>%</th>
                <th>mm</th>
                <th>%</th>

              </tr>

            </thead>
            <tbody>
              <?php $x=1;foreach($plates['unit_position_text'] as $plate){?>
                <tr>
                  <td>

                    <span class="free_text" style="display:none;">&nbsp;</span>
                    <span class="number_plate" style="display:none;"><?=$plate;?></span>
                    <span class="preview_txt number_prefix" style="display:none;"></span><span class="number" style="display:none;"><?=$x;?></span>
                    <span class="strake" style="display:none;">zzzzzzzzzzz</span>
                  </td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                <?php 
                $x++;
              }?>
            </tbody>
          </table>
        </div>


        <div class="preview_div preview_two" style="display: none;">


          <table class="form_table" border="1">
            <thead>
              <tr>
                <th colspan="11" class="text-left">
                  <span class="preview_txt name"></span> - XXXX
                </th>
              </tr>
              <tr>
                <th colspan="11" class="text-left">
                  Additional Form Title 
                </th>
              </tr>
              <tr>
                <th rowspan="2"><span class="preview_txt unit_title"></span></th>                    
                <th rowspan="2"><span class="preview_txt number_wording"></span></th>
                <th rowspan="2">ORG THK (mm)</th>
                <th rowspan="2">MIN. THK (mm)</th>
                <th colspan="2">Gauged</th>
                <th rowspan="2">Diminution Level</th>
                <th colspan="2"><span class="preview_txt dim_p_title"></span></th>
                <th colspan="2"><span class="preview_txt dim_s_title"></span></th>

              </tr>
              <tr>

                <th><span class="preview_txt gauged_p_title"></span></th>
                <th><span class="preview_txt gauged_s_title"></span></th>
                <th>mm</th>
                <th>%</th>
                <th>mm</th>
                <th>%</th>

              </tr>


            </thead>



<tbody>
  <?php $x=1;foreach($plates['unit_position_text'] as $plate){?>
    <tr>
      <td>
        <span class="free_text" style="display:none;">&nbsp;</span>
        <span class="number_plate" style="display:none;"><?=$plate;?></span>
        <span class="preview_txt number_prefix" style="display:none;"></span><span class="number" style="display:none;"><?=$x;?></span>
        <span class="strake" style="display:none;">xxxxxx</span>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>

    <?php 
    $x++;
  }?>
</tbody>
</table>
</div>


<div class="preview_div preview_three" style="display: none;">


  <table class="form_table" border="1">
    <thead>
      <tr>
        <th colspan="31" class="form_title" style="border-right:none;">
          <span class="preview_txt name"></span> - XXXX
        </th>
      </tr>
      <tr>
        <th rowspan="3">
          STRAKE POSITION
        </th>
        <th colspan="10">
          Form Title 1
        </th>
        <th colspan="10">
          Form Title 2
        </th>
        <th colspan="10">
          Form Title 3
        </th>
      </tr>
      <tr>
        <th rowspan="3" class="rotate"><span class="preview_txt number_wording"></span></th>
        <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
        <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
        <th colspan="2">Gauged</th>
        <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
        <th colspan="2">Diminution<br>P</th>
        <th colspan="2">Diminution<br>S</th>

        <th rowspan="3" class="rotate"><span class="preview_txt number_wording"></span></th>
        <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
        <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
        <th colspan="2">Gauged</th>
        <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
        <th colspan="2">Diminution<br>P</th>
        <th colspan="2">Diminution<br>S</th>

        <th rowspan="3" class="rotate"><span class="preview_txt number_wording"></span></th>
        <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
        <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
        <th colspan="2">Gauged</th>
        <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
        <th colspan="2">Diminution<br>P</th>
        <th colspan="2">Diminution<br>S</th>
      </tr>
      <tr>

        <th>P</th>
        <th>S</th>
        <th>mm</th>
        <th>%</th>
        <th>mm</th>
        <th>%</th>

        <th>P</th>
        <th>S</th>
        <th>mm</th>
        <th>%</th>
        <th>mm</th>
        <th>%</th>

        <th>P</th>
        <th>S</th>
        <th>mm</th>
        <th>%</th>
        <th>mm</th>
        <th>%</th>


      </tr>


    </thead>
    <tbody>
      <?php $x=1;foreach($plates['unit_position_text'] as $plate){?>
        <tr>
          <td>
            <span class="free_text" style="display:none;">&nbsp;</span>
            <span class="number_plate" style="display:none;"><?=$plate;?></span>
            <span class="preview_txt number_prefix" style="display:none;"></span><span class="number" style="display:none;"><?=$x;?></span>
            <span class="strake" style="display:none;">
              <?php if($x==count($plates['unit_position_text'])-1){ ?>
                Centre Strake
              <?php }else if($x==count($plates['unit_position_text'])){ ?>
                Sheer Strake
              <?php }else{ ?>
                <?=ordinal($x);?> Strake Inboard
              <?php } ?>
            </span>
          </td>
          <?php for($z=1;$z<=30;$z++) {?>
            <td></td>
          <?php } ?>

        </tr>

        <?php 
        $x++;
      }?>
    </tbody>
  </table>
</div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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

<?php 
$numbers = ['one','two','three'];
?>
<script src="assets/modules/select2/dist/js/select2.js"></script>

<script type="text/javascript">
  $(document).ready(function(){


    function check_prefix(id)
    {
      unit_type = $('#'+id+' .unit_type').val();
      if(unit_type == 'plate' || unit_type == 'free_text' || unit_type == 'strake')
      {
        $('#'+id+' .unit_prefix').hide();
      }
      else
      {
        $('#'+id+' .unit_prefix').show();
      }
    }

    $('.data_format').change(function(e){
      e.preventDefault();
      if($(this).val() == 'one')
      {
        $('#create_form_one').show();
        $('#create_form_two').hide();
        $('#create_form_three').hide();
      }
      if($(this).val() == 'two')
      {
        $('#create_form_one').hide();
        $('#create_form_two').show();
        $('#create_form_three').hide();
      }
      if($(this).val() == 'three')
      {
        $('#create_form_one').hide();
        $('#create_form_two').hide();
        $('#create_form_three').show();
      }

    });

    function check_format(id)
    {
      unit_type = $('#'+id+' .form_data_format').val();
      if(unit_type == 'one')
      {
        $('#'+id+' .center_title').parent().parent().hide();
      }
      else
      {
        $('#'+id+' .center_title').parent().parent().show();
      }
    }

    $('#create_form_modal .form_data_format').change(function(e){
      check_format('create_form_modal');
    });

    $('#create_form_modal .unit_type').change(function(e){
      check_prefix('create_form_modal');
    });



    <?php foreach ($numbers as $number) { ?>
      $('#edit_form_<?=$number;?> .unit_type').change(function(e){
        check_prefix('edit_form_<?=$number;?>');
      });
    <?php } ?>


    $('.add_form_btn').click(function(e){
      e.preventDefault();

      $('#create_form_modal input[type=text]').val('');
      $('#create_form_modal').modal('show');
      check_prefix('create_form_modal');
      check_format('create_form_modal');
    });



    $('#my_table').DataTable({
      processing: true,
      serverSide: false,
      ajax: '<?=url('settings/form_type_datatables');?>',
      columns: [      
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },  
        { data: 'name', name: 'name' },
        { data: 'form_data_format', name: 'form_data_format' },
        { data: 'unit_type', name: 'unit_type' },
        { data: 'unit_prefix', name: 'unit_prefix' },
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
        width: '204px',
      },
      ],
    });


    // Harus menunggu data-tables di load dulu, baru jQuery bisa jalan  
    $('#my_table').on( 'draw.dt', function () {

      $('.b_detail, .b_edit, .b_preview, .b_delete').tooltip({show: {effect:"none", delay:0}});

      $(".b_preview").click(function(event){
        event.preventDefault();

        id = $(this).attr('id');

        $.ajax({
          url: "<?=url('/settings/form_type_data')?>",
          type: 'post',
          dataType: "json",
          data: {
            "action":'detail',
            "form_type_id":id,
            "_token":'{{ csrf_token() }}',
          }, 
          success: function(response) {

            if(response.status=='success')
            {

              if(response.data.form_data_format == 'one')
              {
                $('.preview_one').show();
                $('.preview_two').hide();
                $('.preview_three').hide();
              }
              else if(response.data.form_data_format == 'two')
              {
                $('.preview_one').hide();
                $('.preview_two').show();
                $('.preview_three').hide();
              }
              else if(response.data.form_data_format == 'three')
              {
                $('.preview_one').hide();
                $('.preview_two').hide();
                $('.preview_three').show();
              }


              $('.preview_'+response.data.form_data_format+' .name').html(response.data.name);
              $('.preview_'+response.data.form_data_format+' .unit_title').html(response.data.unit_title);
              $('.preview_'+response.data.form_data_format+' .number_wording').html(response.data.number_wording);
              $('.preview_'+response.data.form_data_format+' .dim_p_title').html(response.data.dim_p_title);
              $('.preview_'+response.data.form_data_format+' .dim_s_title').html(response.data.dim_s_title);
              $('.preview_'+response.data.form_data_format+' .gauged_p_title').html(response.data.gauged_p_title);
              $('.preview_'+response.data.form_data_format+' .gauged_s_title').html(response.data.gauged_s_title);



              if(response.data.unit_type == 'prefix')
              {
                $('.preview_'+response.data.form_data_format+' .strake').hide();
                $('.preview_'+response.data.form_data_format+' .number_plate').hide();
                $('.preview_'+response.data.form_data_format+' .number_prefix').show();
                $('.preview_'+response.data.form_data_format+' .number').show();
                $('.preview_'+response.data.form_data_format+' .free_text').hide();

                $('.preview_'+response.data.form_data_format+' .number_prefix').html(response.data.unit_prefix);
              }
              if(response.data.unit_type == 'plate')
              {
                $('.preview_'+response.data.form_data_format+' .strake').hide();
                $('.preview_'+response.data.form_data_format+' .number_plate').show();
                $('.preview_'+response.data.form_data_format+' .number_prefix').hide();
                $('.preview_'+response.data.form_data_format+' .number').hide();
                $('.preview_'+response.data.form_data_format+' .free_text').hide();
              }
              if(response.data.unit_type == 'free_text')
              {
                $('.preview_'+response.data.form_data_format+' .strake').hide();
                $('.preview_'+response.data.form_data_format+' .number_plate').hide();
                $('.preview_'+response.data.form_data_format+' .number_prefix').hide();
                $('.preview_'+response.data.form_data_format+' .number').hide();
                $('.preview_'+response.data.form_data_format+' .free_text').show();
              }
              if(response.data.unit_type == 'strake')
              {
                $('.preview_'+response.data.form_data_format+' .strake').show();
                $('.preview_'+response.data.form_data_format+' .number_plate').hide();
                $('.preview_'+response.data.form_data_format+' .number_prefix').hide();
                $('.preview_'+response.data.form_data_format+' .number').hide();
                $('.preview_'+response.data.form_data_format+' .free_text').hide();
              }

              $('#preview_modal').modal('show');

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


$(".b_delete").click(function(event){
  event.preventDefault();     

  form_count = $(this).attr('form_count');
  if(form_count > 0)
  {
    s=form_count>1?'s':'';
    text = 'There are '+form_count+' form'+s+' in this report.\nDo you really want to delete this form?';
  }
  else
  {
    text = 'Do you really want to delete this form?\nThis process can not be undone!';
  }
  if(confirm(text))
  {
    form_type_id = $(this).attr('id');

    $.ajax({
      url: "<?=url('/settings/form_type_data')?>",
      type: 'post',
      dataType: "json",
      data: {
        "action":'delete',
        "form_type_id":form_type_id,
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



  $.ajax({
    url: "<?=url('/settings/form_type_data')?>",
    type: 'post',
    dataType: "json",
    data: {
      "action":'detail',
      "form_type_id":id,
      "_token":'{{ csrf_token() }}',
    }, 
    success: function(response) {

      if(response.status=='success')
      {

        $('#edit_form_modal .name').val(response.data.name);

        if(response.data.form_data_format == 'one')
        {
          $('#edit_form_one').show();
          $('#edit_form_two').hide();
          $('#edit_form_three').hide();

          $('#edit_form_one .form_title').val(response.data.name);
                // $('#edit_form_one .number_wording').val(response.data.unit_title);
          $('#edit_form_one .unit_type').val(response.data.unit_type);
          $('#edit_form_one .unit_prefix').val(response.data.unit_prefix);

          $('#edit_form_one .form_type_id').val(id);
          check_prefix('edit_form_one');

        }

        if(response.data.form_data_format == 'two')
        {

          $('#edit_form_one').hide();
          $('#edit_form_two').show();
          $('#edit_form_three').hide();

          $('#edit_form_two .form_title').val(response.data.name);
          $('#edit_form_two .unit_title').val(response.data.unit_title);
          $('#edit_form_two .number_wording').val(response.data.number_wording);
          $('#edit_form_two .gauged_p_title').val(response.data.gauged_p_title);
          $('#edit_form_two .gauged_s_title').val(response.data.gauged_s_title);
          $('#edit_form_two .dim_p_title').val(response.data.dim_p_title);
          $('#edit_form_two .dim_s_title').val(response.data.dim_s_title);

          $('#edit_form_two .unit_type').val(response.data.unit_type);
          $('#edit_form_two .unit_prefix').val(response.data.unit_prefix);

          $('#edit_form_two .form_type_id').val(id);
          check_prefix('edit_form_two');

        }

        if(response.data.form_data_format == 'three')
        {
          $('#edit_form_one').hide();
          $('#edit_form_two').hide();
          $('#edit_form_three').show();

          $('#edit_form_three .form_title').val(response.data.name);
          $('#edit_form_three .number_wording').val(response.data.number_wording);
          $('#edit_form_three .unit_type').val(response.data.unit_type);
          $('#edit_form_three .unit_prefix').val(response.data.unit_prefix);

          $('#edit_form_three .form_type_id').val(id);
          check_prefix('edit_form_three');

        }

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







<?php foreach ($numbers as $number) { ?>


  $('#edit_form_<?=$number;?>').submit(function(e){
    e.preventDefault();
    $.ajax({
      url: "/settings/form_type_data",
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false,

      success: function(data) {

        if(data.status == 'success')
        {
          iziToast.success({position: "topRight", title: 'Success', message: data.message});
          $('#edit_form_modal').modal('hide');
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
<?php }?>

});




<?php foreach ($numbers as $number) { ?>


  $('#create_form_<?=$number;?>').submit(function(e){
    e.preventDefault();

    $.ajax({
      url: "/settings/form_type_data",
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false,

      success: function(data) {

        if(data.status == 'success')
        {
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

<?php } ?>

});
</script>

@endsection
