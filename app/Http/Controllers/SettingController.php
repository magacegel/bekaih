<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FormData;
use App\Models\FormDataOne;
use App\Models\FormDataTwo;
use App\Models\Form;
use App\Models\FormType;
use App\Models\Report;
use App\Models\Ship;
use App\Models\Category;
use App\Models\ShipType;
use App\Models\ShipTypeCategory;
use App\Models\Company;
use App\Models\CompanyCertificate;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{

  public function company_detail($id)
  {
    $company = Company::with([
      'users.competencies' => function($q) {
        $q->orderBy('created_at', 'desc');
      },
      'users.roles',
      'equipments.activeCertifications',
      'certificates',
      'activeCertificate'
    ])->find($id);
    
    if (!$company) {
      abort(404, 'Perusahaan tidak ditemukan');
    }
    
    // Get available roles for user creation
    // Superadmin can assign any role except superadmin itself
    // Administrator can assign inspektor and supervisor
    $availableRoles = collect();
    
    if (auth()->user()->hasRole('superadmin')) {
      $availableRoles = \Spatie\Permission\Models\Role::where('name', '!=', 'superadmin')->get();
    } elseif (auth()->user()->hasRole('administrator')) {
      $availableRoles = \Spatie\Permission\Models\Role::whereIn('name', ['inspektor', 'supervisor'])->get();
    }
    
    return view('company.show', compact('company', 'availableRoles'));
  }

  public function company_setting_save($id, Request $request)
  {
    try {
        $company = Company::find($id);
        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perusahaan tidak ditemukan.'
            ], 404);
        }
        
        $company->address = $request->address;
        $company->city = $request->city;
        $company->zip_code = $request->zip_code;
        
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $sanitizedName = sanitize_filename($logoFile->getClientOriginalName());
            $logoFileName = time() . '_' . $sanitizedName;
            $doDirectory = env('DO0_DIRECTORY', ''); // Get DO directory from env
            $logoPath = $doDirectory . '/' . $company->id . '/company_logo/' . $logoFileName;

            // Save original logo
            $uploadResult = Storage::disk('digitalocean')->put($logoPath, file_get_contents($logoFile));
            
            if (!$uploadResult) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengunggah logo ke storage.'
                ], 500);
            }
            
            $company->logo = $logoPath;

            // Create and save resized version
            $image = Image::make($logoFile);
            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resizedFileName = pathinfo($logoFileName, PATHINFO_FILENAME) . '_resized.' . pathinfo($logoFileName, PATHINFO_EXTENSION);
            $resizedPath = $doDirectory . '/' . $company->id . '/company_logo/' . $resizedFileName;
            $resizedUploadResult = Storage::disk('digitalocean')->put($resizedPath, (string) $image->encode());
            
            if ($resizedUploadResult) {
                $company->logo_resized = $resizedPath;
            }
        }
        
        $company->save();
        
        // Get the base URL for DigitalOcean
        $doConfig = config('filesystems.disks.digitalocean');
        $baseUrl = $doConfig['url'] ?? $doConfig['bucket_endpoint'] ?? '';
        $logoUrl = $company->logo ? rtrim($baseUrl, '/') . '/' . ltrim($company->logo, '/') : null;
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan perusahaan telah disimpan',
            'data' => [
                'name' => $company->name,
                'address' => $company->address,
                'phone' => $company->phone,
                'email' => $company->email,
                'logo' => $logoUrl,
                'logo_resized' => $company->logo_resized
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menyimpan pengaturan: ' . $e->getMessage()
        ], 500);
    }
  }

  public function company_certificate_save($id, Request $request)
  {
    try {
        $company = Company::find($id);
        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perusahaan tidak ditemukan.'
            ], 404);
        }
        
        $activeCertificate = $company->activeCertificate;

        $newCertificateNumber = $request->certificate_number;
        $newApprovalNumber = $request->approval_number;

        if ($activeCertificate &&
            ($newCertificateNumber !== $activeCertificate->number ||
             $newApprovalNumber !== $activeCertificate->approval_number)) {
          // Buat sertifikat baru
          $certificate = new CompanyCertificate();
        } else {
          // Gunakan sertifikat yang ada atau buat baru jika belum ada
          $certificate = $activeCertificate ?? new CompanyCertificate();
        }

        $certificate->number = $newCertificateNumber;
        $certificate->approval_number = $newApprovalNumber;
        $certificate->approval_date = $request->approval_date;
        $certificate->expired_date = $request->expired_date;

        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            if ($file->getClientMimeType() !== 'application/pdf') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hanya file PDF yang diizinkan untuk sertifikat.'
                ], 400);
            }

            $sanitizedName = sanitize_filename($file->getClientOriginalName());
            $fileName = time() . '_' . $sanitizedName;
            $doDirectory = env('DO0_DIRECTORY', '');
            $filePath = $doDirectory . '/' . $company->id . '/company_certificates/' . $fileName;

            // Save certificate file
            $uploadResult = Storage::disk('digitalocean')->put($filePath, file_get_contents($file));
            
            if (!$uploadResult) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file sertifikat ke storage.'
                ], 500);
            }
            
            $certificate->certificate_file = $filePath;
        }

        $certificate->company_id = $company->id;
        $certificate->save();

        // Set sebagai sertifikat aktif
        $company->company_certificate_id = $certificate->id;
        $company->save();

        // Get the base URL for DigitalOcean
        $doConfig = config('filesystems.disks.digitalocean');
        $baseUrl = $doConfig['url'] ?? $doConfig['bucket_endpoint'] ?? '';
        $certificateUrl = $certificate->certificate_file ? rtrim($baseUrl, '/') . '/' . ltrim($certificate->certificate_file, '/') : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan sertifikat perusahaan telah disimpan',
            'data' => [
                'certificate_number' => $certificate->number,
                'approval_number' => $certificate->approval_number,
                'approval_date' => $certificate->approval_date,
                'expired_date' => $certificate->expired_date,
                'certificate_file' => $certificateUrl
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menyimpan sertifikat: ' . $e->getMessage()
        ], 500);
    }
  }



  public function ship_type(Request $request)
  {

    $tmp = '';
    return view('settings.ship_type', compact('tmp'));

  }


  public function category(Request $request)
  {
    $tmp = '';
    return view('settings.category', compact('tmp'));

  }

  public function form_type(Request $request)
  {
    $plates = get_plate_data(5);
    return view('settings.form_type', compact('plates'));
  }

  public function report()
  {

    $all_data = [];
    $ship_types = ShipType::all();
    $categories = Category::all();
    $form_types = FormType::all();

    $tmp = '';
    return view('settings.report', compact('ship_types','categories','form_types'));
  }


  public function report_data(Request $request)
  {

    $response['status']   = 'success';
    $response['message']  = '';

    $id     = $request->ship_type_category_id;
    $action = $request->action;

    if($action == 'list')
    {

      $ship_type_id = ShipTypeCategory::all()->pluck('ship_type_id')->toArray();
      $ship_types   = ShipType::whereIn('id',$ship_type_id)->get();
      $all_data     = [];

      foreach($ship_types as $ship_type)
      {
        $data = [];

        $data['title'] = $ship_type->type;
        $data['type'] = 'ship_type';
        $data['id'] = '';
        $all_data[] = $data;

        $category_id  = ShipTypeCategory::where('ship_type_id', $ship_type->id)->pluck('category_id')->toArray();
        $categories   = Category::whereIn('id',$category_id)->get();

        foreach($categories as $category){

          $data['title']  = $category->name;
          $data['type']   = 'category';
          $data['id']     = '';
          $all_data[]     = $data;

          $form_type_lists = ShipTypeCategory::where('ship_type_id',$ship_type->id)->where('category_id',$category->id)->with('form_type')->get();

          foreach($form_type_lists as $form_type)
          {
            $data['title'] = $form_type->form_type->name;
            $data['type'] = 'form_type';
            $data['id'] = $form_type->id;
            $all_data[] = $data;
          }
        }
      }

      $response['data']   = $all_data;
    }


    if($action == 'delete')
    {

      $check  = ShipTypeCategory::where('id',$id)->first();
      if($check)
      {

        $form_type_id = $check->form_type_id;
        $ship_type_id = $check->ship_type_id;
        $category_id  = $check->category_id;

        // Mengecek apakah ada data yang sudah dicreate menggunakan category & form ini
        $created_data = false;

        $ship_id = Ship::where('ship_type_id',$ship_type_id)->get()->pluck('id')->toArray();
        if($ship_id)
        {
          $report_id = Report::whereIn('ship_id',$ship_id)->get()->pluck('id')->toArray();

          if($report_id)
          {
            $check_form = Form::where('form_type_id',$form_type_id)->whereIn('report_id',$report_id)->count();
            if($check_form)
            {
              $created_data = true;
            }
          }
        }

        if($created_data)
        {
          $response['status']   = 'error';
          $response['message']  = 'Failed to delete! There are forms that already use this Form Type ';
        }
        else
        {
          $check  = ShipTypeCategory::where('id',$id)->delete();
          $response['message']  = 'Item has been deleted';
        }


      }
      else
      {
        $response['status']   = 'error';
        $response['message']  = 'Item not found';
      }
    }

    if($action == 'create')
    {

      $this->validate($request, [
        'ship_type' => 'required',
        'category' => 'required',
        'form_type' => 'required',
      ]);


      $check = ShipTypeCategory::where('ship_type_id',$request->ship_type)->where('form_type_id',$request->form_type)->with('ship_type')->first();
      if($check)
      {
        $response['status']   = 'error';
        $response['message']  = 'Form Type already exist in "'.$check->ship_type->type.'" Ship Type';
      }
      else
      {
        $data['ship_type_id']         = $request->ship_type;
        $data['category_id'] = $request->category;
        $data['form_type_id']  = $request->form_type;
        $data['order']  = 999;


        ShipTypeCategory::insert($data);

        $response['message']  = 'Item has been added';
      }


    }

    return response()->json($response);
  }



  public function category_data(Request $request)
  {

    $response['status']   = 'success';
    $response['message']  = '';

    $id     = $request->category_id;
    $action = $request->action;

    if($action == 'delete')
    {
      $check  = ShipTypeCategory::where('category_id',$id)->count();

      if(!$check)
      {
        Category::where('id',$id)->delete();
        $response['message']  = 'Category has been deleted';
      }
      else
      {
        $s = $check > 1 ? 's' : '';
        $response['status']   = 'error';
        $response['message']  = 'Failed to delete! There are '.$check.' category setting'.$s.' that still using this Category';
      }
    }

    if($action == 'create')
    {

      $this->validate($request, [
        'name' => 'required',
        'abbreviation' => 'required',
        'order' => 'required|numeric',
      ]);

      $data['name']         = $request->name;
      $data['abbreviation'] = $request->abbreviation;
      $data['title']        = $request->title;
      $data['description']  = $request->description;
      $data['order']        = $request->order;

      Category::insert($data);

      $response['message']  = 'Category has been added';

    }

    if($action == 'detail')
    {
      $category = Category::find($id);

      if($category)
      {
        $response['data'] = $category;
      }
    }


    if($action == 'edit')
    {

      $this->validate($request, [
        'name' => 'required',
        'abbreviation' => 'required',
        'order' => 'required|numeric',
      ]);

      $form = Category::find($id);

      if($form)
      {
        $form->name         = $request->name;
        $form->abbreviation = $request->abbreviation;
        $form->title        = $request->title;
        $form->description  = $request->description;
        $form->order        = $request->order;

        $form->save();
        $response['message']  = 'Category has been updated';
      }
      else
      {
        $response['status']   = 'error';
        $response['message']  = 'Category not found!';
      }

    }


    return response()->json($response);
  }


  public function ship_type_data(Request $request)
  {

    $response['status']   = 'success';
    $response['message']  = '';

    $id     = $request->ship_type_id;
    $action = $request->action;

    if($action == 'delete')
    {
      $check  = Ship::where('ship_type_id',$id)->count();

      if(!$check)
      {
        ShipType::where('id',$id)->delete();
        $response['message']  = 'Ship Type has been deleted';
      }
      else
      {
        $s = $check > 1 ? 's' : '';
        $response['status']   = 'error';
        $response['message']  = 'Failed to delete! There are '.$check.' ship report'.$s.' that still using this Ship Type';
      }
    }

    if($action == 'create')
    {

      $this->validate($request, [
        'ship_type' => 'required',
      ]);

      $data['type'] = $request->ship_type;

      ShipType::insert($data);

      $response['message']  = 'Ship Type has been added';

    }

    if($action == 'detail')
    {
      $ship_type = ShipType::find($id);

      if($ship_type)
      {
        $response['data'] = $ship_type;
      }
    }


    if($action == 'edit')
    {

      $this->validate($request, [
        'ship_type' => 'required',
      ]);

      $form = ShipType::find($id);

      if($form)
      {
        $form->type             = $request->ship_type;

        $form->save();
        $response['message']  = 'Ship Type has been updated';
      }
      else
      {
        $response['status']   = 'error';
        $response['message']  = 'Ship Type not found!';
      }

    }


    return response()->json($response);
  }

  public function form_type_data(Request $request)
  {
    $response['status']   = 'success';
    $response['message']  = '';

    $id     = $request->form_type_id;
    $action = $request->action;

    if($action == 'delete')
    {
      $check  = Form::where('form_type_id',$id)->count();

      if(!$check)
      {
        FormType::where('id',$id)->delete();
        $response['message']  = 'Form Type has been deleted';
      }
      else
      {
        $s = $check > 1 ? 's' : '';
        $response['status']   = 'error';
        $response['message']  = 'Failed to delete! There are '.$check.' form'.$s.' that still using this Form Type';
      }
    }

    if($action == 'detail')
    {
      $form_type = FormType::find($id);

      if($form_type)
      {
        $response['data'] = $form_type;
      }
    }

    if($action == 'create')
    {

      $form_data_format = $request->form_data_format;

      if($form_data_format == 'one')
      {

        $this->validate($request, [
          'form_title' => 'required',
          'unit_type' => 'required',
          'unit_prefix' => 'required_if:unit_type,prefix',
        ]);


        $data['name']             = $request->form_title;
        $data['form_data_format'] = $request->form_data_format;
        $data['unit_type']        = $request->unit_type;
        $data['unit_prefix']      = $request->unit_prefix;


        FormType::insert($data);

        $response['message']  = 'Form Type has been added';
      }

      if($form_data_format == 'two')
      {

        $this->validate($request, [
          'form_title' => 'required',
          'unit_title' => 'required',
          'unit_type' => 'required',
          'unit_prefix' => 'required_if:unit_type,prefix',
        ]);


        $data['name']             = $request->form_title;
        $data['form_data_format'] = $request->form_data_format;
        $data['unit_title']       = $request->unit_title ?:'';
        $data['number_wording']   = $request->number_wording ?:'No or Letter';
        $data['gauged_p_title']   = $request->gauged_p_title ?: 'Port';
        $data['gauged_s_title']   = $request->gauged_s_title ?: 'Starboard';
        $data['dim_p_title']      = $request->dim_p_title ?: 'Diminution P';
        $data['dim_s_title']      = $request->dim_s_title ?: 'Diminution S';
        $data['unit_type']        = $request->unit_type;
        $data['unit_prefix']      = $request->unit_prefix;


        FormType::insert($data);

        $response['message']  = 'Form Type has been added';
      }

      if($form_data_format == 'three')
      {

        $this->validate($request, [
          'form_title' => 'required',
          // 'number_wording' => 'required',
          'unit_type' => 'required',
          'unit_prefix' => 'required_if:unit_type,prefix',
        ]);


        $data['name']             = $request->form_title;
        $data['form_data_format'] = $request->form_data_format;
        // $data['unit_title']       = $request->unit_title;
        $data['number_wording']   = $request->number_wording ?:'No or Letter';
        $data['unit_type']        = $request->unit_type;
        $data['unit_prefix']      = $request->unit_prefix;


        FormType::insert($data);

        $response['message']  = 'Form Type has been added';
      }


    }


    if($action == 'edit')
    {

      $form_data_format = $request->form_data_format;

      if($form_data_format == 'one')
      {
        $this->validate($request, [
          'form_title' => 'required',
          'unit_type' => 'required',
          'unit_prefix' => 'required_if:unit_type,prefix',
        ]);

        $form = FormType::find($id);
        if($form)
        {
          $form->name             = $request->form_title;
          $form->unit_type        = $request->unit_type;
          $form->unit_prefix      = $request->unit_type == 'prefix'? $request->unit_prefix : '';

          $form->save();
          $response['message']  = 'Form Type has been updated';
        }
        else
        {
          $response['status']   = 'error';
          $response['message']  = 'Form Type not found!';
        }
      }



      if($form_data_format == 'two')
      {
        $this->validate($request, [
          'form_title' => 'required',
          'number_wording' => 'required',
          'unit_type' => 'required',
          'unit_prefix' => 'required_if:unit_type,prefix',
        ]);

        $form = FormType::find($id);
        if($form)
        {
          $form->name             = $request->form_title;

          $form->unit_title       = $request->unit_title;
          $form->number_wording       = $request->number_wording;
          $form->gauged_p_title       = $request->gauged_p_title;
          $form->gauged_s_title       = $request->gauged_s_title;
          $form->dim_p_title       = $request->dim_p_title;
          $form->dim_s_title       = $request->dim_s_title;

          $form->unit_type        = $request->unit_type;
          $form->unit_prefix      = $request->unit_type == 'prefix'? $request->unit_prefix : '';

          $form->save();
          $response['message']  = 'Form Type has been updated';
        }
        else
        {
          $response['status']   = 'error';
          $response['message']  = 'Form Type not found!';
        }
      }



      if($form_data_format == 'three')
      {
        $this->validate($request, [
          'form_title' => 'required',
          'number_wording' => 'required',
          'unit_type' => 'required',
          'unit_prefix' => 'required_if:unit_type,prefix',
        ]);

        $form = FormType::find($id);
        if($form)
        {
          $form->name             = $request->form_title;
          $form->unit_title       = $request->unit_title;
          $form->unit_type        = $request->unit_type;
          $form->unit_prefix      = $request->unit_type == 'prefix'? $request->unit_prefix : '';

          $form->save();
          $response['message']  = 'Form Type has been updated';
        }
        else
        {
          $response['status']   = 'error';
          $response['message']  = 'Form Type not found!';
        }
      }

    }


    return response()->json($response);

  }


  public function category_datatables(Request $request)
  {

    $tmp = Category::all();

    return Datatables::of($tmp)

    ->addColumn('action', function ($tmp) {
      return '
      <!--<button type="button" class="btn btn-info btn-sm b_detail" id="'.base64_encode($tmp->id).'" title="Detail"><i class="fa fa-info-circle" aria-hidden="true"></i> Detail</button>!-->
      <!--<button type="button" class="btn btn-warning btn-sm b_preview" id="'.$tmp->id.'" title="Preview"><i class="fa fa-search" aria-hidden="true"></i> Preview</button>-->
      <button type="button" class="btn btn-primary btn-sm b_edit" id="'.$tmp->id.'" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i> Edit</button>
      <button type="button" class="btn btn-danger btn-sm b_delete" id="'.$tmp->id.'" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
      ';})
    ->addIndexColumn()
    ->make(true);
  }

  public function ship_type_datatables(Request $request)
  {

    $tmp = ShipType::all();

    return Datatables::of($tmp)

    ->addColumn('action', function ($tmp) {
      return '
      <!--<button type="button" class="btn btn-info btn-sm b_detail" id="'.base64_encode($tmp->id).'" title="Detail"><i class="fa fa-info-circle" aria-hidden="true"></i> Detail</button>!-->
      <!--<button type="button" class="btn btn-warning btn-sm b_preview" id="'.$tmp->id.'" title="Preview"><i class="fa fa-search" aria-hidden="true"></i> Preview</button>-->
      <button type="button" class="btn btn-primary btn-sm b_edit" id="'.$tmp->id.'" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i> Edit</button>
      <button type="button" class="btn btn-danger btn-sm b_delete" id="'.$tmp->id.'" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
      ';})
    ->addIndexColumn()
    ->make(true);
  }


  public function form_type_datatables(Request $request)
  {

    $tmp = FormType::all();

    return Datatables::of($tmp)

    ->addColumn('action', function ($tmp) {
      return '
      <!--<button type="button" class="btn btn-info btn-sm b_detail" id="'.base64_encode($tmp->id).'" title="Detail"><i class="fa fa-info-circle" aria-hidden="true"></i> Detail</button>!-->
      <button type="button" class="btn btn-warning btn-sm b_preview" id="'.$tmp->id.'" title="Preview"><i class="fa fa-search" aria-hidden="true"></i> Preview</button>
      <button type="button" class="btn btn-primary btn-sm b_edit" id="'.$tmp->id.'" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i> Edit</button>
      <button type="button" class="btn btn-danger btn-sm b_delete" id="'.$tmp->id.'" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
      ';})
    ->addIndexColumn()
    ->make(true);
  }

}
