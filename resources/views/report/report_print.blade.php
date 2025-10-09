<link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

<!-- Main Content -->
<div class="main-content">
  <section class="section">


    <div class="section-body">


      <div class="card">
        <div class="card-body">


          <div class="rotate" style="margin-top: 120px;">



            <table style="width:90%; margin: 0 auto;">
              <tr>
                <td style="width:170px">
                  <img src="/images/logobki_new.png" style="width: auto;height: 80px;">
                </td>
                <td style="font-size: 22px;font-weight: bold;text-align: center;">
                  PT. BIRO KLASIFIKASI INDONESIA
                  <br>
                  CABANG PRATAMA KOMERSIL BANJARMASIN
                </td>

              </tr>
            </table>

            <br>
            <hr>

            <table class="" style="width:600px;margin:0 auto;">

              <tr>
                <th colspan="7" class="text-center">
                  <br>
                  <?=$report->ship->name;?>
                  <br>
                </th>
              </tr>
              <tr>
                <td style="width:150px">
                  No. Laporan
                  <div class="eng">Number of Report</div>
                </td>
                <td style="width:2px;">:</td>
                <td colspan="5">
                  <?=$report->report_number;?>
                </td>
              </tr>
              <tr>
                <td>
                  Jenis Laporan
                  <div class="eng">Kind of Report</div>
                </td>
                <td>:</td>
                <td colspan="5">
                  -----
                </td>
              </tr>
              <tr>
                <td>
                  Pemilik
                  <div class="eng">Report Owner</div>
                </td>
                <td>:</td>
                <td colspan="5">
                  <?php if($report->user->user_type == 'bki'){ ?>
                    <?=$report->user->name ?? '-';?><br>
                    <?=$report->user->cif ?? '-';?>
                  <?php }else{ ?>
                    <?=$report->user->name ?? '-';?><br>
                    <?=$report->user->email ?? '-';?>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <th colspan="7" class="text-center">
                  <br>
                  <br>
                  Data-Data Teknis
                  <div class="eng">Principle Dimension</div>
                </th>
              </tr>
              <tr>
                <td>
                  Jenis Kapal
                  <div class="eng">Type Of Ship</div>
                </td>
                <td>:</td>
                <td colspan="5">
                  <?=$report->ship->ship_type;?>
                </td>
              </tr>
              <tr>
                <td rowspan="5" class="align-top">
                  Ukuran Kapal
                  <div class="eng">Ship Dimension</div>
                </td>
                <td rowspan="5" class="align-top">:</td>
                <td style="width:180px;">LOA</td>
                <td>:</td>
                <td><?=$report->ship->loa ?? '-';?></td>
                <td>Meter</td>
              </tr>
              <tr>
                <td>Breadth (B)</td>
                <td>:</td>
                <td><?=$report->ship->breadth ?? '-';?></td>
                <td>Meter</td>
              </tr>
              <tr>
                <td>Depth (H)</td>
                <td>:</td>
                <td><?=$report->ship->depth ?? '-';?></td>
                <td>Meter</td>
              </tr>
              <tr>
                <td>
                  Tonase Kotor/Daya
                  <div class="eng">GT/HP</div>
                </td>
                <td>:</td>
                <td colspan="2">
                  <?=$report->ship->weight;?> TON / <?=$report->ship->power ?? '-';?> HP
                </td>
              </tr>
              <tr>
                <td>
                  Klasifikasi
                  <div class="eng">Classification</div>
                </td>
                <td>:</td>
                <td colspan="2">
                  <?=$report->ship->classification ?? '-';?>
                </td>
              </tr>
            </table>
          </td>
        </tr>



      </table>       


    </div>


    <div class="pagebreak"></div>
    <?php
    $x = 0;

    foreach ($categories as $category) { 

      $report_image = $report_images[$category->id] ?? '';
      $image_url = $report_image ? $report_image['url'] : '';

      ?>


      <div class="tab-pane">

        <div class="report_container">

          <div class="" style="width:100%;margin-top: 80px;margin-bottom: 80px;">
            <h4 class="category_title" style="text-align:center;">  
              <?=$category->name;?>
              <br>
              <?=$category->title;?>
            </h4>
          </div>

          <div class="pagebreak"></div>

          <?php foreach($category->images as $image){?>
            <div class="image_area">
              <img class="image_category_<?=$category->id;?>" src="<?=url('storage').'/'.$image->url;?>" />
            </div>
            <div class="pagebreak"></div>
          <?php } ?>


          <?php
          $form_categories = $form_data_all[$category->id] ?? '';
          if($form_categories)
          {
            foreach($form_categories as $form_category)
            {
              foreach($form_category->form as $form)
              {
                if($form_category->form_data_format == 'one')
                {
                  $form_data = $form->form_data_one->keyBy('plate_position')->toArray();
                  ?>



                  <div class="landscape">



                    <table class="header_table">
                      <tr>
                        <td class="logo">
                          <img src="<?=url('/');?>/images/logobki_new.png">
                        </td>
                        <td class="bki_title">
                          PT. BIRO KLASIFIKASI INDONESIA
                        </td>
                        <td class="form_title text-right align-top">
                          <?=$category->name;?>
                        </td>
                      </tr>

                    </table>

                    <div class="text-center" style="padding:10px;font-weight:bold;"><?=$category->description;?></div>
                    <table class="sub_header_table">
                      <tr>
                        <td>
                          <b> Ship's Name : </b>
                          <?=$ship->name;?>
                        </td>
                        <td>
                          <b> IMO No. : </b>
                          <?=$ship->no_imo;?>
                        </td>
                        <td>
                          <b> Report No. : </b>
                          <?=$report->report_number;?>
                        </td>
                      </tr>
                    </table>

                    <table class="form_table <?=get_font_size($form->total_line, 'one');?>" border="1">
                      <thead>
                        <tr>
                          <th>STRAKE POSITION</th>
                          <th colspan="19" class="form_title" style="border-right:none;">
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
                        $unit_type  = $form->form_type->unit_type;
                        $unit       = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);                                

                        foreach ($unit['unit_position'] as $position) {


                          if(isset($form_data[$position]))
                          {
                            $data = $form_data[$position];


                            $aft_dim_lvl = $data['aft_dim_lvl'] ?? '';
                            $aft_dim_s_pct = $data['aft_dim_s_pct'] ?? '';
                            $aft_dim_p_pct = $data['aft_dim_p_pct'] ?? '';


                            $forward_dim_lvl = $data['forward_dim_lvl'] ?? '';
                            $forward_dim_s_pct = $data['forward_dim_s_pct'] ?? '';
                            $forward_dim_p_pct = $data['forward_dim_p_pct'] ?? '';

                            $mean_dim_p = $data['mean_dim_p'] ?? '';
                            $mean_dim_s = $data['mean_dim_s'] ?? '';


                            $color_1 = get_color($aft_dim_s_pct, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                            $color_2 = get_color($aft_dim_p_pct, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                            $color_3 = get_color($forward_dim_s_pct, $data['new_plate'] ?? null, $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                            $color_4 = get_color($forward_dim_p_pct, $data['new_plate'] ?? null, $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                            $color_5 = get_color($mean_dim_p, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                            $color_6 = get_color($mean_dim_s, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);

                            ?>

                            <tr>

                              <?php if($unit_type == 'free_text') {?>
                                <td class="text-left"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                              <?php } else { ?>
                                <td><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                              <?php } ?>

                              <td><?=$data['no_letter'];?></td>
                              <td><?=$data['org_thickness'];?></td>
                              <td><?=$data['min_thickness'];?></td>

                              <td><?=$data['aft_gauged_p'];?></td>
                              <td><?=$data['aft_gauged_s'];?></td>
                              <td><?=$data['aft_dim_lvl'] ? $data['aft_dim_lvl'].'%':'';?></td>

                              <td><?=$data['aft_dim_p_mm'];?></td>
                              <td class="<?=$color_2;?>"><?=$data['aft_dim_p_pct'] ? $data['aft_dim_p_pct'].'%':'';?></td>

                              <td><?=$data['aft_dim_s_mm'];?></td>
                              <td class="<?=$color_1;?>"><?=$data['aft_dim_s_pct'] ? $data['aft_dim_s_pct'].'%':'';?></td>


                              <td><?=$data['forward_gauged_p'];?></td>
                              <td><?=$data['forward_gauged_s'];?></td>

                              <td><?=$data['forward_dim_lvl'] ? $data['forward_dim_lvl'].'%':'';?></td>

                              <td><?=$data['forward_dim_p_mm'];?></td>
                              <td class="<?=$color_4;?>"><?=$data['forward_dim_p_pct'] ? $data['forward_dim_p_pct'].'%':'';?></td>

                              <td><?=$data['forward_dim_s_mm'];?></td>
                              <td class="<?=$color_3;?>"><?=$data['forward_dim_s_pct'] ? $data['forward_dim_s_pct'].'%':'';?></td>


                              <td class="<?=$color_5;?>"><?=$data['mean_dim_p'] ? $data['mean_dim_p'].'%':'';?></td>
                              <td class="<?=$color_6;?>"><?=$data['mean_dim_s'] ? $data['mean_dim_s'].'%':'';?></td>


                            </tr>
                          <?php }else{ ?>
                            <tr>
                              <td><?=$unit['unit_position_text'][$position];?></td>
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

                          <?php }?>

                        <?php }?>

                        <tr class="total_tr">
                          <td class="text-center" style="border-right-color:transparent;">
                            <b>Total</b> 
                          </td>
                          <td colspan="16" class="text-right" style="border-right-color:transparent;">
                          </td>
                          <td colspan="3">

                            <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                          </td>
                        </tr>

                      </tbody>
                    </table>

                    <table class="report_signature_table">
                      <tr>
                        <td class="text-center" style="width:180px;vertical-align: bottom;">
                          <?=signature($form->id, 'operator');?>
                          <hr>
                          Inspektor / Operator
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                          <?=qr_code($form->id);?>
                        </td>
                        <td class="text-center" style="width:180px;vertical-align: bottom;">
                          <?=signature($form->id, 'surveyor');?>
                          <hr>
                          Surveyor
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="pagebreak"></div>
                  <?php


                }
                if($form_category->form_data_format == 'two')
                {

                  $form_data = $form ? $form->form_data_two->keyBy('port_side')->toArray() : [];

                  ?>

                  <div class="">




                    <table class="header_table">
                      <tr>
                        <td class="logo">
                          <img src="<?=url('/');?>/images/logobki_new.png">
                        </td>
                        <td class="bki_title">
                          PT. BIRO KLASIFIKASI
                          <br>
                          INDONESIA
                        </td>
                        <td class="form_title text-right align-top">
                          <?=$category->name;?>
                        </td>
                      </tr>

                    </table>
                    <div class="text-center" style="padding:10px;font-weight:bold;"><?=$category->description;?></div>
                    <table class="sub_header_table">
                      <tr>
                        <td>
                          <b> Ship's Name : </b>
                          <?=$ship->name;?>
                        </td>
                        <td>
                          <b> IMO No. : </b>
                          <?=$ship->no_imo;?>
                        </td>
                        <td>
                          <b> Report No. : </b>
                          <?=$report->report_number;?>
                        </td>
                      </tr>
                    </table>


                    <table class="form_table <?=get_font_size($form->total_line, 'two');?>" border="1">
                      <thead>
                        <tr>
                          <th colspan="11" class="text-left">
                            <?=$form->name;?>
                          </th>
                        </tr>
                        <?php if($form->title_1){?>
                          <tr>
                            <th colspan="11" class="text-left">
                              <?=$form->title_1;?>
                            </th>
                          </tr>
                        <?php } ?>
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
                        $unit_type  = $form->form_type->unit_type;


                        $form_data = $form->form_data_two->keyBy('plate_position')->toArray();                                
                        $unit        = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);                                                            
                        foreach ($unit['unit_position'] as $position) {

                          if(isset($form_data[$position]))
                          {
                            $data = $form_data[$position];

                            $dim_lvl = $data['dim_lvl'] ?? '';
                            $dim_p_pct = $data['dim_p_pct'] ?? '';
                            $dim_s_pct = $data['dim_s_pct'] ?? '';

                            $color_1 = get_color($dim_p_pct, $data['new_plate'] ?? null, $dim_lvl, $data['dim_lvl_unit'] ?? '%', $data['org_thickness']);
                            $color_2 = get_color($dim_s_pct, $data['new_plate'] ?? null, $dim_lvl, $data['dim_lvl_unit'] ?? '%', $data['org_thickness']);

                            ?>

                            <?php if($unit_type == 'free_text') {?>
                              <td class="text-left"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                            <?php } else { ?>
                              <td><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                            <?php } ?>

                            <td><?=$data['item_no'] ?? '';?></td>
                            <td><?=$data['org_thickness'] ?? '';?></td>
                            <td><?=$data['min_thickness'] ?? '';?></td>
                            <td><?=$data['gauged_p'] ?? '';?></td>
                            <td><?=$data['gauged_s'] ?? '';?></td>
                            <td><?=$data['dim_lvl'] ? $data['dim_lvl'].'%' : '';?></td>
                            <td><?=$data['dim_p_mm'] ?? '';?></td>
                            <td class="<?=$color_1;?>"><?=$data['dim_p_pct'] ? $data['dim_p_pct'].'%' : '';?></td>
                            <td><?=$data['dim_s_mm'] ?? '';?></td>
                            <td class="<?=$color_2;?>"><?=$data['dim_s_pct'] ? $data['dim_s_pct'].'%' : '';?></td>


                          </tr>
                        <?php }else{ ?>
                          <tr>
                            <?php if($unit_type == 'free_text') {?>

                              <td><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                            <?php } else { ?>
                              <td><?=$unit['unit_position_text'][$position];?>&nbsp;</td>

                            <?php } ?>
                            <?php for($z=1;$z<=10;$z++)
                            { ?>
                              <td>&nbsp;</td>
                            <?php } ?>

                          </tr>

                        <?php }?>

                      <?php }?>


                      <tr class="total_tr">
                        <td class="text-center" style="border-right-color:transparent;">
                          <b>Total</b>
                        </td>
                        <td colspan="8" class="text-right" style="border-right-color:transparent;">
                        </td>
                        <td colspan="3">

                          <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                        </td>
                      </tr>


                    </tbody>
                  </table>    


                  <table class="report_signature_table">
                    <tr>
                      <td class="text-center" style="width:180px;vertical-align: bottom;">
                        <?=signature($form->id, 'operator');?>
                        <hr>
                        Inspektor / Operator
                      </td>
                      <td class="text-center" style="vertical-align: middle;">
                        <?=qr_code($form->id);?>
                      </td>
                      <td class="text-center" style="width:180px;vertical-align: bottom;">
                        <?=signature($form->id, 'surveyor');?>
                        <hr>
                        Surveyor
                      </td>
                    </tr>
                  </table>

                </div>
                <div class="pagebreak"></div>


                <?php
              }


              if($form_category->form_data_format == 'three')
              {
                $form_data = $form->form_data_three->keyBy('plate_position')->toArray();
                ?>



                <div class="landscape">



                  <table class="header_table">
                    <tr>
                      <td class="logo">
                        <img src="<?=url('/');?>/images/logobki_new.png">
                      </td>
                      <td class="bki_title">
                        PT. BIRO KLASIFIKASI
                        <br>
                        INDONESIA
                      </td>
                        <td class="form_title text-right align-top">
                          <?=$category->name;?>
                        </td>
                    </tr>

                  </table>
                  <div class="text-center" style="padding:10px;font-weight:bold;"><?=$category->description;?></div>
                  <table class="sub_header_table">
                    <tr>
                      <td>
                        <b> Ship's Name : </b>
                        <?=$ship->name;?>
                      </td>
                      <td>
                        <b> IMO No. : </b>
                        <?=$ship->no_imo;?>
                      </td>
                      <td>
                        <b> Report No. : </b>
                        <?=$report->report_number;?>
                      </td>
                    </tr>
                  </table>

                  <table class="form_table <?=get_font_size($form->total_line, 'three');?>" border="1">
                    <thead>
                      <tr>
                        <th colspan="31" class="form_title" style="border-right:none;">
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
                        <th rowspan="3" class="rotate"><span><?=$form->form_type->number_wording;?></span></th>
                        <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
                        <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
                        <th colspan="2">Gauged</th>
                        <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
                        <th colspan="2">Diminution<br>P</th>
                        <th colspan="2">Diminution<br>S</th>

                        <th rowspan="3" class="rotate"><span><?=$form->form_type->number_wording;?></span></th>
                        <th rowspan="3" class="rotate"><span>ORG THK (mm)</span></th>
                        <th rowspan="3" class="rotate"><span>MIN. THK (mm)</span></th>
                        <th colspan="2">Gauged</th>
                        <th rowspan="2" class="rotate"><span>Diminution Level</span></th>
                        <th colspan="2">Diminution<br>P</th>
                        <th colspan="2">Diminution<br>S</th>

                        <th rowspan="3" class="rotate"><span><?=$form->form_type->number_wording;?></span></th>
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
                      $unit_type  = $form->form_type->unit_type;
                      $unit       = get_unit_data($form->total_line, $form->form_type->unit_type);                                
                      foreach ($unit['unit_position'] as $position) {



                        if(isset($form_data[$position]))
                        {
                          $data = $form_data[$position];
                          ?>
                          <tr>
                            <?php if($unit_type == 'free_text') {?>
                              <td><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                            <?php } else if($unit_type == 'strake') { ?>
                              <td class="text-left"><?=ucwords(str_replace('_', ' ', $unit['unit_position_text'][$position]));?></td>
                            <?php } else { ?>
                              <td><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                            <?php } ?>

                            <?php for($zz=1;$zz<=3;$zz++){?>


                              <?php 

                              $dim_lvl = $data['dim_lvl_'.$zz] ?? '';
                              $dim_p_pct = $data['dim_p_pct_'.$zz] ?? '';
                              $dim_s_pct = $data['dim_s_pct_'.$zz] ?? '';

                              $color_1 = get_color($dim_p_pct, $data['new_plate'] ?? null, $dim_lvl, $data['dim_lvl_unit_'.$zz] ?? '%', $data['org_thickness_'.$zz]);
                              $color_2 = get_color($dim_s_pct, $data['new_plate'] ?? null, $dim_lvl, $data['dim_lvl_unit_'.$zz] ?? '%', $data['org_thickness_'.$zz]);

                              ?>
                              <td><?=$data['item_no_'.$zz] ?? '';?></td>
                              <td><?=$data['org_thickness_'.$zz] ?? '';?></td>
                              <td><?=$data['min_thickness_'.$zz] ?? '';?></td>
                              <td><?=$data['gauged_p_'.$zz] ?? '';?></td>
                              <td><?=$data['gauged_s_'.$zz] ?? '';?></td>
                              <td><?=$data['dim_lvl_'.$zz] ? $data['dim_lvl_'.$zz].'%' : '';?></td>
                              <td><?=$data['dim_p_mm_'.$zz] ?? '';?></td>
                              <td class="<?=$color_1;?>"><?=$data['dim_p_pct_'.$zz] ? $data['dim_p_pct_'.$zz].'%' : '';?></td>
                              <td><?=$data['dim_s_mm_'.$zz] ?? '';?></td>
                              <td class="<?=$color_2;?>"><?=$data['dim_s_pct_'.$zz] ? $data['dim_s_pct_'.$zz].'%' : '';?></td>


                            <?php }?>




                          </tr>
                        <?php }else{ ?>
                          <tr>
                            <td>&nbsp;</td>
                            <?php for($z=1;$z<=30;$z++)
                            { ?>
                              <td>&nbsp;</td>
                            <?php } ?>
                          </tr>
                        <?php }?>

                      <?php }?>

                      <tr class="total_tr">
                        <td class="text-center" style="border-right-color:transparent;">
                          <b>Total</b> 
                        </td>
                        <td colspan="28" class="text-right" style="border-right-color:transparent;">
                        </td>
                        <td colspan="3">

                          <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>


                        </td>
                      </tr>

                    </tbody>
                  </table>

                  <table class="report_signature_table">
                    <tr>
                      <td class="text-center" style="width:180px;vertical-align: bottom;">
                        <?=signature($form->id, 'operator');?>
                        <hr>
                        Inspektor / Operator
                      </td>
                      <td class="text-center" style="vertical-align: middle;">
                        <?=qr_code($form->id);?>
                      </td>
                      <td class="text-center" style="width:180px;vertical-align: bottom;">
                        <?=signature($form->id, 'surveyor');?>
                        <hr>
                        Surveyor
                      </td>
                    </tr>
                  </table>
                </div>

                <div class="pagebreak"></div>
                <?php


              }

            }

          }

        }?>






      </div>
    </div>
    <?php $x++; }?>

  </div>
</div>


</div>













<style type="text/css">

  .tab-pane.fade {
    display: none;
  }
  .tab-pane.show {
    display: block;
  }

  .form_table {
    margin:20px 0 30px 0;
    width: 100%;
  }
  .form_table td , .form_table th {
    text-align: center;
    padding: 1px 4px;
  }  
  .font_16 td , .font_16 th {
    font-size: 16px;
  }
  .font_14 td , .font_14 th {
    font-size: 14px;
  }
  .font_12 td , .font_12 th {
    font-size: 12px;
  }
  .font_10 td , .font_10 th {
    font-size: 10px;
  }
  .font_8 td , .font_8 th {
    font-size: 8px;
  }


  .header_table {
    margin-top: 100px;
  }

  .add_form_btn {
    float: right;
  }
  .report_container {
    width: 100%;
  }
  .header_description {
    font-style: italic;
    font-weight: normal;
    font-size: smaller;
  }

  .header_table {
    width: 100%;
  }
  .header_table .logo
  {
    width: 80px;
  }
  .header_table .logo img
  {
    width: 60px;
    height: auto;
  }
  .header_table .bki_title {
    width: 300px;
    font-weight: bold;
    padding-left: 20px;
    padding-top: 10px;
  }
  .header_table .form_title {
    text-align: center;
    font-weight: bold;
/*    text-decoration: underline;*/
  }
  .sub_header_table {
    width: 100%;
    margin-top: 8px;
  }
  .sub_header_table td {
    width: 33.33%;
  }
  .image_area {
    margin-top: 120px;
    text-align: center;
  }
  .image_area img {
    width: auto;
    height: 100%;
    margin-bottom: 20px;
    max-width: 1366px;
    max-height: 720px;
  }
  .print_btn {
    position: fixed;
    right: 20px;
    top: 20px;
  }

  @media print {
    .category_title {
      margin-top: 20%;
    }
    @page {
      size: A4 landscape;
    }
    body { 
    }

    .pagebreak {
      clear: both;
      page-break-after: always;
    }

    .rotate {
      transform: rotate(-90deg);
      width: 200mm;
      text-align: center;
    }

    .rotate span{
      -ms-writing-mode: none;
      -webkit-writing-mode: none;
      writing-mode: none;
      transform: rotate(-90deg);
      white-space: nowrap;
    }
  }

</style>


<input type="button" class="btn btn-primary print_btn" value="PRINT REPORT">


<script src="{{ asset('assets/modules/jquery.min.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function(){



    $(".print_btn").click(function () {
      $(this).hide();
      window.print();
      $(this).show();
    });
  });


</script>