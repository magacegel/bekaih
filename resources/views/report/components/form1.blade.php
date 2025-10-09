<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Report</title>
    <style>
        body {
            font-size: 10px;
        }
        table {
            font-size: 9px;
            width: 100%;
            border-collapse: collapse;
        }
        .header_table {
            font-size: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        .sub_header_table {
            font-size: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        .form_table {
            border: 1px solid black;
        }
        .form_table th {
            font-size: 8px;
            padding: 2px;
            border: 1px solid black;
        }
        .form_table td {
            font-size: 8px;
            padding: 2px;
            border: 1px solid black;
        }
        .report_signature_table {
            font-size: 10px;
            /* margin-top: 10px; */
            width: 100%;
            border-collapse: collapse;
        }

        .qrtd {
            padding: 0 10px !important;
            margin-left: 50px !important;
        }

        .logo img {
            height: 40px;
            width: auto;
        }
        .landscape_one {
            margin-bottom: 20px;
        }
        .landscape_three {
            margin-bottom: 20px;
        }
        .rotate_title img {
            width: auto;
            height: 80px;
        }
        .paging {
            text-align: center;
            font-weight: bold;
        }
        .image_area {
            width: 100%;
            height: auto;
            margin: 0 0;
        }
        .image_area img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* COLORING */
        .green {
            background-color: rgba(0, 128, 0, 0.3);
        }
        .no-color {
            background-color: transparent;
        }
        .yellow {
            background-color: rgba(255, 255, 0, 0.5);
        }
        .red {
            background-color: rgba(255, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <?php
    foreach ($categories as $category) {
        $report_image = $report_images[$category->id] ?? '';
        $image_url = $report_image ? $report_image['url'] : '';
        $form_categories = $form_data_all[$category->id] ?? '';
        ?>

        <?php
        $hasForm = false;
        if ($form_categories) {
            foreach ($form_categories as $form_category) {
                if (count($form_category->form) > 0) {
                    $hasForm = true;
                    break;
                }
            }
        }
        ?>
        <?php if($hasForm): ?>
            <div style="text-align: center; padding-top: 300px;">
                <h1 style="margin-bottom: 10px; font-size: 24px;"><?= $category->name ?></h1>
                <h3 style="margin: 0; font-size: 20px;"><?= $category->title ?></h3>
            </div>
            <div style="page-break-after: always;"></div>
        <?php endif; ?>
        <?php
        if ($form_categories) {
            foreach ($form_categories as $form_category) {
                foreach ($category->images as $image) {
                    if ($image->form_type_id == $form_category->id) {
                        $imageUrl = $image->url_resized ? Storage::disk('digitalocean')->temporaryUrl($image->url_resized, now()->addMinutes(5)) :
                                  Storage::disk('digitalocean')->temporaryUrl($image->url, now()->addMinutes(5));

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $imageUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $imageData = curl_exec($ch);
                        curl_close($ch);

                        if ($imageData !== false) {
                            $imageBase64 = base64_encode($imageData);
                            $imageDataUri = 'data:image/png;base64,' . $imageBase64;
                        } else {
                            $imageDataUri = '';
                        }
                        ?>
                        <div class="image_area">
                            <?php if (!empty($imageDataUri)): ?>
                                <img class="image_category_<?= $category->id; ?>" src="<?= $imageDataUri ?>" />
                            <?php else: ?>
                                <p>Image not available</p>
                            <?php endif; ?>
                        </div>
                        <?php
                    }
                }

                foreach ($form_category->form as $form) {
                    if ($form_category->form_data_format == 'one') {
                        $form_data = $form->form_data_one->keyBy('plate_position')->toArray();
                        $unit_type = $form->form_type->unit_type;
                        $unit = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                        ?>

                        <div class="landscape landscape_one">
                            <table class="header_table">
                                <tr>
                                    <td class="logo">
                                        <?php
                                        $logo_url = $report->user->company->logo_resized ?
                                            Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                            $report->user->company->logo ?
                                                Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                                Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                        );

                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $logo_url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                        $logo = curl_exec($ch);
                                        curl_close($ch);

                                        $base64_logo = base64_encode($logo);
                                        ?>
                                        <img src="data:image/png;base64,<?= $base64_logo ?>">
                                    </td>
                                    <td class="bki_title">
                                        {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
                                    </td>
                                    <td class="form_title align-top" style="text-align:right;">
                                        <?= $category->name; ?>
                                    </td>
                                </tr>
                            </table>

                            <div style="width:100%;padding:10px;font-weight:bold;text-align:center">
                                <?= $category->description; ?>
                            </div>

                            <table class="sub_header_table">
                                <tr>
                                    <td>
                                        <b>Ship's Name:</b>
                                        <?= $ship->name; ?>
                                    </td>
                                    <td>
                                        <b>IMO No.:</b>
                                        <?= $ship->no_imo; ?>
                                    </td>
                                    <td>
                                        <b>Report No.:</b>
                                        <?= $report->report_number; ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="form_table <?= get_font_size($form->total_line, 'one'); ?>" border="1">
                                <thead>
                                    <tr>
                                        <th>STRAKE POSITION</th>
                                        <th colspan="19" class="form_title" style="border-right:none;">
                                            <?= $form->name; ?>
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
                                    foreach ($unit['unit_position'] as $position) {
                                        if (isset($form_data[$position])) {
                                            $data = $form_data[$position];

                                            $aft_dim_lvl = $data['aft_dim_lvl'] ?? '';
                                            $aft_dim_s_pct = $data['aft_dim_s_pct'] ?? '';
                                            $aft_dim_p_pct = $data['aft_dim_p_pct'] ?? '';

                                            $forward_dim_lvl = $data['forward_dim_lvl'] ?? '';
                                            $forward_dim_s_pct = $data['forward_dim_s_pct'] ?? '';
                                            $forward_dim_p_pct = $data['forward_dim_p_pct'] ?? '';

                                            $mean_dim_p = $data['mean_dim_p'] ?? '';
                                            $mean_dim_s = $data['mean_dim_s'] ?? '';

                                            $color_1 = get_color($aft_dim_s_pct, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                            $color_2 = get_color($aft_dim_p_pct, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                            $color_3 = get_color($forward_dim_s_pct, $data['new_plate'], $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                            $color_4 = get_color($forward_dim_p_pct, $data['new_plate'], $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                            $color_5 = get_color($mean_dim_p, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                            $color_6 = get_color($mean_dim_s, $data['new_plate'], $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                            ?>
                                            <tr>
                                                <?php if ($unit_type == 'free_text') { ?>
                                                    <td class="text-left first_column"><?= $data['position_txt'] ?? ''; ?>&nbsp;</td>
                                                <?php } else { ?>
                                                    <td class="first_column"><?= $unit['unit_position_text'][$position]; ?>&nbsp;</td>
                                                <?php } ?>

                                                <td><?= $data['no_letter']; ?></td>
                                                <td><?= $data['org_thickness']; ?></td>
                                                <td><?= $data['min_thickness']; ?></td>

                                                <td><?= $data['aft_gauged_p']; ?></td>
                                                <td><?= $data['aft_gauged_s']; ?></td>
                                                <td><?= $data['aft_dim_lvl'] ? $data['aft_dim_lvl'].'%' : ''; ?></td>

                                                <td><?= $data['aft_dim_p_mm']; ?></td>
                                                <td class="<?= $color_2; ?>"><?= $data['aft_dim_p_pct'] ? $data['aft_dim_p_pct'].'%' : ''; ?></td>

                                                <td><?= $data['aft_dim_s_mm']; ?></td>
                                                <td class="<?= $color_1; ?>"><?= $data['aft_dim_s_pct'] ? $data['aft_dim_s_pct'].'%' : ''; ?></td>

                                                <td><?= $data['forward_gauged_p']; ?></td>
                                                <td><?= $data['forward_gauged_s']; ?></td>

                                                <td><?= $data['forward_dim_lvl'] ? $data['forward_dim_lvl'].'%' : ''; ?></td>

                                                <td><?= $data['forward_dim_p_mm']; ?></td>
                                                <td class="<?= $color_4; ?>"><?= $data['forward_dim_p_pct'] ? $data['forward_dim_p_pct'].'%' : ''; ?></td>

                                                <td><?= $data['forward_dim_s_mm']; ?></td>
                                                <td class="<?= $color_3; ?>"><?= $data['forward_dim_s_pct'] ? $data['forward_dim_s_pct'].'%' : ''; ?></td>

                                                <td class="<?= $color_5; ?>"><?= $data['mean_dim_p'] ? $data['mean_dim_p'].'%' : ''; ?></td>
                                                <td class="<?= $color_6; ?>"><?= $data['mean_dim_s'] ? $data['mean_dim_s'].'%' : ''; ?></td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="first_column"><?= $unit['unit_position_text'][$position]; ?></td>
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
                                        <?php }
                                    } ?>

                                    <tr class="total_tr">
                                        <td class="text-center" style="border-right-color:transparent;">
                                            <b>Total</b>
                                        </td>
                                        <td colspan="16" class="text-right" style="border-right-color:transparent;">
                                        </td>
                                        <td colspan="3">
                                            <span class="total_spot_txt"><?= $form->total_spot ?? '0'; ?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="report_signature_table" style="width:100%;">
                                <tr>
                                    <td style="width:60%;">
                                        <table style="width:100%;">
                                            <tr>
                                                <td class="text-center" style="width:33%;vertical-align: bottom;">
                                                    <?=signature($form->id, 'operator', 'pdf');?>
                                                    <hr>
                                                    Inspektor / Operator
                                                </td>
                                                <td class="text-center qrtd" style="width:34%;vertical-align: middle;">
                                                    <?=qr_code($form->id, 'pdf');?>
                                                </td>
                                                <td class="text-center" style="width:33%;vertical-align: bottom;">
                                                    <?=signature($form->id, 'surveyor', 'pdf');?>
                                                    <hr>
                                                    Surveyor
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width:10%;"></td>
                                    <td style="width:30%; vertical-align: middle;">
                                        <b style="margin-bottom: 5px;">Legend:</b><br><br>
                                        <span style="background-color: rgba(255, 0, 0, 0.3);">&nbsp;&nbsp;R&nbsp;&nbsp;</span> : Area To Be Repaired<br><br>
                                        <span style="background-color: rgba(255, 255, 0, 0.5);">&nbsp;&nbsp;S&nbsp;&nbsp;</span> : Suspect Area<br><br>
                                        <span style="background-color: rgba(0, 128, 0, 0.3);">&nbsp;&nbsp;N&nbsp;&nbsp;</span> : New Plate
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }
                    if ($form_category->form_data_format == 'two') {
                        $form_data = $form->form_data_two ? $form->form_data_two->keyBy('plate_position')->toArray() : [];
                        $unit_type = $form->form_type->unit_type;
                        $unit = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                        $page = 1;
                        $total_page = 2;
                        ?>

                        <div class="landscape_two">
                            <table class="header_table">
                                <tr>
                                    <td class="logo">
                                        <?php
                                        $logo_url = $report->user->company->logo_resized ?
                                            Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                            $report->user->company->logo ?
                                                Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                                Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                        );

                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $logo_url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                        $logo = curl_exec($ch);
                                        curl_close($ch);

                                        $base64_logo = base64_encode($logo);
                                        ?>
                                        <img src="data:image/png;base64,<?= $base64_logo ?>">
                                    </td>
                                    <td class="bki_title">
                                        {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
                                    </td>
                                    <td class="form_title align-top" style="text-align:right;">
                                        <?=$category->name;?>
                                    </td>
                                </tr>
                            </table>

                            <div style="width:100%;padding:10px;font-weight:bold;text-align: center"><?=$category->description;?></div>

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
                                    foreach ($unit['unit_position'] as $position)
                                    {
                                      if(isset($form_data[$position]))
                                      {
                                        $data = $form_data[$position];

                                        $dim_lvl = $data['dim_lvl'] ?? '';
                                        $dim_p_pct = $data['dim_p_pct'] ?? '';
                                        $dim_s_pct = $data['dim_s_pct'] ?? '';

                                        $color_1 = get_color($dim_p_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                        $color_2 = get_color($dim_s_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit'] ?? '%', $data['org_thickness']);

                                        ?>
                                        <tr>
                                          <?php if($unit_type == 'free_text') {?>
                                            <td class="text-left first_column"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                          <?php } else { ?>
                                            <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
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
                                            <td class="first_column">&nbsp;</td>
                                          <?php } else { ?>
                                            <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                          <?php } ?>
                                          <?php for($z=1;$z<=10;$z++) { ?>
                                            <td>&nbsp;</td>
                                          <?php } ?>
                                        </tr>
                                      <?php }?>
                                    <?php } ?>

                                    <tr class="total_tr">
                                        <td class="text-center" style="border-right-color:transparent;">
                                            <b>Total</b>
                                        </td>
                                        <td colspan="8" class="text-right" style="border-right-color:transparent;">
                                        </td>
                                        <td colspan="2">
                                            <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="report_signature_table" style="width:100%;">
                                <tr>
                                    <td style="width:60%;">
                                        <table style="width:100%;">
                                            <tr>
                                                <td class="text-center" style="width:33%;vertical-align: bottom;">
                                                    <?=signature($form->id, 'operator', 'pdf');?>
                                                    <hr>
                                                    Inspektor / Operator
                                                </td>
                                                <td class="text-center qrtd" style="width:34%;vertical-align: middle;">
                                                    <?=qr_code($form->id, 'pdf');?>
                                                </td>
                                                <td class="text-center" style="width:33%;vertical-align: bottom;">
                                                    <?=signature($form->id, 'surveyor', 'pdf');?>
                                                    <hr>
                                                    Surveyor
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width:10%;"></td>
                                    <td style="width:30%; vertical-align: middle;">
                                        <b style="margin-bottom: 5px;">Legend:</b><br><br>
                                        <span style="background-color: rgba(255, 0, 0, 0.3);">&nbsp;&nbsp;R&nbsp;&nbsp;</span> : Area To Be Repaired<br><br>
                                        <span style="background-color: rgba(255, 255, 0, 0.5);">&nbsp;&nbsp;S&nbsp;&nbsp;</span> : Suspect Area<br><br>
                                        <span style="background-color: rgba(0, 128, 0, 0.3);">&nbsp;&nbsp;N&nbsp;&nbsp;</span> : New Plate
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }
                    if($form_category->form_data_format == 'three'){
                        $form_data = $form->form_data_three ? $form->form_data_three->keyBy('plate_position')->toArray() : [];
                        $unit_type = $form->form_type->unit_type;
                        $unit = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                        $page = 1;
                        $total_page = 1;
                        ?>
                        <div class="landscape_three">
                            <table class="header_table">
                                <tr>
                                    <td class="logo">
                                        <?php
                                        $logo_url = $report->user->company->logo_resized ?
                                            Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                            $report->user->company->logo ?
                                                Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                                Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                        );

                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $logo_url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                        $logo = curl_exec($ch);
                                        curl_close($ch);

                                        $base64_logo = base64_encode($logo);
                                        ?>
                                        <img src="data:image/png;base64,<?= $base64_logo ?>">
                                    </td>
                                    <td class="bki_title">
                                        {!! $report->user->company->name ?? '' !!}<br>{!! $report->user->company->branch ?? '' !!}
                                    </td>
                                    <td class="form_title align-top" style="text-align:right;">
                                        <?=$category->name;?>
                                    </td>
                                </tr>
                            </table>

                            <div style="width:100%;padding:5px;font-weight:bold;text-align: center"><?=$category->description;?></div>

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

                            <table class="form_table form_table_three <?=get_font_size($form->total_line, 'three');?>" border="1">
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
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th colspan="2"><span>Gauged</span></th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th colspan="2"><span>Diminution P</span></th>
                                        <th colspan="2"><span>Diminution S</span></th>

                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th colspan="2"><span>Gauged</span></th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th colspan="2"><span>Diminution P</span></th>
                                        <th colspan="2"><span>Diminution S</span></th>

                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/no_or_letter.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/org_thk.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/min_thk.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th colspan="2"><span>Gauged</span></th>
                                        <th rowspan="2" class="rotate_title">
                                            <img src="<?=public_path('images');?>/dim_lvl.jpg" style="width: auto;height: 80px;">
                                        </th>
                                        <th colspan="2"><span>Diminution P</span></th>
                                        <th colspan="2"><span>Diminution S</span></th>
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
                                    foreach ($unit['unit_position'] as $position) {
                                        if(isset($form_data[$position])) {
                                            $data = $form_data[$position];
                                            ?>
                                            <tr>
                                                <?php if($unit_type == 'free_text') {?>
                                                    <td class="first_column"><?=$data['position_txt'] ?? '';?>&nbsp;</td>
                                                <?php } else if($unit_type == 'strake') { ?>
                                                    <td class="text-left first_column"><?=ucwords(str_replace('_', ' ', $unit['unit_position_text'][$position]));?></td>
                                                <?php } else { ?>
                                                    <td class="first_column"><?=$unit['unit_position_text'][$position];?>&nbsp;</td>
                                                <?php } ?>

                                                <?php for($zz=1;$zz<=3;$zz++){
                                                    $dim_lvl = $data['dim_lvl_'.$zz] ?? '';
                                                    $dim_p_pct = $data['dim_p_pct_'.$zz] ?? '';
                                                    $dim_s_pct = $data['dim_s_pct_'.$zz] ?? '';

                                                    $color_1 = get_color($dim_p_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit_'.$zz] ?? '%', $data['org_thickness_'.$zz]);
                                                    $color_2 = get_color($dim_s_pct, $data['new_plate'], $dim_lvl, $data['dim_lvl_unit_'.$zz] ?? '%', $data['org_thickness_'.$zz]);
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
                                                <?php for($z=1;$z<=30;$z++) { ?>
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
                                        <td colspan="2">
                                            <span class="total_spot_txt"><?=$form->total_spot ?? '0';?></span> &nbsp; SPOT<span class="spot_s" style="display:none;">S</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="report_signature_table" style="width:100%;">
                                <tr>
                                    <td style="width:60%;">
                                        <table style="width:100%;">
                                            <tr>
                                                <td class="text-center" style="width:33%;vertical-align: bottom;">
                                                    <?=signature($form->id, 'operator', 'pdf');?>
                                                    <hr>
                                                    Inspektor / Operator
                                                </td>
                                                <td class="text-center qrtd" style="width:34%;vertical-align: middle;">
                                                    <?=qr_code($form->id, 'pdf');?>
                                                </td>
                                                <td class="text-center" style="width:33%;vertical-align: bottom;">
                                                    <?=signature($form->id, 'surveyor', 'pdf');?>
                                                    <hr>
                                                    Surveyor
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width:10%;"></td>
                                    <td style="width:30%; vertical-align: middle;">
                                        <b style="margin-bottom: 5px;">Legend:</b><br><br>
                                        <span style="background-color: rgba(255, 0, 0, 0.3);">&nbsp;&nbsp;R&nbsp;&nbsp;</span> : Area To Be Repaired<br><br>
                                        <span style="background-color: rgba(255, 255, 0, 0.5);">&nbsp;&nbsp;S&nbsp;&nbsp;</span> : Suspect Area<br><br>
                                        <span style="background-color: rgba(0, 128, 0, 0.3);">&nbsp;&nbsp;N&nbsp;&nbsp;</span> : New Plate
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }
                    ?>
                    <div style="position: absolute; bottom: 10px; left: 10px; font-size: 8pt; color: #666; font-style: italic;">
                        *Dokumen ini ditandatangani secara digital
                    </div>
                    <div style="page-break-after: always;"></div>
                    <?php
                }
            }
        }
    }
    ?>
</body>
</html>
