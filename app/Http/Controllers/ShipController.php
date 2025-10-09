<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ship;
use App\Models\FormData;
use App\Models\Form;
use App\Models\FormType;
use App\Models\Report;
use App\Models\ReportData;
use App\Models\Category;
use App\Models\ShipList;
use Hash;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

use Yajra\DataTables\Facades\DataTables;

class ShipController extends Controller
{

  public function ship_list(Request $request)
  {
    $search = $request->search;
    $no_reg = $request->no_reg;
    if($search)
    {
      $result = ShipList::selectRaw('NOREG as id, CONCAT(NOREG, " - ", NMKPL) as text')->where('NOREG','LIKE','%'.strtoupper($search).'%')->orWhere('NMKPL','LIKE','%'.strtoupper($search).'%')->orderBy('NMKPL','ASC')->get();
    }
    else if($no_reg)
    {
      $result = ShipList::where('NOREG',$no_reg)->first();
    }

    return response()->json($result);
  }



  public function sync_ship(Request $request)
  {

    ShipList::where('id','>',0)->delete();

    $client_url = 'https://new.bki.co.id/survapi/allregisternumber/BAYUMWITBKI';


    $client = new Client();
    $guzzleResponse = $client->get( $client_url, [
      'headers' => [
                    // 'APP_KEY'=>'QAWLhIK2p5'
      ],
    ]);
    if ($guzzleResponse->getStatusCode() == 200) {
      $response = json_decode($guzzleResponse->getBody(),true);
    }

    if(isset($response['data']))
    {
      $ship_data = [];
      $x = 1;
      foreach($response['data'] as $data)
      {
        $tmp = [];
        $tmp['NOREG'] = $data['NOREG'] ?? '';
        $tmp['NOIMO'] = $data['NOIMO'] ?? '';
        $tmp['NMKPL'] = $data['NMKPL'] ?? '';
        $tmp['TYSHP'] = $data['TYSHP'] ?? '';
        $tmp['BRT'] = $data['BRT'] ?? '';
        $tmp['LOA'] = $data['LOA'] ?? '';
        $tmp['BMLD'] = $data['BMLD'] ?? '';
        $tmp['HMLD'] = $data['HMLD'] ?? '';
        $tmp['KOTAFL'] = $data['KOTAFL'] ?? '';
        $tmp['PEMILIK'] = $data['PEMILIK'] ?? '';
        $tmp['KOTA'] = $data['KOTA'] ?? '';

        $ship_data[] = $tmp;

        if($x%100 == 0 || $x == count($response['data']))
        {
          ShipList::insert($ship_data);
          $ship_data = [];
        }

        $x++;
      }
    }


    return response()->json(['status'=>'success','message'=>'Ship data has been succesfully synced']);


  }


  public function index()
  {

    $text = '';
    return view('ship.index', compact('text')); 

  }


  public function ship_datatables()
  {

    $tmp = Ship::with('report');

    return Datatables::of($tmp)
    ->addColumn('action', function ($tmp) {
      return '
      <button type="button" class="btn btn-danger btn-sm b_delete" report_count="'.$tmp->report->count().'" id="'.$tmp->id.'" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
      ';})
    ->addIndexColumn()
    ->make(true);

  }



  public function ship_data(Request $request)
  {

    $response['status'] = 'error';
    $response['message'] = '';

    if($request->action == 'create')
    {
      $ship_data = [];

      $ship_data['name']          = $request->ship_name;
      $ship_data['class_identity'] = $request->class_identity;

      $ship = Ship::create($ship_data);

      if($ship)
      {
        $response['status'] = 'success';
        $response['message'] = 'Ship has been created';
      }

    }

    if($request->action == 'delete')
    {

      $ship_id = $request->ship_id;

      if($ship_id)
      {

        $report_id = Report::where('ship_id',$ship_id)->get()->pluck('id');

        if($report_id)
        {
          $form_id = Form::whereIn('report_id',$report_id)->get()->pluck('id');
          if($form_id)
          {
            FormData::whereIn('form_id',$form_id)->delete();
          }
          Form::whereIn('report_id',$report_id)->delete();
        }

        Report::where('ship_id',$ship_id)->delete();
        Ship::where('id',$ship_id)->delete();
      }

      $response['status'] = 'success';
      $response['message'] = 'Ship has been deleted';

    }

    return response()->json($response);

  }



}
