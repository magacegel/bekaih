@extends('layouts.master')

@section('content')


<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">
        <?=$report->ship->name;?>
        &nbsp;
        &nbsp;
        &nbsp;
        <span class="ship_type">
          (<?=$ship_type->type ?? '-'; ?>)
        </span>

      </h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="{{url('administrator')}}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{url('report')}}">Report</a></li>
          <li class="breadcrumb-item"><a href="<?=url('/report_detail');?>/<?=base64_encode($report->id);?>">Report Detail</a></li>
          <li class="breadcrumb-item active"><?=$form ? 'Update':'Create';?> Form</li>
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

        <div class="card-body table-responsive">

          <?php

          $form_data_format = $form->form_type->form_data_format ?? '';


          if($form_data_format == 'one'){ ?>
            <form id="form_data">

              <input type="hidden" name="_token" value="<?=csrf_token();?>">
              <input type="hidden" name="category_id" value="<?=$category->id;?>">
              <input type="hidden" name="report_id" value="<?=$report->id;?>">
              <input type="hidden" name="total_line" value="<?=$total_line;?>">
              <input type="hidden" name="action" value="<?=$form ? 'edit':'create';?>">
              <input type="hidden" name="tab" value="<?=$_GET['tab'] ?? '';?>">
              <?php if($form){?>
                <input type="hidden" name="form_id" value="<?=$form->id;?>">
              <?php }?>


              <?php if($form){?>
                <a href="<?= url('/report_detail/' . base64_encode($report->id) . '?tab=' . ($_GET['tab'] ?? '') . '&form_id=' . ($form->id ?? '')); ?>" 
   class="btn btn-sm btn-secondary" style="float: right; margin-left: 10px;">
   Back
</a>

              <?php }?>

              <input type="submit" class="btn btn-sm btn-primary" style="float:right;" value="<?=$form ? 'Update':'Create';?> Form">


              <table class="padding">
                <tr>
                  <td>Default Org. Thickness</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_org_thickness" name="default_org_thickness" value="<?=$form->default_org_thickness;?>">
                  </td>
                  <td>&nbsp;</td>
                  <td>Default Min. Thickness</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_min_thickness" name="default_min_thickness" value="<?=$form->default_min_thickness;?>">
                  </td>
                  <td>&nbsp;</td>
                  <td>Default Dimunition Level</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_dim_lvl" name="default_dim_lvl" value="<?=$form->default_dim_lvl;?>">
                  </td>
                </tr>
              </table>


              <table class="header_table">
                <tr>
                  <td class="bki_logo_td">
                    <div class="bki_logo">
                    </div>
                  </td>
                  <td class="bki_title_td">PT. Biro Klasifikasi<br>Indonesia</td>
                  <td class="form_title_td" colspan="2">
                    <?=$report->name;?>
                  </td>
                </tr>
              </table>

              <table class="title_table">
                <tr>
                  <td class="sub_title">Ship's Name :</td>
                  <td class="bold"><?=$report->ship->name;?></td>
                  <td class="sub_title">Class Identity No. :</td>
                  <td class="bold"><?=$report->ship->class_identity;?></td>

                  <td class="sub_title">Report No. :</td>
                  <td class="bold"><?=$report->report_number;?></td>

                </tr>
              </table>


              <table class="form_table table_one" border="1">


                <thead>
                  <tr>
                    <th>STRAKE POSITION</th>
                    <th colspan="19" class="form_title">
                      <?=$form->name;?>
                    </th>
                  </tr>
                  <tr>
                    <th rowspan="3">PLATE POSITION</th>
                    <th rowspan="3">No<br>or<br>Letter</th>
                    <th rowspan="2">ORG<br>THK</th>
                    <th rowspan="2">MIN<br>THK</th>
                    <th colspan="7">Aft Reading</th>
                    <th colspan="7">Forward Reading</th>
                    <th colspan="2">Mean Dimunition %</th>
                  </tr>
                  <tr>

                    <th colspan="2">Gauged</th>
                    <th rowspan="2">Dimunition<br>Level</th>
                    <th colspan="2">Diminution P</th>
                    <th colspan="2">Diminution S</th>

                    <th colspan="2">Gauged</th>
                    <th rowspan="2">Dimunition<br>Level</th>
                    <th colspan="2">Diminution P</th>
                    <th colspan="2">Diminution S</th>

                    <th rowspan="2">P</th>
                    <th rowspan="2">S</th>

                  </tr>
                  <tr>
                    <th>mm</th>
                    <th>mm</th>
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




                  <?php
                  $form_data = $form ? $form->form_data_one->keyBy('plate_position')->toArray() : [];
                  $unit_type = $form->form_type->unit_type;

                  $idx = 0;
                  foreach ($unit['unit_position'] as $position)
                  {
                    $line = $unit['unit_position_input'][$position];
                    $data = $form_data[$position] ?? [];

                    ?>

                    <tr class="<?=$line;?>" line="<?=$line;?>">
                      <?php if($unit_type == 'free_text') {?>
                        <td><input type="text" class="input_others position_txt" name="position_txt_<?=$line;?>" value="<?=$data['position_txt'] ?? '';?>"></td>
                      <?php } else { ?>
                        <td><?=$unit['unit_position_text'][$position];?></td>
                      <?php } ?>


                      <td><input type="text" id="cell-{{$idx}}-0" class="input_form no_letter" name="no_letter_<?=$line;?>" value="<?=$data['no_letter'] ?? '';?>"></td>
                      <td><input type="text" id="cell-{{$idx}}-1" class="input_form org_thickness" name="org_thickness_<?=$line;?>" value="<?=$data['org_thickness'] ?? '';?>"></td>
                      <td><input type="text" id="cell-{{$idx}}-2" class="input_form min_thc min_thickness" name="min_thickness_<?=$line;?>" value="<?=$data['min_thickness'] ?? '';?>" readonly></td>


                      <td><input type="text" id="cell-{{$idx}}-3" class="input_form counted aft_gauged_p" name="aft_gauged_p_<?=$line;?>" value="<?=$data['aft_gauged_p'] ?? '';?>"></td>
                      <td><input type="text" id="cell-{{$idx}}-4" class="input_form counted aft_gauged_s" name="aft_gauged_s_<?=$line;?>" value="<?=$data['aft_gauged_s'] ?? '';?>"></td>
                      <td>
                        <input type="text" id="cell-{{$idx}}-5" class="input_form aft_dim_lvl" name="aft_dim_lvl_<?=$line;?>" value="<?=$data['aft_dim_lvl'] ?? '';?>">
                        <select class="input_form aft_dim_lvl_unit" name="aft_dim_lvl_unit_<?=$line;?>">
                          <option value=""></option>
                          <option value="%" <?= isset($data['aft_dim_lvl_unit']) && $data['aft_dim_lvl_unit'] == '%' ? 'selected' : '' ?>>%</option>
                          <option value="mm" <?= isset($data['aft_dim_lvl_unit']) && $data['aft_dim_lvl_unit'] == 'mm' ? 'selected' : '' ?>>mm</option>
                        </select>
                      </td>

                      <td>
                        <input type="text" class="hidden aft_dim_p_mm" name="aft_dim_p_mm_<?=$line;?>"  value="<?=$data['aft_dim_p_mm'] ?? '';?>">
                        <span class="aft_dim_p_mm_txt"><?=$data['aft_dim_p_mm'] ?? '';?></span>
                      </td>
                      <td>
                        <input type="text" class="hidden aft_dim_p_pct" name="aft_dim_p_pct_<?=$line;?>"  value="<?=$data['aft_dim_p_pct'] ?? '';?>">
                        <span class="aft_dim_p_pct_txt"><?=isset($data['aft_dim_p_pct']) && $data['aft_dim_p_pct'] ? $data['aft_dim_p_pct'].'%' : '';?></span>
                      </td>

                      <td>
                        <input type="text" class="hidden aft_dim_s_mm" name="aft_dim_s_mm_<?=$line;?>"  value="<?=$data['aft_dim_s_mm'] ?? '';?>">
                        <span class="aft_dim_s_mm_txt"><?=$data['aft_dim_s_mm'] ?? '';?></span>
                      </td>
                      <td>
                        <input type="text" class="hidden aft_dim_s_pct" name="aft_dim_s_pct_<?=$line;?>"  value="<?=$data['aft_dim_s_pct'] ?? '';?>">
                        <span class="aft_dim_s_pct_txt"><?=isset($data['aft_dim_s_pct']) && $data['aft_dim_s_pct'] ? $data['aft_dim_s_pct'].'%' : '';?></span>
                      </td>





                      <td><input type="text" id="cell-{{$idx}}-6" class="input_form counted forward_gauged_p" name="forward_gauged_p_<?=$line;?>" value="<?=$data['forward_gauged_p'] ?? '';?>"></td>
                      <td><input type="text" id="cell-{{$idx}}-7" class="input_form counted forward_gauged_s" name="forward_gauged_s_<?=$line;?>" value="<?=$data['forward_gauged_s'] ?? '';?>"></td>

                      <td>
                        <input type="text" id="cell-{{$idx}}-8" class="input_form forward_dim_lvl" name="forward_dim_lvl_<?=$line;?>" value="<?=$data['forward_dim_lvl'] ?? '';?>">
                        <select class="input_form forward_dim_lvl_unit" name="forward_dim_lvl_unit_<?=$line;?>">
                          <option value=""></option>
                          <option value="%" <?= isset($data['forward_dim_lvl_unit']) && $data['forward_dim_lvl_unit'] == '%' ? 'selected' : '' ?>>%</option>
                          <option value="mm" <?= isset($data['forward_dim_lvl_unit']) && $data['forward_dim_lvl_unit'] == 'mm' ? 'selected' : '' ?>>mm</option>
                        </select>
                      </td>
                      <td>
                        <input type="text"  class="forward_dim_p_mm hidden" name="forward_dim_p_mm_<?=$line;?>" value="<?=$data['forward_dim_p_mm'] ?? '';?>">
                        <span class="forward_dim_p_mm_txt"><?=$data['forward_dim_p_mm'] ?? '';?></span>
                      </td>
                      <td>
                        <input type="text"  class="forward_dim_p_pct hidden" name="forward_dim_p_pct_<?=$line;?>" value="<?=$data['forward_dim_p_pct'] ?? '';?>">
                        <span class="forward_dim_p_pct_txt"><?=isset($data['forward_dim_p_pct']) && $data['forward_dim_p_pct']  ? $data['forward_dim_p_pct'].'%': '';?></span>
                      </td>

                      <td>
                        <input type="text"  class="forward_dim_s_mm hidden" name="forward_dim_s_mm_<?=$line;?>" value="<?=$data['forward_dim_s_mm'] ?? '';?>">
                        <span class="forward_dim_s_mm_txt"><?=$data['forward_dim_s_mm'] ?? '';?></span>
                      </td>
                      <td>
                        <input type="text"  class="forward_dim_s_pct hidden" name="forward_dim_s_pct_<?=$line;?>" value="<?=$data['forward_dim_s_pct'] ?? '';?>">
                        <span class="forward_dim_s_pct_txt"><?=isset($data['forward_dim_s_pct']) && $data['forward_dim_s_pct']  ? $data['forward_dim_s_pct'].'%': '';?></span>
                      </td>

                      <td>
                        <input type="text" class="mean_dim_p hidden" name="mean_dim_p_<?=$line;?>" value="<?=$data['mean_dim_p'] ?? '';?>">
                        <span class="mean_dim_p_txt"><?=isset($data['mean_dim_p']) && $data['mean_dim_p'] ? $data['mean_dim_p'].'%':'';?></span>
                      </td>

                      <td>
                        <input type="text"  class="mean_dim_s hidden" name="mean_dim_s_<?=$line;?>" value="<?=$data['mean_dim_s'] ?? '';?>">
                        <span class="mean_dim_s_txt"><?=isset($data['mean_dim_s']) && $data['mean_dim_s'] ? $data['mean_dim_s'].'%':'';?></span>
                      </td>





                      <td style="width: 10px;">
                        <button class="btn btn-xs btn-danger px-2 py-1 clear_line">
                          <i class="fa fa-trash pr-0" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-xs btn-success px-2 py-1 new_plate_btn" data-line="<?=$line;?>">
                          <input type="hidden" class="new_plate" name="new_plate_<?=$line;?>" value="<?=$data['new_plate'] ?? '0';?>">
                          <span class="new_plate_txt">N</span>
                        </button>
                      </td>



                    </tr>


                  <?php $idx++;}?>


                  <tr class="total_tr">
                    <td class="text-center" style="border-right-color:transparent;">
                      <b>Total</b>
                    </td>
                    <td colspan="16" class="text-right" style="border-right-color:transparent;">
                    </td>
                    <td colspan="4">


                      <input type="text" class="hidden total_spot" name="total_spot"  value="<?=$data['total_spot'] ?? '0';?>">
                      <span class="total_spot_txt"><?=$data['total_spot'] ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                    </td>
                  </tr>


                </tbody>
              </table>

              <table class="signature_table">
                <tr>
                  <td>
                    Operator's Signature
                    <br><br><br><br><br><br>
                    <?=$report->operator;?>
                  </td>
                  <td>
                    Surveyor's Signature
                    <br><br><br><br><br><br>
                    <?=$report->surveyor;?>
                  </td>

                </tr>
              </table>
            </form>
          <?php }?>





          <?php if($form_data_format == 'two'){ ?>
            <form id="form_data">

              <input type="hidden" name="_token" value="<?=csrf_token();?>">
              <input type="hidden" name="category_id" value="<?=$category->id;?>">
              <input type="hidden" name="report_id" value="<?=$report->id;?>">
              <input type="hidden" name="total_line" value="<?=$total_line;?>">
              <input type="hidden" name="action" value="<?=$form ? 'edit':'create';?>">
              <input type="hidden" name="tab" value="<?=$_GET['tab'] ?? '';?>">
              <?php if($form){?>
                <input type="hidden" name="form_id" value="<?=$form->id;?>">
              <?php }?>


              <?php if($form){?>
                <a href="<?= url('/report_detail/' . base64_encode($report->id) . '?tab=' . ($_GET['tab'] ?? '') . '&form_id=' . ($form->id ?? '')); ?>" 
   class="btn btn-sm btn-secondary" style="float: right; margin-left: 10px;">
   Back
</a>

              <?php }?>

              <input type="submit" class="btn btn-sm btn-primary" style="float:right;" value="<?=$form ? 'Update':'Create';?> Form">


              <table class="padding">
                <tr>
                  <td>Default Org. Thickness</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_org_thickness" name="default_org_thickness" value="<?=$form->default_org_thickness;?>">
                  </td>
                  <td>&nbsp;</td>
                  <td>Default Min. Thickness</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_min_thickness" name="default_min_thickness" value="<?=$form->default_min_thickness;?>">
                  </td>
                  <td>&nbsp;</td>
                  <td>Default Dimunition Level</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_dim_lvl" name="default_dim_lvl" value="<?=$form->default_dim_lvl;?>">
                  </td>
                </tr>
              </table>

              <table class="header_table">
                <tr>
                  <td class="bki_logo_td">
                    <div class="bki_logo">
                    </div>
                  </td>
                  <td class="bki_title_td">PT. Biro Klasifikasi<br>Indonesia</td>
                  <td class="form_title_td" colspan="2">
                    <?=$report->name;?>
                  </td>
                </tr>
              </table>

              <table class="title_table">
                <tr>
                  <td class="sub_title">Ship's Name :</td>
                  <td class="bold"><?=$report->ship->name;?></td>
                  <td class="sub_title">Class Identity No. :</td>
                  <td class="bold"><?=$report->ship->class_identity;?></td>

                  <td class="sub_title">Report No. :</td>
                  <td class="bold"><?=$report->report_number;?></td>

                </tr>
              </table>


              <table class="form_table table_two" border="1">
                <thead>
                  <tr>
                    <th colspan="11" class="form_title text-left">
                      <?=$form->name;?>
                    </th>
                  </tr>
                  <tr>
                    <th colspan="11" class="form_title">
                      <input type="text" class="input_title title_1" name="title_1" value="<?=$form->title_1 ? : 'LOCATION OF STRUCTURE : ';?>" placeholder="Input additional title if needed">
                    </th>
                  </tr>
                  <tr>
                    <th rowspan="2"><?=$form->form_type->unit_title;?></th>
                    <th rowspan="2"><?=$form->form_type->number_wording;?></th>
                    <th rowspan="2">ORG THK (mm)</th>
                    <th rowspan="2">MIN. THK (mm)</th>
                    <th colspan="2">Gauged</th>
                    <th rowspan="2">Diminution Level</th>
                    <th colspan="2"><?=$form->form_type->dim_p_title;?></th>
                    <th colspan="2"><?=$form->form_type->dim_s_title;?></th>

                  </tr>
                  <tr>

                    <th><?=$form->form_type->gauged_p_title;?></th>
                    <th><?=$form->form_type->gauged_s_title;?></th>
                    <th>mm</th>
                    <th>%</th>
                    <th>mm</th>
                    <th>%</th>

                  </tr>


                </thead>


                <tbody>

                  <?php


                  $form_data = $form ? $form->form_data_two->keyBy('plate_position')->toArray() : [];
                  $unit_type = $form->form_type->unit_type;
                  $idx=0;
                  foreach ($unit['unit_position'] as $position)
                  {
                    $line = $unit['unit_position_input'][$position];
                    $data = $form_data[$position] ?? [];

                    ?>

                    <tr class="<?=$line;?>" line="<?=$line;?>">

                      <?php if($unit_type == 'free_text') {?>

                        <td><input type="text" id="cell-{{$idx}}-1" class="input_others position_txt" name="position_txt_<?=$line;?>" value="<?=$data['position_txt'] ?? '';?>"></td>
                      <?php } else { ?>
                        <td><?=$unit['unit_position_text'][$position];?></td>

                      <?php } ?>


                      <td><input type="text" id="cell-{{$idx}}-2" class="input_form item_no" name="item_no_<?=$line;?>" value="<?=$data['item_no'] ?? '';?>"></td>
                      <td><input type="text" id="cell-{{$idx}}-3" class="input_form org_thickness" name="org_thickness_<?=$line;?>" value="<?=$data['org_thickness'] ?? '';?>"></td>
                      <td><input type="text" id="cell-{{$idx}}-4" class="input_form min_thc min_thickness" name="min_thickness_<?=$line;?>" value="<?=$data['min_thickness'] ?? '';?>" readonly></td>
                      <td><input type="text" id="cell-{{$idx}}-5" class="input_form counted gauged_p" name="gauged_p_<?=$line;?>" value="<?=$data['gauged_p'] ?? '';?>"></td>
                      <td><input type="text" id="cell-{{$idx}}-6" class="input_form counted gauged_s" name="gauged_s_<?=$line;?>" value="<?=$data['gauged_s'] ?? '';?>"></td>
                      <td>
                        <input type="text" id="cell-{{$idx}}-7" class="input_form dim_lvl" name="dim_lvl_<?=$line;?>" value="<?=$data['dim_lvl'] ?? '';?>">
                        <select class="input_form dim_lvl_unit" name="dim_lvl_unit_<?=$line;?>">
                          <option value=""></option>
                          <option value="%" <?= isset($data['dim_lvl_unit_'.$line]) && $data['dim_lvl_unit_'.$line] == '%' ? 'selected' : '' ?>>%</option>
                          <option value="mm" <?= isset($data['dim_lvl_unit_'.$line]) && $data['dim_lvl_unit_'.$line] == 'mm' ? 'selected' : '' ?>>mm</option>
                        </select>
                      </td>

                      <td>
                        <input type="hidden" class="input_form dim_p_mm" name="dim_p_mm_<?=$line;?>" value="<?=$data['dim_p_mm'] ?? '';?>">
                        <span class="dim_p_mm_txt"><?=$data['dim_p_mm'] ?? '';?></span>
                      </td>


                      <td>
                        <input type="hidden" class="input_form dim_p_pct" name="dim_p_pct_<?=$line;?>" value="<?=$data['dim_p_pct'] ?? '';?>">
                        <span class="dim_p_pct_txt"><?=$data['dim_p_pct'] ?? '';?>%</span>
                      </td>


                      <td>
                        <input type="hidden" class="input_form dim_s_mm" name="dim_s_mm_<?=$line;?>" value="<?=$data['dim_s_mm'] ?? '';?>">
                        <span class="dim_s_mm_txt"><?=$data['dim_s_mm'] ?? '';?></span>
                      </td>


                      <td>
                        <input type="hidden" class="input_form dim_s_pct" name="dim_s_pct_<?=$line;?>" value="<?=$data['dim_s_pct'] ?? '';?>">
                        <span class="dim_s_pct_txt"><?=$data['dim_s_pct'] ?? '';?>%</span>
                      </td>









                      <td style="width: 10px;">
                        <button class="btn btn-xs btn-danger px-2 py-1 clear_line">
                          <i class="fa fa-trash pr-0" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-xs btn-success px-2 py-1 new_plate_btn" data-line="<?=$line;?>">
                          <input type="hidden" class="new_plate" name="new_plate_<?=$line;?>" value="<?=$data['new_plate'] ?? '0';?>">
                          <span class="new_plate_txt">N</span>
                        </button>
                      </td>



                    </tr>


                  <?php $idx++;}?>


                  <tr class="total_tr">
                    <td class="text-center" style="border-right-color:transparent;">
                      <b>Total</b>
                    </td>
                    <td colspan="7" class="text-right" style="border-right-color:transparent;">
                    </td>
                    <td colspan="4">


                      <input type="text" class="hidden total_spot" name="total_spot"  value="<?=$data['total_spot'] ?? '0';?>">
                      <span class="total_spot_txt"><?=$data['total_spot'] ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                    </td>
                  </tr>


                </tbody>
              </table>

              <table class="signature_table">
                <tr>
                  <td>
                    Operator's Signature
                    <br><br><br><br><br><br>
                    <?=$report->operator;?>
                  </td>
                  <td>
                    Surveyor's Signature
                    <br><br><br><br><br><br>
                    <?=$report->surveyor;?>
                  </td>

                </tr>
              </table>
            </form>
          <?php }?>


          <?php if($form_data_format == 'three'){ ?>
            <form id="form_data">
              <input type="hidden" name="_token" value="<?=csrf_token();?>">
              <input type="hidden" class="category_id" name="category_id" value="<?=$category->id;?>">
              <input type="hidden" class="report_id" name="report_id" value="<?=$report->id;?>">
              <input type="hidden" class="total_line" name="total_line" value="<?=$total_line;?>">
              <input type="hidden" class="action" name="action" value="<?=$form ? 'edit':'create';?>">
              <input type="hidden" class="tab" name="tab" value="<?=$_GET['tab'] ?? '';?>">
              <?php if($form){?>
                <input type="hidden" class="form_id" name="form_id" value="<?=$form->id;?>">
              <?php }?>


              <?php if($form){?>
                <a href="<?= url('/report_detail/' . base64_encode($report->id) . '?tab=' . ($_GET['tab'] ?? '') . '&form_id=' . ($form->id ?? '')); ?>" 
   class="btn btn-sm btn-secondary" style="float: right; margin-left: 10px;">
   Back
</a>

              <?php }?>

              <input type="submit" class="btn btn-sm btn-primary" style="float:right;" value="<?=$form ? 'Update':'Create';?> Form">


              <table class="padding">
                <tr>
                  <td>Default Org. Thickness</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_org_thickness" name="default_org_thickness" value="<?=$form->default_org_thickness;?>">
                  </td>
                  <td>&nbsp;</td>
                  <td>Default Min. Thickness</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_min_thickness" name="default_min_thickness" value="<?=$form->default_min_thickness;?>">
                  </td>
                  <td>&nbsp;</td>
                  <td>Default Dimunition Level</td>
                  <td>:</td>
                  <td>
                    <input type="text" class="input_others default_dim_lvl" name="default_dim_lvl" value="<?=$form->default_dim_lvl;?>">
                  </td>
                </tr>
              </table>

              <table class="header_table">
                <tr>
                  <td class="bki_logo_td">
                    <div class="bki_logo">
                    </div>
                  </td>
                  <td class="bki_title_td">PT. Biro Klasifikasi<br>Indonesia</td>
                  <td class="form_title_td" colspan="2">
                    <?=$report->name;?>
                  </td>
                </tr>
              </table>

              <table class="title_table">
                <tr>
                  <td class="sub_title">Ship's Name :</td>
                  <td class="bold"><?=$report->ship->name;?></td>
                  <td class="sub_title">Class Identity No. :</td>
                  <td class="bold"><?=$report->ship->class_identity;?></td>

                  <td class="sub_title">Report No. :</td>
                  <td class="bold"><?=$report->report_number;?></td>

                </tr>
              </table>


              <table class="form_table table_three" border="1">
                <thead>
                  <tr>
                    <th colspan="31" class="form_title">
                      <?=$form->name;?>
                    </th>
                  </tr>
                  <tr>
                    <th rowspan="3">Strake<br>Position</th>
                    <th colspan="10">
                      <?=$form->title_1 ??'-';?>
                    </th>
                    <th colspan="10">
                      <?=$form->title_2 ??'-';?>
                    </th>
                    <th colspan="10">
                      <?=$form->title_3 ??'-';?>
                    </th>
                  </tr>
                  <tr>
                    <th rowspan="3" class="rotate"><span>No or Letter</span></th>
                    <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
                    <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
                    <th colspan="2">Gauged</th>
                    <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
                    <th colspan="2">Diminution<br>P</th>
                    <th colspan="2">Diminution<br>S</th>

                    <th rowspan="3" class="rotate"><span>No or Letter</span></th>
                    <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
                    <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
                    <th colspan="2">Gauged</th>
                    <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
                    <th colspan="2">Diminution<br>P</th>
                    <th colspan="2">Diminution<br>S</th>

                    <th rowspan="3" class="rotate"><span>No or Letter</span></th>
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

                  <?php


                  $form_data = $form ? $form->form_data_three->keyBy('plate_position')->toArray() : [];
                  $unit_type = $form->form_type->unit_type;

                  $line_number = 0;
                  $idx = 0;
                  foreach ($unit['unit_position'] as $position)
                  {
                    $line = $unit['unit_position_input'][$position];
                    $data = $form_data[$position] ?? [];
                    $line_number++;

                    ?>

                    <tr class="<?=$line;?>" line="<?=$line;?>">

                      <?php if($unit_type == 'free_text') {?>

                        <td><input type="text" id="cell-{{$idx}}-1" class="input_others position_txt input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['position_txt'] ?? '';?>"></td>

                      <?php } else if($unit_type == 'strake') { ?>
                        <td>
                          <input type="hidden" class="input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=ucwords(str_replace('_', ' ', $unit['unit_position_text'][$position]));?>">
                          <?=ucwords(str_replace('_', ' ', $unit['unit_position_text'][$position]));?>
                        </td>
                      <?php } else { ?>
                        <td>
                          <input type="hidden" class="input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$unit['unit_position_text'][$position];?>">
                          <?=$unit['unit_position_text'][$position];?>
                        </td>

                      <?php } ?>


                      <?php for($x=1;$x<=3;$x++){?>


                        <td><input group="<?=$x;?>" id="cell-{{$idx}}-{{2+(($x-1)*6)}}" type="text" class="input_form item_no_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['item_no_'.$x] ?? '';?>"></td>
                        <td><input group="<?=$x;?>" id="cell-{{$idx}}-{{3+(($x-1)*6)}}" type="text" class="input_form org_thickness_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['org_thickness_'.$x] ?? '';?>"></td>
                        <td><input group="<?=$x;?>" id="cell-{{$idx}}-{{4+(($x-1)*6)}}" type="text" class="input_form min_thc min_thickness_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['min_thickness_'.$x] ?? '';?>" readonly></td>
                        <td><input group="<?=$x;?>" id="cell-{{$idx}}-{{5+(($x-1)*6)}}" type="text" class="input_form counted gauged_p_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['gauged_p_'.$x] ?? '';?>"></td>
                        <td><input group="<?=$x;?>" id="cell-{{$idx}}-{{6+(($x-1)*6)}}" type="text" class="input_form counted gauged_s_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['gauged_s_'.$x] ?? '';?>"></td>
                        <td class="dim_lvl_three">
                          <input group="<?=$x;?>" id="cell-{{$idx}}-{{7+(($x-1)*6)}}" type="text" class="input_form dim_lvl_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['dim_lvl_'.$x] ?? '';?>">
                          <select class="input_form dim_lvl_unit_<?=$x;?> input_form_<?=$line_number;?>" name="dim_lvl_unit_<?=$x;?>_<?=$line_number;?>">
                            <option value=""></option>
                            <option value="%" <?= isset($data['dim_lvl_unit_'.$x]) && $data['dim_lvl_unit_'.$x] == '%' ? 'selected' : '' ?>>%</option>
                            <option value="mm" <?= isset($data['dim_lvl_unit_'.$x]) && $data['dim_lvl_unit_'.$x] == 'mm' ? 'selected' : '' ?>>mm</option>
                          </select>
                          </td>


                        <td>

                          <input group="<?=$x;?>" type="hidden" class="input_form dim_p_mm_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['dim_p_mm_'.$x] ?? '';?>">
                          <span class="dim_p_mm_txt_<?=$x;?>"></span>
                        </td>


                        <td>

                          <input group="<?=$x;?>" type="hidden" class="input_form dim_p_pct_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['dim_p_pct_'.$x] ?? '';?>">
                          <span class="dim_p_pct_txt_<?=$x;?>"></span>
                        </td>


                        <td>

                          <input group="<?=$x;?>" type="hidden" class="input_form dim_s_mm_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['dim_s_mm_'.$x] ?? '';?>">
                          <span class="dim_s_mm_txt_<?=$x;?>"></span>
                        </td>


                        <td>

                          <input group="<?=$x;?>" type="hidden" class="input_form dim_s_pct_<?=$x;?> input_form_<?=$line_number;?>" name="input_form_<?=$line_number;?>" value="<?=$data['dim_s_pct_'.$x] ?? '';?>">
                          <span class="dim_s_pct_txt_<?=$x;?>"></span>
                        </td>



                      <?php }?>








                      <td style="width: 10px;">
                        <button class="btn btn-xs btn-danger px-2 py-1 clear_line">
                          <i class="fa fa-trash pr-0" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-xs btn-success px-2 py-1 new_plate_btn" data-line="<?=$line;?>">
                          <input type="hidden" class="new_plate" name="new_plate_<?=$line_number;?>" value="<?=$data['new_plate'] ?? '0';?>">
                          <span class="new_plate_txt">N</span>
                        </button>
                      </td>



                    </tr>


                  <?php $idx++;}?>


                  <tr class="total_tr">
                    <td class="text-center" style="border-right-color:transparent;">
                      <b>Total</b>
                    </td>
                    <td colspan="27" class="text-right" style="border-right-color:transparent;">
                    </td>
                    <td colspan="4">


                      <input type="text" class="hidden total_spot" name="total_spot"  value="<?=$data['total_spot'] ?? '0';?>">
                      <span class="total_spot_txt"><?=$data['total_spot'] ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                    </td>
                  </tr>


                </tbody>
              </table>

              <table class="signature_table">
                <tr>
                  <td>
                    Operator's Signature
                    <br><br><br><br><br><br>
                    <?=$report->operator;?>
                  </td>
                  <td>
                    Surveyor's Signature
                    <br><br><br><br><br><br>
                    <?=$report->surveyor;?>
                  </td>

                </tr>
              </table>
            </form>
          <?php }?>
        <div class="legend" style="margin-top: 20px;">
          <strong>Remarks:</strong>
          <div style="display: flex; gap: 20px; margin-top: 5px;">
            <div style="display: flex; align-items: center;">
              <span style="display: inline-block; width: 20px; height: 10px; background-color: #808080;"></span>
              <span style="margin-left: 5px;">Safe</span>
            </div>
            <div style="display: flex; align-items: center;">
              <span style="display: inline-block; width: 20px; height: 10px; background-color: #00FF00;"></span>
              <span style="margin-left: 5px;">New Plate</span>
            </div>
            <div style="display: flex; align-items: center;">
              <span style="display: inline-block; width: 20px; height: 10px; background-color: #FFFF00;"></span>
              <span style="margin-left: 5px;">Suspect Area</span>
            </div>
            <div style="display: flex; align-items: center;">
              <span style="display: inline-block; width: 20px; height: 10px; background-color: #FF0000;"></span>
              <span style="margin-left: 5px;">Area to be Repaired</span>
            </div>
          </div>
        </div>



        </div>

      </div><!-- end card-body -->
    </div><!-- end card -->
  </div>
  <!-- end col -->
</div>












<div class="modal fade" id="copy_data_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Copy Data</h4>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <h4>X</h4>
        </div>
        <!--end::Close-->
      </div>

      <div class="modal-body">

        Copy default data :
        <table>
          <tr>
            <td>ORG THK (mm)</td>
            <td>:</td>
            <td>
              <input type="text" class="form-control copy_org_thk">
            </td>
          </tr>

          <tr>
            <td>MIN. THK (mm)</td>
            <td>:</td>
            <td>
              <input type="text" class="form-control copy_min_thk">
            </td>
          </tr>
          <tr>
            <td>Diminution Level</td>
            <td>:</td>
            <td>
              <input type="text" class="form-control copy_dim_level">
            </td>
          </tr>

        </table>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary create_form_btn">Create Form</button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('css')

<style type="text/css">
  table.padding td {
    padding:2px 5px;
  }
  table input[type=text]
  {
    width:50px;
    border:1px solid #777;
    text-align: center;
  }
  .table_three input[type=text]
  {
    width:30px;
    font-size: 12px;
  }
  .table_three span {
    font-size:12px;
  }
  .input_form, .input_others, .input_title {
    background: #f1feff;
  }
  .min_thc {
    background-color: #f5f5f5;
  }
  .input_title {
    width: 100% !important;
    text-align: left !important;
    padding:4px 10px;
  }
  .form_title {
    padding: 4px !important;
    font-size: 16px !important;
  }
  .table {
    border-collapse: collapse;
  }
  .table td {
    padding: 3px;
  }

  .form_name {
    width: calc(100% - 6px) !important;
    margin: 0 auto;
    margin-left: 3px;
    margin-right: 3px;
    font-size:16px;
  }
  .amid {
    background-color: #e8fafc;
  }
  .bki_logo {
    width: 80px;
    height: 80px;
    background: url('{{ url("/") }}/images/bki_logo.png') center center no-repeat;
    background-size: 100% auto;
  }
  .header_table, .title_table, .form_table, .signature_table {
    width: 100%;
  }
  .title_table, .form_table {
    margin-bottom: 25px;
  }
  .form_table td , .form_table th {
    text-align: center;
    font-size: 13px;
    padding:2px;
    border:1px solid #666;
  }

  .signature_table td {
    width: 50%;
    text-align: center;
  }
  .signature_table {
    display: none;
  }

  .title_table .sub_title {
    width: 200px;
  }

  .title_table .bold {
    font-weight: bold;
  }
  .hidden {
    display: none;
  }
  .bki_logo_td {
    width: 100px;
  }
  .bki_title_td {
    text-align: left;
    font-size: 14px;
    line-height: 20px;
    padding-top: 15px;
    text-transform: uppercase;
    width: 150px;
  }
  .form_title_td {
    text-align: center;
    font-size: 18px;
  }
  .total_tr {
    font-size: 15px;
  }
  .total_tr td {
    padding-top: 8px;
    padding-bottom: 4px;
  }

  .clear_line
  {
    padding: 0px 4px !important;
    font-size: 11px;
    line-height: 20px;
  }
  .copy_button
  {
    padding: 0px 4px;
    font-size: 11px;
    line-height: 20px;
  }
  .position_txt {
    text-align: left !important;
  }
  .table_one .position_txt, .table_three .position_txt {
    width: 150px !important;
  }
  .table_two .position_txt {
    width: 100% !important;
  }

  .new_plate_btn.active {
    background-color: #28a745;
    color: white;
  }

  .new_plate_btn:not(.active) {
    background-color: #6c757d;
    color: white;
  }
</style>
@endsection
@section('js')

<script type="text/javascript">

  function change_color(line, item) {
    let pct = $('.'+line+' .'+item).val();
    let color = '';
    
    if(pct) {
      pct = parseFloat(pct);
      
      // Mendapatkan dim_lvl dan unit yang sesuai
      let dim_lvl, dim_lvl_unit, org_thickness;
      
      // Format One
      if(item.includes('aft_dim')) {
        dim_lvl = $('.'+line+' .aft_dim_lvl').val();
        dim_lvl_unit = $('.'+line+' .aft_dim_lvl_unit').val();
        org_thickness = $('.'+line+' .org_thickness').val();
      } else if(item.includes('forward_dim')) {
        dim_lvl = $('.'+line+' .forward_dim_lvl').val();
        dim_lvl_unit = $('.'+line+' .forward_dim_lvl_unit').val();
        org_thickness = $('.'+line+' .org_thickness').val();
      } 
      // Format Two
      else if(item.includes('dim_p_pct') || item.includes('dim_s_pct')) {
        dim_lvl = $('.'+line+' .dim_lvl').val();
        dim_lvl_unit = $('.'+line+' .dim_lvl_unit').val();
        org_thickness = $('.'+line+' .org_thickness').val();
      }
      // Format Three 
      else if(item.match(/dim_[ps]_pct_\d+/)) {
        let group = item.match(/\d+/)[0];
        dim_lvl = $('.'+line+' .dim_lvl_'+group).val();
        dim_lvl_unit = $('.'+line+' .dim_lvl_unit_'+group).val();
        org_thickness = $('.'+line+' .org_thickness_'+group).val();
      }

      if(dim_lvl && dim_lvl_unit && org_thickness) {
        dim_lvl = parseFloat(dim_lvl);
        org_thickness = parseFloat(org_thickness);
        
        // Konversi dim_lvl ke persentase jika unitnya mm
        let dim_lvl_pct = dim_lvl;
        if(dim_lvl_unit === 'mm') {
          dim_lvl_pct = (dim_lvl / org_thickness) * 100;
        }
        
        // Penentuan warna berdasarkan persentase
        if(pct < 0) {
          color = 'green';
        } else if(pct >= 0 && pct < (dim_lvl_pct * 0.75)) {
          color = 'no-color';
        } else if(pct >= (dim_lvl_pct * 0.75) && pct < dim_lvl_pct) {
          color = 'yellow';
        } else {
          color = 'red';
        }
      }
    }

    $('.'+line+' .'+item).parent().removeClass('transparent red yellow green no-color');
    if(color) {
      $('.'+line+' .'+item).parent().addClass(color);
    }
  }



  $(document).ready(function(){


    <?php if($form_data_format == 'three') {?>
      setTimeout(function() {
        $('#topnav-hamburger-icon').trigger('click');
      }, 200);
    <?php } ?>


    $('.copy_button').click(function(e){
      e.preventDefault();

      $('#copy_data_modal').modal('show');
    });

    // Add autosave functionality
    let autoSaveTimer;
    $('.input_form').on('keyup', function() {
      clearTimeout(autoSaveTimer); // Reset timer setiap kali ada keyup
      autoSaveTimer = setTimeout(function() {
        $('#form_data').submit(); // Submit form setelah 10 detik tidak ada aktivitas
      }, 10000); // 10 seconds
    });


      // Meng-calculate semua line saat membuka dokumen agar warna2 tampil
    <?php if($form_data_format != 'three') {?>
      <?php foreach ($unit['unit_position'] as $position){?>
        calculate('<?=$unit['unit_position_input'][$position];?>')
      <?php }?>
    <?php }else {?>
      <?php foreach ($unit['unit_position'] as $position){?>
        calculate('<?=$unit['unit_position_input'][$position];?>', 1)
        calculate('<?=$unit['unit_position_input'][$position];?>', 2)
        calculate('<?=$unit['unit_position_input'][$position];?>', 3)
      <?php }?>
    <?php }?>


    <?php if($form_data_format == 'three') {?>

      $('#form_data').submit(function (e){

        e.preventDefault();

        form_id               = $('#form_data .form_id').val();
        category_id           = $('#form_data .category_id').val();
        report_id             = $('#form_data .report_id').val();
        total_line            = $('#form_data .total_line').val();
        action                = $('#form_data .action').val();
        tab                   = $('#form_data .tab').val();
        default_org_thickness = $('#form_data .default_org_thickness').val();
        default_min_thickness = $('#form_data .default_min_thickness').val();
        default_dim_lvl       = $('#form_data .default_dim_lvl').val();
        total_spot            = $('#form_data .total_spot').val();

        requestData = [];
        requestData.push({name: '_token', value: '{{csrf_token()}}'});
        requestData.push({name: 'form_id', value: form_id});
        requestData.push({name: 'category_id', value: category_id});
        requestData.push({name: 'report_id', value: report_id});
        requestData.push({name: 'total_line', value: total_line});
        requestData.push({name: 'action', value: action});
        requestData.push({name: 'tab', value: tab});
        requestData.push({name: 'default_org_thickness', value: default_org_thickness});
        requestData.push({name: 'default_min_thickness', value: default_min_thickness});
        requestData.push({name: 'default_dim_lvl', value: default_dim_lvl});
        requestData.push({name: 'total_spot', value: total_spot});



        for(x=1;x<=<?=$total_line_three;?>;x++)
        {

          item_line = '';

          $('.input_form_'+x).each(function(e){

            item_line += $(this).val()+'----';

          });

          $('input[name="new_plate_'+x+'"]').each(function(e){
            console.log("NEW PLATE : "+$(this).val());
            item_line += $(this).val()+'----';
          });
          requestData.push({name: 'input_form_'+x, value: item_line});

        }


        console.log(requestData);
        $.ajax({
          url: "<?=url('/form_data')?>",
          type: 'post',
          dataType: "json",
          data: requestData,
          success: function(data) {

            if(data.status == 'success')
            {
              iziToast.success({
                title: 'Success',
                message: data.message,
                position: 'topRight'
              });
              // window.location = data.redirect_url;
            }
            else
            {
              iziToast.error({
                title: 'Error',
                message: data.message,
                position: 'topRight'
              });
            }
          },

        });

      });

    <?php }else{ ?>

      $('#form_data').submit(function (e){
        e.preventDefault();

        var formData = new FormData(this);
        for (var pair of formData.entries()) {
          if (pair[1] !== '') {
            console.log(pair[0] + ': ' + pair[1]);
          }
        }

        $.ajax({
          url: "<?=url('/form_data')?>",
          type: 'POST',
          data: new FormData( this ),
          processData: false,
          contentType: false,

          success: function(data) {

            if(data.status == 'success')
            {
              iziToast.success({
                title: 'Success',
                message: data.message,
                position: 'topRight'
              });
              // window.location = data.redirect_url;
            }
            else
            {
              iziToast.error({
                title: 'Error', 
                message: data.message,
                position: 'topRight'
              });
            }
          },

        });

      });

    <?php } ?>

    $('.input_form').change(function(e){
      e.preventDefault();

      // Memfilter orang yang salah memasukkan koma
      val = $(this).val();
      val = val.replace(',', '.');
      $(this).val(val);

      line = $(this).parent().parent().attr('line');

        // Untuk type three
      group = $(this).attr('group');
      default_value(line, group);
      calculate(line, group);
    });


    <?php if($form_data_format == 'one') {?>

      function default_value(line, group = null)
      {
        default_dim_lvl = $('.default_dim_lvl').val();
        default_min_thickness = $('.default_min_thickness').val();
        default_org_thickness = $('.default_org_thickness').val();

        if(default_dim_lvl)
        {
          if(!$('.'+line+' .aft_dim_lvl').val())
          {
            $('.'+line+' .aft_dim_lvl').val(default_dim_lvl);
          }
          if(!$('.'+line+' .forward_dim_lvl').val())
          {
            $('.'+line+' .forward_dim_lvl').val(default_dim_lvl);
          }
        }

        if(default_min_thickness)
        {
          if(!$('.'+line+' .min_thickness').val())
          {
            $('.'+line+' .min_thickness').val(default_min_thickness);
          }
        }

        if(default_org_thickness)
        {
          if(!$('.'+line+' .org_thickness').val())
          {
            $('.'+line+' .org_thickness').val(default_org_thickness);
          }
        }
      }

      function calculate(line, group = null)
      {

        org_thickness     = $('.'+line+' .org_thickness').val();

        aft_dim_lvl     = $('.'+line+' .aft_dim_lvl').val();
        aft_dim_lvl_unit = $('.'+line+' .aft_dim_lvl_unit').val();
        aft_gauged_p      = $('.'+line+' .aft_gauged_p').val();
        aft_gauged_s      = $('.'+line+' .aft_gauged_s').val();

        forward_dim_lvl     = $('.'+line+' .forward_dim_lvl').val();
        forward_dim_lvl_unit   = $('.'+line+' .forward_dim_lvl_unit').val();
        forward_gauged_p  = $('.'+line+' .forward_gauged_p').val();
        forward_gauged_s  = $('.'+line+' .forward_gauged_s').val();

        // Calculate min_thickness
        if(org_thickness && aft_dim_lvl && aft_dim_lvl_unit)
        {
          if (aft_dim_lvl_unit === '%') {
            min_thickness = (parseFloat(org_thickness) * (1 - parseFloat(aft_dim_lvl)/100)).toFixed(2);
          } else if (aft_dim_lvl_unit === 'mm') {
            min_thickness = (parseFloat(org_thickness) - parseFloat(aft_dim_lvl)).toFixed(2);
          }
          $('.'+line+' .min_thickness').val(min_thickness);
          $('.'+line+' .min_thickness_txt').html(min_thickness);
        }
        else
        {
          $('.'+line+' .min_thickness').val('');
          $('.'+line+' .min_thickness_txt').html('');
        }

        if(org_thickness && aft_gauged_p)
        {
          val = (parseFloat(org_thickness) - parseFloat(aft_gauged_p)).toFixed(2);
          $('.'+line+' .aft_dim_p_mm').val(val);
          $('.'+line+' .aft_dim_p_mm_txt').html(val);
        }
        else
        {
          $('.'+line+' .aft_dim_p_mm').val('');
          $('.'+line+' .aft_dim_p_mm_txt').html('');
        }

        aft_dim_p_mm     = $('.'+line+' .aft_dim_p_mm').val();


        if(org_thickness  && aft_dim_lvl && aft_dim_lvl_unit && aft_gauged_p)
        {
          if (aft_dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - aft_gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (aft_dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - aft_gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .aft_dim_p_pct').val(val);
          $('.'+line+' .aft_dim_p_pct_txt').html(val+'%');
        }
        else
        {
          $('.'+line+' .aft_dim_p_pct').val('');
          $('.'+line+' .aft_dim_p_pct_txt').html('');
        }



        if(org_thickness && aft_gauged_s)
        {
          val = (parseFloat(org_thickness) - parseFloat(aft_gauged_s)).toFixed(2);
          $('.'+line+' .aft_dim_s_mm').val(val);
          $('.'+line+' .aft_dim_s_mm_txt').html(val);
        }
        else
        {
          $('.'+line+' .aft_dim_s_mm').val('');
          $('.'+line+' .aft_dim_s_mm_txt').html('');
        }

        aft_dim_s_mm     = $('.'+line+' .aft_dim_s_mm').val();


        if(org_thickness  && aft_dim_lvl && aft_gauged_s)
        {
          if (aft_dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - aft_gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (aft_dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - aft_gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .aft_dim_s_pct').val(val);
          $('.'+line+' .aft_dim_s_pct_txt').html(val+'%');
        }
        else
        {
          $('.'+line+' .aft_dim_s_pct').val('');
          $('.'+line+' .aft_dim_s_pct_txt').html('');
        }



        if(org_thickness && forward_gauged_p)
        {
          val = (parseFloat(org_thickness) - parseFloat(forward_gauged_p)).toFixed(2);
          $('.'+line+' .forward_dim_p_mm').val(val);
          $('.'+line+' .forward_dim_p_mm_txt').html(val);
        }
        else
        {
          $('.'+line+' .forward_dim_p_mm').val('');
          $('.'+line+' .forward_dim_p_mm_txt').html('');
        }

        forward_dim_p_mm     = $('.'+line+' .forward_dim_p_mm').val();


        if(org_thickness  && forward_dim_lvl && forward_gauged_p)
        {
          if (forward_dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - forward_gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (forward_dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - forward_gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .forward_dim_p_pct').val(val);
          $('.'+line+' .forward_dim_p_pct_txt').html(val+'%');
        }
        else
        {
          $('.'+line+' .forward_dim_p_pct').val('');
          $('.'+line+' .forward_dim_p_pct_txt').html('');
        }



        if(org_thickness && forward_gauged_s)
        {
          val = (parseFloat(org_thickness) - parseFloat(forward_gauged_s)).toFixed(2);
          $('.'+line+' .forward_dim_s_mm').val(val);
          $('.'+line+' .forward_dim_s_mm_txt').html(val);
        }
        else
        {
          $('.'+line+' .forward_dim_s_mm').val('');
          $('.'+line+' .forward_dim_s_mm_txt').html('');
        }

        forward_dim_s_mm     = $('.'+line+' .forward_dim_s_mm').val();


        if(org_thickness  && forward_dim_lvl && forward_gauged_s)
        {
          if (forward_dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - forward_gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (forward_dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - forward_gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .forward_dim_s_pct').val(val);
          $('.'+line+' .forward_dim_s_pct_txt').html(val+'%');
        }
        else
        {
          $('.'+line+' .forward_dim_s_pct').val('');
          $('.'+line+' .forward_dim_s_pct_txt').html('');
        }



        aft_dim_p_pct     = $('.'+line+' .aft_dim_p_pct').val();
        forward_dim_p_pct = $('.'+line+' .forward_dim_p_pct').val();

        if(aft_dim_p_pct && forward_dim_p_pct)
        {
          val = ((parseFloat(aft_dim_p_pct) + parseFloat(forward_dim_p_pct)) / 2).toFixed(2);
          $('.'+line+' .mean_dim_p').val(val);
          $('.'+line+' .mean_dim_p_txt').html(val+'%');
        }
        else if(aft_dim_p_pct)
        {
          $('.'+line+' .mean_dim_p').val(aft_dim_p_pct);
          $('.'+line+' .mean_dim_p_txt').html(aft_dim_p_pct+'%');
        }
        else if(forward_dim_p_pct)
        {
          $('.'+line+' .mean_dim_p').val(forward_dim_p_pct);
          $('.'+line+' .mean_dim_p_txt').html(forward_dim_p_pct+'%');
        }

        aft_dim_s_pct     = $('.'+line+' .aft_dim_s_pct').val();
        forward_dim_s_pct = $('.'+line+' .forward_dim_s_pct').val();

        if(aft_dim_s_pct && forward_dim_s_pct)
        {
          val = ((parseFloat(aft_dim_s_pct) + parseFloat(forward_dim_s_pct)) / 2).toFixed(2);
          $('.'+line+' .mean_dim_s').val(val);
          $('.'+line+' .mean_dim_s_txt').html(val+'%');
        }
        else if(aft_dim_s_pct)
        {
          $('.'+line+' .mean_dim_s').val(aft_dim_s_pct);
          $('.'+line+' .mean_dim_s_txt').html(aft_dim_s_pct+'%');
        }
        else if(forward_dim_s_pct)
        {
          $('.'+line+' .mean_dim_s').val(forward_dim_s_pct);
          $('.'+line+' .mean_dim_s_txt').html(forward_dim_s_pct+'%');
        }



        total = 0;
        $('.counted').each(function(e){
          if($(this).val())
          {
            total++
          }
        })

        $('.total_spot').val(total);
        $('.total_spot_txt').html(total);
        total > 1 ? $('.spot_s').show() : $('.spot_s').hide();


        change_color(line,'aft_dim_s_pct');
        change_color(line,'aft_dim_p_pct');
        change_color(line,'forward_dim_s_pct');
        change_color(line,'forward_dim_p_pct');
        change_color(line,'mean_dim_p');
        change_color(line,'mean_dim_s');




      }

    <?php }?>



    <?php if($form_data_format == 'two') {?>

      function default_value(line, group = null)
      {
        default_dim_lvl = $('.default_dim_lvl').val();
        default_min_thickness = $('.default_min_thickness').val();
        default_org_thickness = $('.default_org_thickness').val();


        if(default_dim_lvl)
        {
          if(!$('.'+line+' .dim_lvl').val())
          {
            $('.'+line+' .dim_lvl').val(default_dim_lvl);
          }
        }

        if(default_min_thickness)
        {
          if(!$('.'+line+' .min_thickness').val())
          {
            $('.'+line+' .min_thickness').val(default_min_thickness);
          }
        }

        if(default_org_thickness)
        {
          if(!$('.'+line+' .org_thickness').val())
          {
            $('.'+line+' .org_thickness').val(default_org_thickness);
          }
        }
      }


      function calculate(line, group = null)
      {

        org_thickness = $('.'+line+' .org_thickness').val();
        gauged_p      = $('.'+line+' .gauged_p').val();
        gauged_s      = $('.'+line+' .gauged_s').val();
        dim_lvl      = $('.'+line+' .dim_lvl').val();
        dim_lvl_unit = $('.'+line+' .dim_lvl_unit').val();

        // Calculate min_thickness
        if(org_thickness && dim_lvl && dim_lvl_unit)
        {
          if (dim_lvl_unit === '%') {
            min_thickness = (parseFloat(org_thickness) * (1 - parseFloat(dim_lvl)/100)).toFixed(2);
          } else if (dim_lvl_unit === 'mm') {
            min_thickness = (parseFloat(org_thickness) - parseFloat(dim_lvl)).toFixed(2);
          }
          $('.'+line+' .min_thickness').val(min_thickness);
          $('.'+line+' .min_thickness_txt').html(min_thickness);
        }
        else
        {
          $('.'+line+' .min_thickness').val('');
          $('.'+line+' .min_thickness_txt').html('');
        }

        if(org_thickness && gauged_p)
        {
          val = (parseFloat(org_thickness) - parseFloat(gauged_p)).toFixed(2);
          $('.'+line+' .dim_p_mm').val(val);
          $('.'+line+' .dim_p_mm_txt').html(val);
        }
        else
        {
          $('.'+line+' .dim_p_mm').val('');
          $('.'+line+' .dim_p_mm_txt').html('');
        }

        dim_p_mm     = $('.'+line+' .dim_p_mm').val();

        if(org_thickness  && dim_lvl && gauged_p)
        {
          if (dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .dim_p_pct').val(val);
          $('.'+line+' .dim_p_pct_txt').html(val+'%');
        }
        else
        {
          $('.'+line+' .dim_p_pct').val('');
          $('.'+line+' .dim_p_pct_txt').html('');
        }




        if(org_thickness && gauged_s)
        {
          val = (parseFloat(org_thickness) - parseFloat(gauged_s)).toFixed(2);
          $('.'+line+' .dim_s_mm').val(val);
          $('.'+line+' .dim_s_mm_txt').html(val);
        }
        else
        {
          $('.'+line+' .dim_s_mm').val('');
          $('.'+line+' .dim_s_mm_txt').html('');
        }

        dim_s_mm     = $('.'+line+' .dim_s_mm').val();

        if(org_thickness  && dim_lvl && gauged_s)
        {
          if (dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .dim_s_pct').val(val);
          $('.'+line+' .dim_s_pct_txt').html(val+'%');
        }
        else
        {
          $('.'+line+' .dim_s_pct').val('');
          $('.'+line+' .dim_s_pct_txt').html('');
        }


        total = 0;
        $('.counted').each(function(e){
          if($(this).val())
          {
            total++
          }
        })

        $('.total_spot').val(total);
        $('.total_spot_txt').html(total);
        total > 1 ? $('.spot_s').show() : $('.spot_s').hide();

        change_color(line,'dim_p_pct');
        change_color(line,'dim_s_pct');
      }

    <?php }?>




    <?php if($form_data_format == 'three') {?>

      function default_value(line, group = null)
      {
        default_dim_lvl = $('.default_dim_lvl').val();
        default_min_thickness = $('.default_min_thickness').val();
        default_org_thickness = $('.default_org_thickness').val();


        if(default_dim_lvl)
        {
          if(!$('.'+line+' .dim_lvl_'+group).val())
          {
            $('.'+line+' .dim_lvl_'+group).val(default_dim_lvl);
          }
        }

        if(default_min_thickness)
        {
          if(!$('.'+line+' .min_thickness_'+group).val())
          {
            $('.'+line+' .min_thickness_'+group).val(default_min_thickness);
          }
        }

        if(default_org_thickness)
        {
          if(!$('.'+line+' .org_thickness_'+group).val())
          {
            $('.'+line+' .org_thickness_'+group).val(default_org_thickness);
          }
        }
      }


      function calculate(line, group = null)
      {
        org_thickness = $('.'+line+' .org_thickness_'+group).val();
        gauged_p      = $('.'+line+' .gauged_p_'+group).val();
        gauged_s      = $('.'+line+' .gauged_s_'+group).val();
        dim_lvl       = $('.'+line+' .dim_lvl_'+group).val();
        dim_lvl_unit  = $('.'+line+' .dim_lvl_unit_'+group).val();

        // Calculate min_thickness
        if(org_thickness && dim_lvl && dim_lvl_unit)
        {
          if (dim_lvl_unit === '%') {
            min_thickness = (parseFloat(org_thickness) * (1 - parseFloat(dim_lvl)/100)).toFixed(2);
          } else if (dim_lvl_unit === 'mm') {
            min_thickness = (parseFloat(org_thickness) - parseFloat(dim_lvl)).toFixed(2);
          }
          $('.'+line+' .min_thickness_'+group).val(min_thickness);
          $('.'+line+' .min_thickness_txt_'+group).html(min_thickness);
        }
        else
        {
          $('.'+line+' .min_thickness_'+group).val('');
          $('.'+line+' .min_thickness_txt_'+group).html('');
        }

        if(org_thickness && gauged_p)
        {
          val = (parseFloat(org_thickness) - parseFloat(gauged_p)).toFixed(2);
          $('.'+line+' .dim_p_mm_'+group).val(val);
          $('.'+line+' .dim_p_mm_txt_'+group).html(val);
        }
        else
        {
          $('.'+line+' .dim_p_mm_'+group).val('');
          $('.'+line+' .dim_p_mm_txt_'+group).html('');
        }

        dim_p_mm     = $('.'+line+' .dim_p_mm_'+group).val();

        if(org_thickness && dim_lvl && gauged_p && dim_lvl_unit)
        {
          if (dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - gauged_p)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .dim_p_pct_'+group).val(val);
          $('.'+line+' .dim_p_pct_txt_'+group).html(val+'<span class="percent">%</span>');
        }
        else
        {
          $('.'+line+' .dim_p_pct_'+group).val('');
          $('.'+line+' .dim_p_pct_txt_'+group).html('');
        }

        if(org_thickness && gauged_s)
        {
          val = (parseFloat(org_thickness) - parseFloat(gauged_s)).toFixed(2);
          $('.'+line+' .dim_s_mm_'+group).val(val);
          $('.'+line+' .dim_s_mm_txt_'+group).html(val);
        }
        else
        {
          $('.'+line+' .dim_s_mm_'+group).val('');
          $('.'+line+' .dim_s_mm_txt_'+group).html('');
        }

        dim_s_mm     = $('.'+line+' .dim_s_mm_'+group).val();

        if(org_thickness && dim_lvl && gauged_s && dim_lvl_unit)
        {
          if (dim_lvl_unit === '%') {
            val = (parseFloat(org_thickness - gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          } else if (dim_lvl_unit === 'mm') {
            val = (parseFloat(org_thickness - gauged_s)/parseFloat(org_thickness) *100).toFixed(2);
          }
          $('.'+line+' .dim_s_pct_'+group).val(val);
          $('.'+line+' .dim_s_pct_txt_'+group).html(val+'<span class="percent">%</span>');
        }
        else
        {
          $('.'+line+' .dim_s_pct_'+group).val('');
          $('.'+line+' .dim_s_pct_txt_'+group).html('');
        }

        total = 0;
        $('.counted').each(function(e){
          if($(this).val())
          {
            total++
          }
        })

        $('.total_spot').val(total);
        $('.total_spot_txt').html(total);
        total > 1 ? $('.spot_s').show() : $('.spot_s').hide();

        change_color(line,'dim_p_pct_'+group);
        change_color(line,'dim_s_pct_'+group);
      }

      // Add event listener for dim_lvl_unit change
      $('.dim_lvl_unit_1, .dim_lvl_unit_2, .dim_lvl_unit_3').on('change', function() {
        var line = $(this).closest('tr').attr('class');
        var group = $(this).attr('class').match(/dim_lvl_unit_(\d+)/)[1];
        calculate(line, group);
      });

    <?php }?>



    $('.clear_line').click(function(e){
      e.preventDefault();

      if(confirm('Are you sure want to clear this line?'))
      {
        line = $(this).parent().parent().attr('line');
        $('.'+line+' input[type=text]').val('');
        $('.'+line+' span').html('');
        $('.'+line+' td').removeClass('red');
        $('.'+line+' td').removeClass('green');
        $('.'+line+' td').removeClass('yellow');
      }

    })

    // Inisialisasi status new plate saat halaman dimuat
    $('.new_plate').each(function() {
      let btn = $(this).closest('.new_plate_btn');
      if($(this).val() === '1') {
        btn.addClass('active');
      } else {
        btn.removeClass('active'); 
      }
    });

    // Handle klik tombol new plate
    $('.new_plate_btn').click(function(e){
      e.preventDefault();
      let input = $(this).find('.new_plate');
      let currentValue = input.val();
      
      if(currentValue === '0') {
        input.val('1');
        $(this).addClass('active');
      } else {
        input.val('0'); 
        $(this).removeClass('active');
      }

      // Trigger form submit untuk autosave
      clearTimeout(autoSaveTimer);
      autoSaveTimer = setTimeout(function() {
        $('#form_data').submit();
      }, 10000);
    });

  });
</script>

    <script>
        document.addEventListener('keydown', function(event) {
            let focusedElement = document.activeElement;
            if (focusedElement.tagName.toLowerCase() === 'input') {
                let cellId = focusedElement.id;
                let [_, row, col] = cellId.split('-').map(Number);

                // Dapatkan jumlah baris dan kolom dari tabel
                let table = document.querySelector('.form_table');
                let rowCount = table.rows.length;
                let colCount = table.rows[10].querySelectorAll('input').length;


                if (event.ctrlKey && !event.shiftKey && !event.altKey) {
                    switch (event.key) {
                        case 'ArrowRight':
                            col = (col + 1) % colCount;
                            break;
                        case 'ArrowLeft':
                            col = (col - 1 + colCount) % colCount;
                            break;
                        case 'ArrowDown':
                            row = (row + 1) % rowCount;
                            break;
                        case 'ArrowUp':
                            row = (row - 1 + rowCount) % rowCount;
                            break;
                        default:
                            return; // keluar jika tombol bukan panah
                    }
                }

                // console.log("cell-"+row+"-"+col);
                let nextCell = document.getElementById(`cell-${row}-${col}`);
                if (nextCell) {
                    nextCell.focus();
                }
            }
        });
    </script>

@endsection
