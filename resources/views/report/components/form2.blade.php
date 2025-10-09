<div class="landscape_two">

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

      <?php }?>



    <?php }?>


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

    <?php if($total_page > 1 && $show_total_page){ ?>
      <tr>
        <td colspan="11" class="paging">

          Table <?=$page++;?> of <?=$total_page;?>
        </td>
      </tr>
    <?php } ?>

  </tbody>
</table>


<table class="report_signature_table">
  <tr>
    <td class="text-center" style="width:250px;vertical-align: bottom;">
      <?=signature($form->id, 'operator', 'pdf');?>
      <hr>
      Inspektor / Operator
    </td>
    <td class="text-center" style="vertical-align: middle;">
      <?=qr_code($form->id, 'pdf');?>
    </td>
    <td class="text-center" style="width:250px;vertical-align: bottom;">
      <?=signature($form->id, 'surveyor', 'pdf');?>
      <hr>
      Surveyor
    </td>
  </tr>
</table>

</div>
