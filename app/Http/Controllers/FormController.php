<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FormData;
use App\Models\FormDataOne;
use App\Models\FormDataTwo;
use App\Models\FormDataThree;
use App\Models\Form;
use App\Models\FormType;
use App\Models\Report;
use App\Models\Category;
use App\Models\ShipType;
use App\Models\ShipTypeCategory;

class FormController extends Controller
{

  public function form_data(Request $request)
  {
    $response = [];

    if($request->action == 'delete')
    {

      $form_id = $request->form_id;

      $form = Form::where('id',$form_id)->first();
      if($form)
      {
        if($form->form_type->form_data_format == 'one')
        {
          FormDataOne::where('form_id',$form_id)->delete();
        }

        if($form->form_type->form_data_format == 'two')
        {
          FormDataTwo::where('form_id',$form_id)->delete();
        }

        if($form->form_type->form_data_format == 'three')
        {
          FormDataThree::where('form_id',$form_id)->delete();
        }

        $form->delete();

        $response['status'] = 'success';
        $response['message'] = 'Form '.$form->name.' has been deleted';
      }
      else
      {
        $response['status'] = 'error';
        $response['message'] = 'Form not found';
      }

    }

    if($request->action == 'create')
    {

      $form_type = FormType::find($request->form_type);

      $form_data = [];
      $form_data['category_id']   = $request->category_id;
      $form_data['form_type_id']  = $request->form_type;
      $form_data['report_id']     = $request->report_id;
      $form_data['name']          = $request->form_name;

      $form_data['title_1']       = $form_type->form_data_format == 'two' || $form_type->form_data_format == 'three' ? $request->title_1 : '';
      $form_data['title_2']       = $form_type->form_data_format == 'three' ? $request->title_2 : '';
      $form_data['title_3']       = $form_type->form_data_format == 'three' ? $request->title_3 : '';
      $form_data['total_line']    = $form_type->form_data_format == 'one' ? 14 : ($form_type->form_data_format == 'three' ? 14 : 25);

      $form = Form::create($form_data);
      if($form)
      {
        return redirect()->to(url('/form/edit/').'/'.base64_encode($form->id).'?tab='.$request->category_id);
      }


    }


    if($request->action == 'edit')
    {

      $response['status'] = 'error';
      $response['message'] = '';

      $data = $request->toArray();

      // dd($data);


      $total_line = $request->total_line;
      $tab        = $request->tab ?? '';
      $form       = Form::where('id',$request->form_id)->with('form_type')->first();
      $unit_type  = $form->form_type->unit_type;
      $xx         = 1;


      if($form && $form->form_type->form_data_format == 'one')
      {
        $form->default_dim_lvl        = $request->default_dim_lvl ?? NULL;
        $form->default_org_thickness  = $request->default_org_thickness ?? NULL;
        $form->default_min_thickness  = $request->default_min_thickness ?? NULL;
        $form->total_spot             = $request->total_spot;
        $form->save();

        if($unit_type == 'free_text' || $unit_type == 'prefix') 
        {
          $total = $request->total_line;
          $min = 1;
        }
        else
        {
          $total = $request->total_line;
          $min = $request->total_line*-1;
        }


        for($x=$total; $x>=$min; $x--)
        {

          if($unit_type == 'free_text') {
            $line = $total - $xx + 1;
          }
          else if($unit_type == 'prefix')
          {
            $line = 'line'.($total - $xx + 1);
          }
          else
          {
            $line = $x == 0 ? 'amid' : ( $x < 0 ? 'n'.abs($x) : 'p'.$x);
          }

          $row_data = [
            'form_id' => $form->id,
            'plate_position' => $x,
            'position_txt' => $data['position_txt_'.$line] ?? '',
            'no_letter' => $data['no_letter_'.$line], 
            'org_thickness' => $data['org_thickness_'.$line], 
            'min_thickness' => $data['min_thickness_'.$line], 
            'aft_gauged_s' => $data['aft_gauged_s_'.$line], 
            'aft_gauged_p' => $data['aft_gauged_p_'.$line], 
            'aft_dim_lvl' => $data['aft_dim_lvl_'.$line], 
            'aft_dim_lvl_unit' => $data['aft_dim_lvl_unit_'.$line],
            'aft_dim_s_mm' => $data['aft_dim_s_mm_'.$line], 
            'aft_dim_s_pct' => $data['aft_dim_s_pct_'.$line], 
            'aft_dim_p_mm' => $data['aft_dim_p_mm_'.$line], 
            'aft_dim_p_pct' => $data['aft_dim_p_pct_'.$line], 
            'forward_gauged_s' => $data['forward_gauged_s_'.$line], 
            'forward_gauged_p' => $data['forward_gauged_p_'.$line], 
            'forward_dim_lvl' => $data['forward_dim_lvl_'.$line], 
            'forward_dim_lvl_unit' => $data['forward_dim_lvl_unit_'.$line],
            'forward_dim_s_mm' => $data['forward_dim_s_mm_'.$line], 
            'forward_dim_s_pct' => $data['forward_dim_s_pct_'.$line], 
            'forward_dim_p_mm' => $data['forward_dim_p_mm_'.$line], 
            'forward_dim_p_pct' => $data['forward_dim_p_pct_'.$line], 
            'mean_dim_s' => $data['mean_dim_s_'.$line], 
            'mean_dim_p' => $data['mean_dim_p_'.$line],
            'new_plate' => $data['new_plate_'.$line]
          ];
          $all_data[] = $row_data;
          $xx++;
        }


        FormDataOne::where('form_id', $form->id)->delete();
if(FormDataOne::insert($all_data))
{
  $response['status'] = 'success';
  $response['message'] = 'Form has been updated';
  $response['redirect_url'] = url('/report_detail').'/'.base64_encode($request->report_id).'?tab='.$tab.'&form_id='.$form->id;
}
      }

      if($form && $form->form_type->form_data_format == 'two')
      {
        $form->title_1                = $request->title_1 ?? '';
        $form->default_dim_lvl        = $request->default_dim_lvl ?? NULL;
        $form->default_org_thickness  = $request->default_org_thickness ?? NULL;
        $form->default_min_thickness  = $request->default_min_thickness ?? NULL;
        $form->total_spot             = $request->total_spot;
        $form->save();


        if($unit_type == 'free_text' || $unit_type == 'prefix') 
        {
          $total = $request->total_line;
          $min = 1;
        }
        else
        {
          $total = $request->total_line;
          $min = $request->total_line*-1;
        }

        for($x=$total; $x>=$min; $x--)
        {
          if($unit_type == 'free_text') {
            $line = $total - $xx + 1;
          }
          else if($unit_type == 'prefix')
          {
            $line = 'line'.($total - $xx + 1);
          }
          else
          {
            $line = $x == 0 ? 'amid' : ( $x < 0 ? 'n'.abs($x) : 'p'.$x);
          }

          $row_data = [
            'form_id' => $form->id,
            'plate_position' => $x,
            'position_txt' => $data['position_txt_'.$line] ?? '',

            'item_no' => $data['item_no_'.$line], 
            'org_thickness' => $data['org_thickness_'.$line], 
            'min_thickness' => $data['min_thickness_'.$line], 
            'gauged_p' => $data['gauged_p_'.$line], 
            'gauged_s' => $data['gauged_s_'.$line], 
            'dim_lvl' => $data['dim_lvl_'.$line], 
            'dim_lvl_unit' => $data['dim_lvl_unit_'.$line],
            'dim_p_mm' => $data['dim_p_mm_'.$line], 
            'dim_p_pct' => $data['dim_p_pct_'.$line], 
            'dim_s_mm' => $data['dim_s_mm_'.$line], 
            'dim_s_pct' => $data['dim_s_pct_'.$line], 
            'new_plate' => $data['new_plate_'.$line]

          ];

          $all_data[] = $row_data;

          $xx++;
        }

        FormDataTwo::where('form_id', $form->id)->delete();
if(FormDataTwo::insert($all_data))
{
  $response['status'] = 'success';
  $response['message'] = 'Form has been updated';
  $response['redirect_url'] = url('/report_detail').'/'.base64_encode($request->report_id).'?tab='.$tab.'&form_id='.$form->id;
}
      }


      if($form && $form->form_type->form_data_format == 'three')
      {
        $form->default_dim_lvl        = $request->default_dim_lvl ?? NULL;
        $form->default_org_thickness  = $request->default_org_thickness ?? NULL;
        $form->default_min_thickness  = $request->default_min_thickness ?? NULL;
        $form->total_spot             = $request->total_spot;
        $form->save();

        // if($unit_type == 'free_text') 
        if($unit_type == 'free_text' || $unit_type == 'prefix') 
        {
          $total = $request->total_line;
          $min = 1;
        }
        else if($unit_type == 'strake')
        {
          $total = $request->total_line + 3;
          $min = 1;   
        }
        else
        {
          $total = $request->total_line;
          $min = $request->total_line*-1;
        }

        for($x=$total; $x>=$min; $x--)
        {

          if($unit_type == 'free_text') {
            $line = $total - $xx + 1;
          }
          else if($unit_type == 'prefix')
          {
            $line = 'line'.($total - $xx + 1);
          }
          else if($unit_type == 'strake')
          {
            if($x == 1)
            {
              $line = 'stringer_plate';
            }
            else if($x == $request->total_line + 2)
            {
              $line = 'centre_strake';
            }
            else if($x == $request->total_line + 3)
            {
              $line = 'sheer_strake';
            }
            else
            {
              $line = ordinal($x-1).'_strake_inboard';
            }
          }
          else
          {
            $line = $x == 0 ? 'amid' : ( $x < 0 ? 'n'.abs($x) : 'p'.$x);
          }


          $line_data = explode('----', $data['input_form_'.$x]);
          // dd($line_data);

          $row_data = [
            'form_id' => $form->id,
            'plate_position' => $x,
            'position_txt' => isset($line_data[0]) ? ($line_data[0] ? : null) : null,

            'item_no_1'       => isset($line_data[1]) ? ($line_data[1] ? : null) : null,
            'org_thickness_1' => isset($line_data[2]) ? ($line_data[2] ? : null) : null,
            'min_thickness_1' => isset($line_data[3]) ? ($line_data[3] ? : null) : null,
            'gauged_p_1'      => isset($line_data[4]) ? ($line_data[4] ? : null) : null,
            'gauged_s_1'      => isset($line_data[5]) ? ($line_data[5] ? : null) : null,
            'dim_lvl_1'       => isset($line_data[6]) ? ($line_data[6] ? : null) : null,
            'dim_lvl_unit_1'  => isset($line_data[7]) ? ($line_data[7] ? : null) : null,
            'dim_p_mm_1'      => isset($line_data[8]) ? ($line_data[8] ? : null) : null,
            'dim_p_pct_1'     => isset($line_data[9]) ? ($line_data[9] ? : null) : null,
            'dim_s_mm_1'      => isset($line_data[10]) ? ($line_data[10] ? : null) : null,
            'dim_s_pct_1'     => isset($line_data[11]) ? ($line_data[11] ? : null) : null,

            'item_no_2'       => isset($line_data[12]) ? ($line_data[12] ? : null) : null,
            'org_thickness_2' => isset($line_data[13]) ? ($line_data[13] ? : null) : null,
            'min_thickness_2' => isset($line_data[14]) ? ($line_data[14] ? : null) : null,
            'gauged_p_2'      => isset($line_data[15]) ? ($line_data[15] ? : null) : null,
            'gauged_s_2'      => isset($line_data[16]) ? ($line_data[16] ? : null) : null,
            'dim_lvl_2'       => isset($line_data[17]) ? ($line_data[17] ? : null) : null,
            'dim_lvl_unit_2'  => isset($line_data[18]) ? ($line_data[18] ? : null) : null,
            'dim_p_mm_2'      => isset($line_data[19]) ? ($line_data[19] ? : null) : null,
            'dim_p_pct_2'     => isset($line_data[20]) ? ($line_data[20] ? : null) : null,
            'dim_s_mm_2'      => isset($line_data[21]) ? ($line_data[21] ? : null) : null,
            'dim_s_pct_2'     => isset($line_data[22]) ? ($line_data[22] ? : null) : null,

            'item_no_3'       => isset($line_data[23]) ? ($line_data[23] ? : null) : null,
            'org_thickness_3' => isset($line_data[24]) ? ($line_data[24] ? : null) : null,
            'min_thickness_3' => isset($line_data[25]) ? ($line_data[25] ? : null) : null,
            'gauged_p_3'      => isset($line_data[26]) ? ($line_data[26] ? : null) : null,
            'gauged_s_3'      => isset($line_data[27]) ? ($line_data[27] ? : null) : null,
            'dim_lvl_3'       => isset($line_data[28]) ? ($line_data[28] ? : null) : null,
            'dim_lvl_unit_3'  => isset($line_data[29]) ? ($line_data[29] ? : null) : null,
            'dim_p_mm_3'      => isset($line_data[30]) ? ($line_data[30] ? : null) : null,
            'dim_p_pct_3'     => isset($line_data[31]) ? ($line_data[31] ? : null) : null,
            'dim_s_mm_3'      => isset($line_data[32]) ? ($line_data[32] ? : null) : null,
            'dim_s_pct_3'     => isset($line_data[33]) ? ($line_data[33] ? : null) : null,
            'new_plate'       => isset($line_data[34]) ? ($line_data[34] ? : 0) : 0,

          ];
          // dd($row_data);
          $all_data[] = $row_data;
          // dd($all_data);

          $xx++;
        }

        // dd($all_data);
        FormDataThree::where('form_id', $form->id)->delete();
if(FormDataThree::insert($all_data))
{
  $response['status'] = 'success';
  $response['message'] = 'Form has been updated';
  $response['redirect_url'] = url('/report_detail').'/'.base64_encode($request->report_id).'?tab='.$tab.'&form_id='.$form->id;
}
      }


    }


    return response()->json($response);
  }

  public function edit($id)
  {

    $id = $id ? base64_decode($id) : '';

    if($id)
    {
      $form = Form::where('id',$id)->with(['report.ship','form_data_one', 'form_data_two', 'form_data_three', 'form_type'])->first();
      if($form)
      {
        $ship_type_id       = $form->report->ship->ship_type_id;
        $ship_type          = ShipType::find($ship_type_id);
        $ship_type_category = ShipTypeCategory::where('ship_type_id',$ship_type_id)->where('form_type_id',$form->form_type_id)->with('category')->first();

        $report_id    = $form->report_id;
        $category_id  = $form->form_type->category_id;
        $tab          = $_GET['tab'] ?? '';
        $report       = Report::where('id', $report_id)->with('ship')->first();
        $category     = Category::find($category_id);
        $category     = $ship_type_category->category;
        $total_line   = $form->total_line;
        $unit         = get_unit_data($total_line, $form->form_type->unit_type, $form->form_type->unit_prefix);


        if($form->form_type->unit_type == 'free_text' || $form->form_type->unit_type == 'prefix') 
        {
          $total_line_three = $form->total_line;
        }
        else if($form->form_type->unit_type == 'strake')
        {
          $total_line_three = $form->total_line + 3;
        }
        else
        {
          $total_line_three = $form->total_line;
        }


        return view('report.edit', compact('ship_type', 'form', 'report', 'category', 'unit', 'total_line','total_line_three'));   

      }
      else
      {
        return 'Form not found';
      }
    }
    else
    {
      return 'Form not found';
    }
  }


}
