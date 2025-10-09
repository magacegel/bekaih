<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
    $show_total_page = false;
    $limit_page_break = 20;
    $x = 0;

    foreach ($categories as $category) {
        $report_image = $report_images[$category->id] ?? '';
        $image_url = $report_image ? $report_image['url'] : '';
    ?>
        <div class="tab-pane">
            <div class="report_container">
                <div class="category_title_area" style="width:100%;margin-top:80px;margin-bottom:80px;">
                    <h4 class="category_title" style="text-align:center;">
                        <?= $category->name; ?>
                        <br>
                        <?= $category->title; ?>
                    </h4>
                </div>

                <?php
                $form_categories = $form_data_all[$category->id] ?? '';
                if ($form_categories) {
                    foreach ($form_categories as $form_category) {
                        foreach ($category->images as $image) {
                            if ($image->form_type_id == $form_category->id) {
                ?>
                                <div class="image_area">
                                    <?php
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
                                    <?php if (!empty($imageDataUri)): ?>
                                        <img class="image_category_<?= $category->id; ?>" src="<?= $imageDataUri ?>" />
                                    <?php else: ?>
                                        <p>Image not available</p>
                                    <?php endif; ?>
                                </div>
                <?php
                            }
                        }

                        $status = ($form->surveyor_verifikasi ?? 'waiting_for_approval');
                        foreach ($form_category->form as $form) {
                            if ($form_category->form_data_format == 'one') {
                                $form_data = $form->form_data_one->keyBy('plate_position')->toArray();
                                $unit_type = $form->form_type->unit_type;
                                $unit = get_unit_data($form->total_line, $unit_type, $form->form_type->unit_prefix);
                                $page = 1;
                                $total_page = (int)ceil(count($unit['unit_position'])/$limit_page_break);
                ?>
                                <div class="landscape landscape_one">
                                    <table class="header_table">
                                        <tr>
                                            <td class="logo">
                                                <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                                           $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                                           Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                                         ) ?>">
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
                                            $line = 0;
                                            $current_page = 1;
                                            foreach ($unit['unit_position'] as $position) {
                                                $line++;
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

                                                    $color_1 = get_color($aft_dim_s_pct, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                                    $color_2 = get_color($aft_dim_p_pct, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                                    $color_3 = get_color($forward_dim_s_pct, $data['new_plate'] ?? null, $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                                    $color_4 = get_color($forward_dim_p_pct, $data['new_plate'] ?? null, $forward_dim_lvl, $data['forward_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                                    $color_5 = get_color($mean_dim_p, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
                                                    $color_6 = get_color($mean_dim_s, $data['new_plate'] ?? null, $aft_dim_lvl, $data['aft_dim_lvl_unit'] ?? '%', $data['org_thickness']);
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
                                                <?php } ?>

                                                <?php if ($line % $limit_page_break == 0 && $current_page <= $total_page) {
                                                    $current_page++;
                                                    if ($total_page > 1 && $show_total_page) { ?>
                                                        <tr>
                                                            <td colspan="20" class="paging">
                                                                Table <?= $page++; ?> of <?= $total_page; ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>

                                                <table class="report_signature_table">
                                                    <tr>
                                                        <td class="text-center" style="width:250px;vertical-align:bottom;line-height:12px;font-size:12px;">
                                                            <?= signature($form->id, 'operator', 'pdf'); ?>
                                                            <hr>
                                                            Inspektor / Operator
                                                        </td>
                                                        <td class="text-center" style="vertical-align:middle;">
                                                            <?= qr_code($form->id, 'pdf'); ?>
                                                        </td>
                                                        <td class="text-center" style="width:250px;vertical-align:bottom;line-height:12px;font-size:12px;">
                                                            <?= signature($form->id, 'surveyor', 'pdf'); ?>
                                                            <hr>
                                                            Surveyor
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="landscape landscape_one">
                                                <table class="header_table">
                                                    <tr>
                                                        <td class="logo">
                                                            <img src="<?= $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                                                                       $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                                                                       Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                                                                     ) ?>">
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
                                                <?php } ?>
                                            <?php } ?>

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

                                            <?php if ($total_page > 1 && $show_total_page) { ?>
                                                <tr>
                                                    <td colspan="20" class="paging">
                                                        Table <?= $page++; ?> of <?= $total_page; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <table class="report_signature_table">
                                        <tr>
                                            <td class="text-center" style="width:250px;vertical-align:bottom;line-height:12px;font-size:12px;">
                                                <?= signature($form->id, 'operator', 'pdf'); ?>
                                                <hr>
                                                Inspektor / Operator
                                            </td>
                                            <td class="text-center" style="vertical-align:middle;">
                                                <?= qr_code($form->id, 'pdf'); ?>
                                            </td>
                                            <td class="text-center" style="width:250px;vertical-align:bottom;line-height:12px;font-size:12px;">
                                                <?= signature($form->id, 'surveyor', 'pdf'); ?>
                                                <hr>
                                                Surveyor
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                <?php
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    <?php
        $x++;
    }
    ?>
</body>
</html>
