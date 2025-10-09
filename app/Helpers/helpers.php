<?php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Form;
use App\Models\User;

if(!function_exists('ordinal_number'))
{
  function ordinal_number($number)
  {
    if(strlen($number) >= 2 && (substr($number, -2) == 11 || substr($number, -2) == 12 || substr($number, -2) == 13))
    {
      $number .='th';
    }
    elseif (substr($number,-1) == 1) {
      $number .='th';
    }
    elseif (substr($number,-1) == 2) {
      $number .='rd';
    }
    else {
      $number .='th';
    }
    return $number;
  }
}

if(!function_exists('get_plate_data'))
{
  function get_plate_data($limit)
  {

    for($x=$limit; $x>= ($limit*-1);$x--)
    {
      $unit_position[]         = $x;
      $unit_position_text[$x]  = ordinal_number(abs($x));

      if($x == 0) $unit_position_text[$x] = 'Amidships';
      if($x == $limit) $unit_position_text[$x] .= ' Forward';
      if(($x*-1) == $limit) $unit_position_text[$x] .= ' Afterward';


      $unit_position_input[$x] = $x == 0 ? 'amid' : ( $x < 0 ? 'n'.abs($x) : 'p'.$x);
    }

    $data['unit_position'] = $unit_position;
    $data['unit_position_text'] = $unit_position_text;
    $data['unit_position_input'] = $unit_position_input;

    return $data;



  }
}


if(!function_exists('get_unit_data'))
{


  function get_unit_data($total_line, $unit_type, $unit_prefix = '')
  {

    $data = [];

    if($unit_type == 'plate')
    {
      $data = get_plate_data($total_line);

    }
    if($unit_type == 'prefix')
    {
      $unit_position = [];
      $unit_position_text = [];
      $unit_position_input = [];
      for($x=1;$x<=$total_line;$x++)
      {
        $unit_position[] = $x;
        $unit_position_text[$x] =  $unit_prefix.$x;
        $unit_position_input[$x] = 'line'.$x;
      }

      $data['unit_position'] = $unit_position;
      $data['unit_position_text'] = $unit_position_text;
      $data['unit_position_input'] = $unit_position_input;
    }
    if($unit_type == 'free_text')
    {
      $tmp_1 = [];
      $tmp_2 = [];
      for($x=1;$x<=$total_line;$x++)
      {
        $tmp_1[] = $x;
        $tmp_2[$x] = $x;
      }

      $data['unit_position'] = $tmp_1;
      $data['unit_position_text'] = $tmp_2;
      $data['unit_position_input'] = $tmp_2;

    }
    if($unit_type == 'strake')
    {

      $tmp_1 = [];
      $tmp_2 = [];

      $tmp_1[] = 1;
      $tmp_2[1] = 'stringer_plate';

      for($x=2;$x<=($total_line+1);$x++)
      {
        $tmp_1[] = $x;
        $tmp_2[$x] = ordinal($x-1).'_strake_inboard';
      }

      $tmp_1[] = $x;
      $tmp_2[$x] = 'centre_strake';

      $x++;
      $tmp_1[] = $x;
      $tmp_2[$x] = 'sheer_strake';

      $data['unit_position'] = $tmp_1;
      $data['unit_position_text'] = $tmp_2;
      $data['unit_position_input'] = $tmp_2;
    }

    return $data;



  }
}

if(!function_exists('get_color'))
{
  function get_color($input, $new_plate = null, $dim_lvl = null, $dim_lvl_unit = null, $org_thickness = null)
  {
    $color = '';
    if($input)
    {
      $input = floatval($input);

      // Jika new_plate, langsung set warna hijau
      if($new_plate)
      {
        $color = 'green';
        return $color;
      }

      // Jika input negatif
      if($input < 0)
      {
        $color = 'no-color';
        return $color;
      }

      // Jika dim_lvl tersedia, gunakan sebagai threshold
      if($dim_lvl !== null)
      {
        $dim_lvl = floatval($dim_lvl);

        // Konversi dim_lvl ke persentase jika unitnya mm
        if($dim_lvl_unit === 'mm' && $org_thickness !== null)
        {
          $org_thickness = floatval($org_thickness);
          $dim_lvl_pct = ($dim_lvl / $org_thickness) * 100;
        }
        else
        {
          $dim_lvl_pct = $dim_lvl; // Sudah dalam persentase
        }

        // Penentuan warna berdasarkan persentase
        if($input >= 0 && $input < ($dim_lvl_pct * 0.75))
        {
          $color = 'no-color';
        }
        else if($input >= ($dim_lvl_pct * 0.75) && $input < $dim_lvl_pct)
        {
          $color = 'yellow';
        }
        else
        {
          $color = 'red';
        }
      }
      // Jika dim_lvl tidak tersedia, gunakan threshold default (100%)
      else
      {
        if($input >= 0 && $input < 75)
        {
          $color = 'no-color';
        }
        else if($input >= 75 && $input < 100)
        {
          $color = 'yellow';
        }
        else
        {
          $color = 'red';
        }
      }
    }

    return $color;
  }
}


if(!function_exists('qr_code'))
{
  function qr_code($form_id, $output = null)
  {
    $form = Form::where('id',$form_id)->with('report.user', 'report.ship')->first();

    if($form)
    {
      $text = '';
      $text .= $form->report->ship->name.' | ';
      $text .= $form->report->name.' | ';
      $text .= $form->name.' | ';
      if($form->report->user->user_type == 'bki')
      {
        $text .= 'Inspektor : '.$form->report->user->name.' ('.$form->report->user->cif.') | ';
      }
      else
      {
        $text .= 'Operator : '.$form->report->user->name.' ('.$form->report->user->email.') | ';
      }

      if($form->surveyor)
      {
        $text .= 'Surveyor : '.$form->surveyor->nama.' ('.$form->surveyor->nup.')';
      }
      else
      {
        $text .= 'Surveyor : ---';
      }

      if($output == 'pdf')
      {
        return '<img style="margin-left: 70px;width:60px;" src="data:image/png;base64, '.base64_encode(QrCode::size(100)->generate($text)).'} ">';
      }
      else
      {
        return QrCode::generate(
          $text,
        );
      }


    }
    else
    {
      return '';
    }


  }
}

if(!function_exists('signature'))
{
  function signature($form_id, $type, $status = null, $report_id = null)
  {

    if($type == 'operator')
    {
      if ($report_id) {
        $report = \App\Models\Report::findOrFail($report_id);
        $user = $report->user;
      } else {
        $form = Form::where('id',$form_id)->with('report.user')->first();
        $user = $form->report->user;
      }

      if($user)
      {
        $txt = '<table style="margin:0 auto;"><tr><td align="center" height="70px"><div style="position: relative;">';
        if($user->signature)
        {
          if($status == 'pdf')
          {
            $signature_url = Storage::disk('digitalocean')->temporaryUrl($user->signature, now()->addMinutes(5));

            // Download file signature
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $signature_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $signature = curl_exec($ch);
            curl_close($ch);

            if ($signature !== false) {
              $base64_signature = base64_encode($signature);
              $txt .= '<div style="min-height: 65px; display: flex; align-items: center; justify-content: center;">';
              $txt .= '<img src="data:image/png;base64,'.$base64_signature.'" width="150" style="max-height: 90px;" />';
              $txt .= '</div>';
            }
          }
          else
          {
            $signature_url = Storage::disk('digitalocean')->temporaryUrl($user->signature, now()->addMinutes(5));
            $txt .= '<div style="min-height: 65px; display: flex; align-items: center; justify-content: center;"><img src="'.$signature_url.'" alt="image" width="150" style="max-height: 200px;" /></div>';
          }
        }
        else{
          $txt .= '<!-- Tarik tanda tangan manual --!>';
        }
        $txt .= '</div></td></tr><tr><td class="signature_name" align="center" valign="bottom"><b>'.strtoupper($user->name).'</b></td></tr></table>';

        return $txt;
      }
      else
      {
        return '';
      }
    }
    else if($type == 'supervisor')
    {
      if ($report_id) {
        $report = \App\Models\Report::findOrFail($report_id);
        $user = $report->supervisor;
      } else {
        $form = Form::where('id',$form_id)->with('report.supervisor')->first();
        $user = $form->report->supervisor;
      }

      if($user)
      {
        $txt = '<table style="margin:0 auto;"><tr><td align="center" height="70px"><div style="position: relative;">';
        if($user->signature)
        {
          if($status == 'pdf')
          {
            $signature_url = Storage::disk('digitalocean')->temporaryUrl($user->signature, now()->addMinutes(5));

            // Download file signature
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $signature_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $signature = curl_exec($ch);
            curl_close($ch);

            if ($signature !== false) {
              $base64_signature = base64_encode($signature);
              $txt .= '<div style="min-height: 65px; display: flex; align-items: center; justify-content: center;">';
              $txt .= '<img src="data:image/png;base64,'.$base64_signature.'" width="150" style="max-height: 90px;" />';
              $txt .= '</div>';
            }
          }
          else
          {
            $signature_url = Storage::disk('digitalocean')->temporaryUrl($user->signature, now()->addMinutes(5));
            $txt .= '<div style="min-height: 65px; display: flex; align-items: center; justify-content: center;"><img src="'.$signature_url.'" alt="image" width="150" style="max-height: 200px;" /></div>';
          }
        }
        else{
          $txt .= '<!-- Tarik tanda tangan manual --!>';
        }
        $txt .= '</div></td></tr><tr><td class="signature_name" align="center" valign="bottom"><b>'.strtoupper($user->name).'</b></td></tr></table>';

        return $txt;
      }
      else
      {
        return '';
      }
    }
    else if($type == 'surveyor')
    {
      if ($report_id) {
        $report = \App\Models\Report::with('surveyor')->findOrFail($report_id);
        $surveyor = $report->surveyor;
      } else {
        $form = Form::where('id',$form_id)->with('report.surveyor')->first();
        $surveyor = $form->report->surveyor;
      }

      if($surveyor)
      {
        if($status == 'pdf')
        {
          $signature_url = 'https://new.bki.co.id/assets/stamp_ttd/ttd_'.$surveyor->nup.'.png';
          $stamp_url = 'https://new.bki.co.id/assets/stamp_ttd/stamp_'.$surveyor->nup.'.png';

          // Download file signature
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $signature_url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $signature = curl_exec($ch);
          curl_close($ch);

          // Download file stamp
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $stamp_url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $stamp = curl_exec($ch);
          curl_close($ch);

          if($signature !== false && $stamp !== false)
          {
            $base64_signature = base64_encode($signature);
            $base64_stamp = base64_encode($stamp);

            $txt = '<table style="margin:0 auto;width:100%;border-spacing:0;"><tr>';
            $txt .= '<td align="center" style="padding:0;">';
            $txt .= '<div style="position:relative;width:120px;height:70px;margin:0 auto;">';
            $txt .= '<img src="data:image/png;base64,'.$base64_stamp.'" width="120" style="position:absolute;left:0;top:0;" />';
            $txt .= '<img src="data:image/png;base64,'.$base64_signature.'" width="80" style="position:absolute;left:20px;top:10px;" />';
            $txt .= '</div>';
            $txt .= '</td></tr>';
            $txt .= '<tr><td class="signature_name" align="center" style="padding-top:0;"><b>'.strtoupper($surveyor->nama).'</b></td></tr></table>';
          }
          else
          {
            $txt = '<table style="margin:0 auto;width:100%;border-spacing:0;"><tr>';
            $txt .= '<td align="center" style="padding:0;height:70px;"></td></tr>';
            $txt .= '<tr><td class="signature_name" align="center" style="padding-top:0;"><b>'.strtoupper($surveyor->nama).'</b></td></tr></table>';
          }
        }
        else
        {
          $txt = '<table style="margin:0 auto;width:100%;"><tr><td align="center" height="100px"><div style="position: relative;">';
          if($status == 'revised' )
          {

            $stamp_url = public_path('/images').'/need_revision.png';
            $stamp = @file_get_contents($stamp_url);

            $txt .= '<div style="background:transparent url(\'data:image/png;base64,'.base64_encode($stamp).'\') center center no-repeat;background-size:130px auto;min-height: 65px; display: flex; align-items: center; justify-content: center;"></div>';
          }
          if($status == 'waiting_for_approval' )
          {

            $stamp_url = public_path('/images').'/approval_in_progress.png';
            $stamp = @file_get_contents($stamp_url);

            $txt .= '<div style="background:transparent url(\'data:image/png;base64,'.base64_encode($stamp).'\') center center no-repeat;background-size:130px auto;min-height: 65px; display: flex; align-items: center; justify-content: center;"></div>';
          }
          if($status == 'approved')
          {
            $stamp_url = public_path('/images').'/approved.png';
            $stamp = @file_get_contents($stamp_url);
            $txt .= '<div style="background:transparent url(\'data:image/png;base64,'.base64_encode($stamp).'\') center center no-repeat;background-size:100px auto;min-height: 65px; display: flex; align-items: center; justify-content: center;"></div>';
          }
          if($status == 'gp' )
          {

            $stamp_url = public_path('/images').'/approval_in_progress.png';
            $stamp = @file_get_contents($stamp_url);

            $txt .= '<div style="background:transparent url(\'data:image/png;base64,'.base64_encode($stamp).'\') center center no-repeat;background-size:130px auto;min-height: 65px; display: flex; align-items: center; justify-content: center;"></div>';
          }

          $txt .= '</div></td></tr><tr><td class="signature_name" align="center" valign="bottom"><b>'.strtoupper($surveyor->nama).'</b></td></tr></table>';
        }


        return $txt;
      }
      else
      {
        return '';
      }
    }
  }
}


if(!function_exists('ordinal'))
{
  function ordinal($number)
  {

    $suffix = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
      $ordinal = $number . 'th';
    }
    else {
      $ordinal = $number . $suffix[$number % 10];
    }

    return $ordinal;
  }
}
if(!function_exists('get_font_size'))
{
  function get_font_size($total_line, $form_data_format)
  {
    if($form_data_format == 'one')
    {
      // Untuk format "one" total = Atas + Amidships + Bawah
      $total_line = ($total_line * 2) + 1;
    }

    if($form_data_format == 'one' || $form_data_format == 'two')
    {
      if($total_line >19)
      {
        $class = 'font_10';
      }
      else if($total_line >18)
      {
        $class = 'font_11';
      }
      else if($total_line >15)
      {
        $class = 'font_13';
      }
      else
      {
        $class = 'font_14';
      }
    }

    if($form_data_format == 'three')
    {

      if($total_line >18)
      {
        $class = 'font_9';
      }
      else if($total_line >16)
      {
        $class = 'font_10';
      }
      else
      {
        $class = 'font_11';
      }

    }

    return $class;
  }
}

if(!function_exists('sanitize_filename'))
{
  /**
   * Sanitize filename by removing or replacing unsafe characters
   * 
   * @param string $filename
   * @return string
   */
  function sanitize_filename($filename)
  {
    // Get file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Remove or replace unsafe characters
    $unsafe_chars = ['#', '%', '&', '{', '}', '\\', '<', '>', '*', '?', '/', '$', '!', "'", '"', ':', '@', '+', '`', '|', '=', ' '];
    $name = str_replace($unsafe_chars, '_', $name);
    
    // Remove multiple consecutive underscores
    $name = preg_replace('/_+/', '_', $name);
    
    // Remove leading and trailing underscores
    $name = trim($name, '_');
    
    // If name is empty after sanitization, use default
    if (empty($name)) {
      $name = 'file_' . time();
    }
    
    // Return sanitized filename with extension
    return $name . ($extension ? '.' . $extension : '');
  }
}
