@extends('template.main')


@section('custom-css')

@endsection


@section('content-title')
Form Report - <?=$category->name;?>
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="<?=url('/');?>">Home</a></li>
<li class="breadcrumb-item"><a href="<?=url('/report');?>">Report</a></li>
<li class="breadcrumb-item"><a href="<?=url('/report_detail');?>/<?=base64_encode($report->id);?>">Report Detail</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')





<style type="text/css">

  table input[type=text]
  {
    width:40px;
    border:1px solid #777;
    text-align: center;
  }
  .input_form {
    background: #f1feff;
  }
  .table {
    border-collapse: collapse;
  }
  .table td {
    padding: 3px;
  }

  .form_name {
    width: 100% !important;
  }
  .amid {
    background-color: #e8fafc;
  }
  .bki_logo {
    width: 100px;
    height: 100px;
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

</style>



<form id="form_data">

  <input type="hidden" name="_token" value="<?=csrf_token();?>">
  <input type="hidden" name="category_id" value="<?=$category->id;?>">
  <input type="hidden" name="report_id" value="<?=$report->id;?>">
  <input type="hidden" name="limit" value="<?=$limit;?>">



  <table class="header_table">
    <tr>
      <td>
        <div class="bki_logo">

        </div>
      </td>
      <td>PT Biro Klasifikasi<br>Indonesia</td>
      <td>
        <h4><?=$report->name;?></h4>
      </td>

      <td style="width:20px">
        <input type="submit" class="btn btn-sm btn-primary" value="Create Form">
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



  <table class="form_table" border="1">
    <thead>
      <tr>
        <th>Strake<br>Position</th>
        <th colspan="18" class="form_title"><input type="text" class="form_name" name="form_name" placeholder="Form Title"></th>
        <th rowspan="4" colspan="2">Max<br>Allwb.<br>Dim. mm</th>
      </tr>
      <tr>
        <th rowspan="3">Plate<br>Position</th>
        <th rowspan="3">No<br>or<br>Letter</th>
        <th rowspan="3">Org.<br>Thk.<br>mm</th>
        <th colspan="7">AFTER READING</th>
        <th colspan="7">FORWARD READING</th>
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



      <?php foreach ($plate_position as $position) {
        $line = $plate_position_input[$position];
        ?>

        <tr class="<?=$line;?>" line="<?=$line;?>">
          <td><?=$plate_position_text[$position];?></td>


          <td><input type="text" class="input_form no_letter" name="no_letter_<?=$line;?>"></td>
          <td><input type="text" class="input_form org_thickness" name="org_thickness_<?=$line;?>"></td>


          <td><input type="text" class="input_form after_gauged_s" name="after_gauged_s_<?=$line;?>"></td>
          <td><input type="text" class="input_form after_gauged_p" name="after_gauged_p_<?=$line;?>"></td>
          <td><input type="text" class="after_dim_lvl" name="after_dim_lvl_<?=$line;?>">%</td>

          <td>
            <input type="text" class="after_dim_s_mm" name="after_dim_s_mm_<?=$line;?>" style="display: none;">
            <span class="after_dim_s_mm_txt"></span>
          </td>
          <td>
            <input type="text" class="after_dim_s_pct" name="after_dim_s_pct_<?=$line;?>" style="display: none;">
            <span class="after_dim_s_pct_txt"></span>
          </td>
          <td>
            <input type="text" class="after_dim_p_mm" name="after_dim_p_mm_<?=$line;?>" style="display: none;">
            <span class="after_dim_p_mm_txt"></span>
          </td>
          <td>
            <input type="text" class="after_dim_p_pct" name="after_dim_p_pct_<?=$line;?>" style="display: none;">
            <span class="after_dim_p_pct_txt"></span>
          </td>



          <td><input type="text" class="input_form forward_gauged_s" name="forward_gauged_s_<?=$line;?>"></td>
          <td><input type="text" class="input_form forward_gauged_p" name="forward_gauged_p_<?=$line;?>"></td>
          <td><input type="text" class="forward_dim_lvl" name="forward_dim_lvl_<?=$line;?>">%</td>

          <td>
            <input type="text" class="forward_dim_s_mm" name="forward_dim_s_mm_<?=$line;?>" style="display: none;">
            <span class="forward_dim_s_mm_txt"></span>
          </td>
          <td>
            <input type="text" class="forward_dim_s_pct" name="forward_dim_s_pct_<?=$line;?>" style="display: none;">
            <span class="forward_dim_s_pct_txt"></span>
          </td>
          <td>
            <input type="text" class="forward_dim_p_mm" name="forward_dim_p_mm_<?=$line;?>" style="display: none;">
            <span class="forward_dim_p_mm_txt"></span>
          </td>
          <td>
            <input type="text" class="forward_dim_p_pct" name="forward_dim_p_pct_<?=$line;?>" style="display: none;">
            <span class="forward_dim_p_pct_txt"></span>
          </td>


          <td>
            <input type="text" class="mean_dim_s" name="mean_dim_s_<?=$line;?>" style="display: none;">
            <span class="mean_dim_s_txt"></span>
          </td>

          <td>
            <input type="text" class="mean_dim_p" name="mean_dim_p_<?=$line;?>" style="display: none;">
            <span class="mean_dim_p_txt"></span>
          </td>

          <td>
            <input type="text" class="max_allwb" name="max_allwb_<?=$line;?>" style="display: none;">
            <span class="max_allwb_txt"></span>
          </td>





          <td style="width: 10px;">
            <input type="button" class="clear_line" value="X">
          </td>



        </tr>


      <?php }?>



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



@endsection

@section('custom-js')

<script type="text/javascript">
  $(document).ready(function(){



    $('#form_data').submit(function (e){

      e.preventDefault();


      form_name = $('.form_name').val();

      if(!form_name)
      {
        alert('Please input form title');
        $('.form_name').focus();
        return false;
      }



      $.ajax({
        url: "<?=url('/test_form')?>",
        type: 'POST',
        data: new FormData( this ),
        processData: false,
        contentType: false,

        success: function(data) {

          if(data.status == 'success')
          {
            // Kasi notif
            // alert(data.message);
            alert(data.message);
            window.location = data.redirect_url;
          }
          else
          {
            alert('error : '+data.message);
          }
        },

        // error: function (err) {
        //   // Status code is 422 artinya error di validation
        //   if (err.status == 422) {
        //     // Tampilkan error di tiap form
        //     $.each(err.responseJSON.errors, function (i, error) {
        //       var el = $('#test_form').find('.'+i+'');
        //       el.after($('<div class="error_message">'+error[0]+'</div>'));
        //     });
        //     // Menyembunyikan error message setelah beberapa waktu
        //     $('.error_message').delay(5000).fadeOut('slow');
        //   }
        // }
      });

    });



    $('.input_form').change(function(e){
      e.preventDefault();
      line = $(this).parent().parent().attr('line');
      calculate(line);
    });

    function calculate(line)
    {

      org_thickness = $('.'+line+' .org_thickness').val();


      after_dim_lvl = $('.'+line+' .after_dim_lvl').val();
      if(!after_dim_lvl)
      {
        $('.'+line+' .after_dim_lvl').val('20');
      }

      forward_dim_lvl = $('.'+line+' .forward_dim_lvl').val();
      if(!forward_dim_lvl)
      {
        $('.'+line+' .forward_dim_lvl').val('20');
      }



      after_gauged_s = $('.'+line+' .after_gauged_s').val();
      if(org_thickness && after_gauged_s)
      {
        val = (parseFloat(org_thickness) - parseFloat(after_gauged_s)).toFixed(2);
        $('.'+line+' .after_dim_s_mm').val(val);
        $('.'+line+' .after_dim_s_mm_txt').html(val);
      }
      else
      {
        $('.'+line+' .after_dim_s_mm').val('');
        $('.'+line+' .after_dim_s_mm_txt').html('');
      }

      after_dim_s_mm = $('.'+line+' .after_dim_s_mm').val();
      if(org_thickness && after_dim_s_mm)
      {
        val = (parseFloat(after_dim_s_mm)/parseFloat(org_thickness) * 100).toFixed(2);
        $('.'+line+' .after_dim_s_pct').val(val);
        $('.'+line+' .after_dim_s_pct_txt').html(val+'%');
      }
      else
      {
        $('.'+line+' .after_dim_s_pct').val('');
        $('.'+line+' .after_dim_s_pct_txt').html('');
      }

      after_gauged_p = $('.'+line+' .after_gauged_p').val();
      if(org_thickness && after_gauged_p)
      {
        val = (parseFloat(org_thickness) - parseFloat(after_gauged_p)).toFixed(2);
        $('.'+line+' .after_dim_p_mm').val(val);
        $('.'+line+' .after_dim_p_mm_txt').html(val);
      }
      else
      {
        $('.'+line+' .after_dim_p_mm').val('');
        $('.'+line+' .after_dim_p_mm_txt').html('');
      }


      after_dim_p_mm = $('.'+line+' .after_dim_p_mm').val();
      if(org_thickness && after_dim_p_mm)
      {
        val = (parseFloat(after_dim_p_mm)/parseFloat(org_thickness) * 100).toFixed(2);
        $('.'+line+' .after_dim_p_pct').val(val);
        $('.'+line+' .after_dim_p_pct_txt').html(val+'%');
      }
      else
      {
        $('.'+line+' .after_dim_p_pct').val('');
        $('.'+line+' .after_dim_p_pct_txt').html('');
      }





      forward_gauged_s = $('.'+line+' .forward_gauged_s').val();
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

      forward_dim_s_mm = $('.'+line+' .forward_dim_s_mm').val();
      if(org_thickness && forward_dim_s_mm)
      {
        val = (parseFloat(forward_dim_s_mm)/parseFloat(org_thickness) * 100).toFixed(2);
        $('.'+line+' .forward_dim_s_pct').val(val);
        $('.'+line+' .forward_dim_s_pct_txt').html(val+'%');
      }
      else
      {
        $('.'+line+' .forward_dim_s_pct').val('');
        $('.'+line+' .forward_dim_s_pct_txt').html('');
      }

      forward_gauged_p = $('.'+line+' .forward_gauged_p').val();
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


      forward_dim_p_mm = $('.'+line+' .forward_dim_p_mm').val();
      if(org_thickness && forward_dim_p_mm)
      {
        val = (parseFloat(forward_dim_p_mm)/parseFloat(org_thickness) * 100).toFixed(2);
        $('.'+line+' .forward_dim_p_pct').val(val);
        $('.'+line+' .forward_dim_p_pct_txt').html(val+'%');
      }
      else
      {
        $('.'+line+' .forward_dim_p_pct').val('');
        $('.'+line+' .forward_dim_p_pct_txt').html('');
      }


      after_dim_s_pct = $('.'+line+' .after_dim_s_pct').val();
      forward_dim_s_pct = $('.'+line+' .forward_dim_s_pct').val();
      if(after_dim_s_pct && forward_dim_s_pct)
      {
        val = ((parseFloat(after_dim_s_pct)+parseFloat(forward_dim_s_pct)) / 2).toFixed(2);
        $('.'+line+' .mean_dim_s').val(val);
        $('.'+line+' .mean_dim_s_txt').html(val+'%');
      }
      else
      {
        $('.'+line+' .mean_dim_s').val('');
        $('.'+line+' .mean_dim_s_txt').html('');
      }

      after_dim_p_pct = $('.'+line+' .after_dim_p_pct').val();
      forward_dim_p_pct = $('.'+line+' .forward_dim_p_pct').val();
      if(after_dim_p_pct && forward_dim_p_pct)
      {
        val = ((parseFloat(after_dim_p_pct)+parseFloat(forward_dim_p_pct)) / 2).toFixed(2);
        $('.'+line+' .mean_dim_p').val(val);
        $('.'+line+' .mean_dim_p_txt').html(val+'%');
      }
      else
      {
        $('.'+line+' .mean_dim_p').val('');
        $('.'+line+' .mean_dim_p_txt').html('');
      }


      after_dim_lvl = $('.'+line+' .after_dim_lvl').val();
      if(org_thickness && after_dim_lvl)
      {
        val = ((parseFloat(org_thickness)*parseFloat(after_dim_lvl)) / 100).toFixed(2);
        $('.'+line+' .max_allwb').val(val);
        $('.'+line+' .max_allwb_txt').html(val);
      }
      else
      {
        $('.'+line+' .max_allwb').val('');
        $('.'+line+' .max_allwb_txt').html('');
      }

    }


    $('.clear_line').click(function(e){
      e.preventDefault();

      if(confirm('Are you sure want to clear this line?'))
      {
        line = $(this).parent().parent().attr('line');
        $('.'+line+' input[type=text]').val('');
        $('.'+line+' span').html('');
      }

    })

  });
</script>

@endsection
