<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\UserList;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Competency;

class UserController extends Controller
{
  public function index()
  {
    if ($user = Auth::user()) {
      return view('user.dashboard');
    }
    return view('login');
  }



  public function profile(Request $request)
  {

    if (Auth::user()) {

      $action = $request->action;
      if($action == 'update_profile')
      {

        $this->validate($request, [
          'name' => 'required',
          'email' => 'required|email',
          'ktp' => 'required|numeric|digits:16',
          'phone' => 'nullable',
          'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2000',
          'signature_data' => 'nullable|string',
          'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2000',
        ]);

        $image_fields = [
          'profile_image' => 'uploads/profile_image/',
        ];

        foreach ($image_fields as $field => $upload_path) {
          if ($request->hasFile($field)) {
            $file = $request->file($field);
            $extension = $file->getClientOriginalExtension();
            $name = $field . '_' . Auth()->user()->id . '_' . date('YmdHis');
            $image_name = $name . '.' . $extension;
            $image_name_resized = $name . '_resized.' . $extension;

            $full_filename = $upload_path . $image_name;
            $full_filename_resized = $upload_path . $image_name_resized;

            $img = Image::make($file);
            $img->save('storage/' . $full_filename);

            $resize_limit = 1200;
            if ($img->width() > $resize_limit || $img->height() > $resize_limit) {
              $img->resize($resize_limit, $resize_limit, function ($constraint) {
                $constraint->aspectRatio();
              });
            }
            $img->save('storage/' . $full_filename_resized);

            $user = User::where('id', Auth()->user()->id)->first();
            if ($user->$field) {
              // Hapus gambar lama
              $old_file = $upload_path . $user->$field;
              if (file_exists(public_path('storage/' . $old_file))) {
                unlink(public_path('storage/' . $old_file));
              }
              // Hapus gambar lama yang di-resize
              $old_resized = pathinfo($user->$field, PATHINFO_FILENAME) . '_resized.' . pathinfo($user->$field, PATHINFO_EXTENSION);
              $old_resized_file = $upload_path . $old_resized;
              if (file_exists(public_path('storage/' . $old_resized_file))) {
                unlink(public_path('storage/' . $old_resized_file));
              }
            }

            $user->$field = $image_name;
            $user->save();

            $api_data[$field] = $image_name;
          }
        }

        // Handle signature upload or canvas data
        $user = User::where('id', Auth()->user()->id)->first();
        if ($request->hasFile('signature') || $request->signature_data) {
          $signature_directory = env('DO0_DIRECTORY') . '/' . $user->company_id . '/signatures/' . $user->id . '/';
          $name = 'signature_' . Auth()->user()->id . '_' . date('YmdHis');

          if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $extension = $file->getClientOriginalExtension();
            $image_name = $name . '.' . $extension;
            $image_name_resized = $name . '_resized.' . $extension;

            $full_filename = $signature_directory . $image_name;
            $full_filename_resized = $signature_directory . $image_name_resized;

            $img = Image::make($file);
            if ($img->height() > $img->width()) {
              $img->rotate(90);
            }

            // dd($img);
            Storage::disk('digitalocean')->put($full_filename, $img->encode());

            $resize_limit = 1200;
            if ($img->width() > $resize_limit || $img->height() > $resize_limit) {
              $img->resize($resize_limit, $resize_limit, function ($constraint) {
                $constraint->aspectRatio();
              });
            }
            Storage::disk('digitalocean')->put($full_filename_resized, $img->encode());
          } elseif ($request->signature_data) {
            $signature_data = $request->signature_data;
            $signature_data = str_replace('data:image/png;base64,', '', $signature_data);
            $signature_data = str_replace(' ', '+', $signature_data);
            $signature_image = base64_decode($signature_data);

            $image_name = $name . '.png';
            $full_filename = $signature_directory . $image_name;

            Storage::disk('digitalocean')->put($full_filename, $signature_image);
          }

          // if ($user->signature) {
          //   // Hapus gambar lama
          //   $old_signature_path = pathinfo($user->signature, PATHINFO_DIRNAME);
          //   try {
          //     if (Storage::disk('digitalocean')->exists($old_signature_path)) {
          //       Storage::disk('digitalocean')->deleteDirectory($old_signature_path);
          //     }
          //   } catch (\Exception $e) {
          //     // Log the error or handle it as needed
          //     \Log::error('Error deleting old signature: ' . $e->getMessage());
          //   }
          // }

          $user->signature = $full_filename;
          $user->save();

          $api_data['signature'] = $full_filename;
        }

        try {
          $user->update($request->only(['name', 'email', 'ktp', 'phone']));
          $api_data['status'] = 'success';
          $api_data['message'] = 'Profil berhasil diperbarui';
        } catch (\Exception $e) {
          $api_data['status'] = 'error';
          $api_data['message'] = 'Gagal memperbarui profil: ' . $e->getMessage();
        }

        return response($api_data);

      }
      else if($action == 'change_password')
      {

        $id = Auth::User()->id;

        $user = User::where('id',$id)->first();

        $this->validate($request, [
          'old_password' => 'required',
          'new_password' => 'required|min:4',
          'new_password_confirm' => 'required||min:4|same:new_password',
        ]);

        // Kalau password masih kosongan
        if(!$user->password)
        {
          $api_data['status'] = 'error';
          $api_data['message'] = 'Harap menghubungi administrator untuk mengaktifkan akun anda';
        }
        else
        {
          // Compare passsword yang diinputkan dengan yang ada di database
          if(!Hash::check($request->old_password, $user->password))
          {
            $api_data['status'] = 'error';
            $api_data['message'] = 'Password lama yang Anda masukkan salah';
          }
          else
          {
            try {
              $user->update(['password' => Hash::make($request->new_password), 'change_password' => 'no']);
              $api_data['status'] = 'success';
              $api_data['message'] = 'Password berhasil diubah';
            } catch (\Exception $e) {
              $api_data['status'] = 'error';
              $api_data['message'] = 'Gagal mengubah password: ' . $e->getMessage();
            }
          }
        }
        return response($api_data);
      }
      else
      {
        return view('user.profile');
      }
    }


  }



  public function user_management()
  {
    // Check authorization - only Administrator and Superadmin can access
    if (!auth()->user()->hasAnyRole(['superadmin', 'administrator'])) {
      abort(403, 'Akses ditolak. Hanya Administrator dan Super Admin yang dapat mengakses User Management.');
    }

    // Get roles based on user level
    if (auth()->user()->hasRole('superadmin')) {
      // Superadmin can create all roles except superadmin
      $roles = \Spatie\Permission\Models\Role::where('name', '!=', 'superadmin')->get();
    } else if (auth()->user()->hasRole('administrator')) {
      // Administrator can only create inspector and supervisor
      $roles = \Spatie\Permission\Models\Role::whereIn('name', ['inspector', 'supervisor'])->get();
    }

    // Get all companies for assignment
    $companies = \App\Models\Company::all();
    
    return view('user.index', compact('roles', 'companies'));
  }


  public function user_list(Request $request)
  {
    $search = $request->search;
    $nup = $request->nup;

    $token = 'WP4PFLLTUWS2430R7D6T02Q86JX3IV4VO6168SCBM8JF1J6Y3E';
    $uid = 'RS1PRkZJQ0U';

    $url = "https://hrms.bki.co.id/web/api/v2/client/employee/list-surveyor";
    $params = [
      // Jika $search hanya berisi angka, gunakan sebagai nup, jika tidak gunakan sebagai fullname
      (preg_match('/^[0-9]+$/', $search) ? 'nup' : 'fullname') => $search,
      'token' => $token,
      'uid' => $uid
    ];

    $url = $url . '?' . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if($search) {
      $result = collect($data['data'])->map(function($item) {
        return [
          'id' => $item['nup'],
          'text' => $item['nup'] . ' - ' . $item['fullname']
        ];
      });
    }
    else if($nup) {
      $result = collect($data['data'])->where('nup', $nup)->first();
    }

    return response()->json($result);
  }


  public function user_data(Request $request)
  {
    // Check authorization - only Administrator and Superadmin can access
    if (!auth()->user()->hasAnyRole(['superadmin', 'administrator'])) {
      return response()->json([
        'status' => 'error',
        'message' => 'Anda tidak memiliki kewenangan yang cukup'
      ]);
    }

    $action = $request->action;
    
    if ($action == 'create') {
      return $this->store($request);
    } elseif ($action == 'update') {
      return $this->update($request);
    } elseif ($action == 'delete') {
      return $this->destroy($request);
    }

    return response()->json(['status' => 'error', 'message' => 'Invalid action']);
  }

  public function store(Request $request)
  {
    try {
      // Validasi role berdasarkan level user
      if (auth()->user()->hasRole('administrator')) {
        if (!in_array($request->role, ['inspektor', 'supervisor', 'operator'])) {
          return response()->json([
            'status' => 'error',
            'message' => 'Administrator hanya dapat membuat user dengan role Inspektor, Supervisor, atau Operator'
          ]);
        }
      } elseif (auth()->user()->hasRole('superadmin')) {
        if ($request->role == 'superadmin') {
          return response()->json([
            'status' => 'error',
            'message' => 'Tidak dapat membuat user dengan role Super Admin untuk keamanan sistem'
          ]);
        }
      }

      // Validasi role exists
      if (!\Spatie\Permission\Models\Role::where('name', $request->role)->exists()) {
        return response()->json([
          'status' => 'error',
          'message' => 'Role tidak valid'
        ]);
      }

      // Custom validation untuk phone
      $validation_rules = [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'ktp' => 'required|string|max:16|unique:users,ktp',
        'role' => 'required|string',
        'company_id' => 'required|exists:companies,id',
        'password' => 'required|min:6|confirmed'
      ];

      // Add phone validation only if phone is provided and not empty
      $phone = trim($request->phone ?? '');
      if (!empty($phone)) {
        // Check if phone already exists in database
        $existingPhone = \App\Models\User::where('phone', $phone)->first();
        if ($existingPhone) {
          return response()->json([
            'status' => 'error',
            'message' => 'Nomor telepon sudah digunakan'
          ]);
        }
        $validation_rules['phone'] = 'string|max:15';
      }

      $this->validate($request, $validation_rules);

      // Validate role exists
      $role = \Spatie\Permission\Models\Role::where('name', $request->role)->first();
      if (!$role) {
        return response()->json([
          'status' => 'error',
          'message' => 'Role tidak valid.'
        ]);
      }

      // Create the user - menggunakan format yang sama dengan Company module
      $user = new User();
      $user->name = $request->name;
      $user->username = $request->username;
      $user->email = $request->email;
      $user->phone = !empty($phone) ? $phone : null; // Use validated phone or null
      $user->ktp = $request->ktp;
      $user->company_id = $request->company_id;
      $user->password = bcrypt($request->password);
      $user->level = $request->role; // Set level sama dengan role
      $user->save();

      if ($user) {
        // Assign role using Spatie Permission
        $user->assignRole($request->role);
        
        return response()->json([
          'status' => 'success',
          'message' => 'User berhasil ditambahkan'
        ]);
      }

    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }

    return response()->json([
      'status' => 'error',
      'message' => 'Gagal membuat user'
    ]);
  }

  public function update(Request $request)
  {
    try {
      $user = User::findOrFail($request->user_id);
      
      // Validasi role berdasarkan level user
      if (auth()->user()->hasRole('administrator')) {
        if (!in_array($request->role, ['inspektor', 'supervisor', 'operator'])) {
          return response()->json([
            'status' => 'error',
            'message' => 'Administrator hanya dapat mengubah user dengan role Inspektor, Supervisor, atau Operator'
          ]);
        }
      }

      // Validation rules - sama dengan Company module
      $rules = [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'email' => 'required|email|unique:users,email,' . $user->id,
        'ktp' => 'required|string|max:16|unique:users,ktp,' . $user->id,
        'role' => 'required|string',
        'company_id' => 'required|exists:companies,id',
      ];

      // Add phone validation only if phone is provided and not empty
      $phone = trim($request->phone ?? '');
      if (!empty($phone)) {
        // Check if phone already exists in database (exclude current user)
        $existingPhone = \App\Models\User::where('phone', $phone)->where('id', '!=', $user->id)->first();
        if ($existingPhone) {
          return response()->json([
            'status' => 'error',
            'message' => 'Nomor telepon sudah digunakan'
          ]);
        }
        $rules['phone'] = 'string|max:15';
      }

      // Password is optional for update
      if ($request->filled('password')) {
        $rules['password'] = 'min:6|confirmed';
      }

      $this->validate($request, $rules);

      // Update user data
      $user->name = $request->name;
      $user->username = $request->username;
      $user->email = $request->email;
      $user->phone = !empty($phone) ? $phone : null; // Use validated phone or null
      $user->ktp = $request->ktp;
      $user->company_id = $request->company_id;
      $user->level = $request->role;

      // Update password if provided
      if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
      }

      $user->save();

      // Update role
      $user->syncRoles([$request->role]);

      return response()->json([
        'status' => 'success',
        'message' => 'User berhasil diupdate'
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function destroy(Request $request)
  {
    $api_data = ['status' => 'error', 'message' => ''];

    try {
      $user = User::findOrFail($request->user_id);
      
      // Check if user has any reports
      $reportCount = \App\Models\Report::where('user_id', $user->id)->count();
      
      if ($reportCount > 0) {
        return response()->json([
          'status' => 'error',
          'message' => 'User memiliki ' . $reportCount . ' report. Harap hapus semua report yang dibuat terlebih dahulu!'
        ]);
      }

      $user->delete();
      
      $api_data = [
        'status' => 'success',
        'message' => 'User telah berhasil dihapus!'
      ];

    } catch (\Exception $e) {
      $api_data = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ];
    }

    return response()->json($api_data);
  }

  public function show($id)
  {
    try {
      $user = User::with(['roles', 'company'])->findOrFail($id);
      
      return response()->json([
        'status' => 'success',
        'data' => [
          'id' => $user->id,
          'name' => $user->name,
          'username' => $user->username,
          'email' => $user->email,
          'phone' => $user->phone,
          'ktp' => $user->ktp,
          'company_id' => $user->company_id,
          'company_name' => $user->company ? $user->company->name : '-',
          'role' => $user->getRoleNames()->first() ?? '-',
        ]
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'User tidak ditemukan'
      ]);
    }
  }












  public function user_datatables()
  {
    // Filter users based on user role
    if (auth()->user()->hasRole('administrator')) {
      // Administrator can only see users they created or users with inspector/supervisor roles
      $tmp = User::with(['roles', 'company'])
        ->whereHas('roles', function($query) {
          $query->whereIn('name', ['inspector', 'supervisor']);
        })
        ->get();
    } else {
      // Superadmin can see all users except other superadmin
      $tmp = User::with(['roles', 'company'])
        ->whereDoesntHave('roles', function($query) {
          $query->where('name', 'superadmin');
        })
        ->get();
    }

    return Datatables::of($tmp)
      ->addColumn('role', function ($tmp) {
        return $tmp->getRoleNames()->first() ?? '-';
      })
      ->addColumn('usertype', function ($tmp) {
        return $tmp->user_type == 'bki' ? 'BKI' : 'Non BKI';
      })
      ->addColumn('company', function ($tmp) {
        return $tmp->company ? $tmp->company->name : '-';
      })
      ->addColumn('action', function ($tmp) {
        $html = '';
        $html .= '<button type="button" class="btn btn-info btn-sm me-1 b_edit" data-id="'.$tmp->id.'" title="Edit"><i class="fa fa-edit"></i></button>';
        $html .= '<button type="button" class="btn btn-danger btn-sm b_delete" data-id="'.$tmp->id.'" title="Delete"><i class="fa fa-trash"></i></button>';
        return $html;
      })
      ->addIndexColumn()
      ->make(true);
  }


  public function sync_user(Request $request)
  {

    UserList::where('id','>',0)->delete();

    $client_url = 'https://new.bki.co.id/survapi/allbkiuser/BAYUMWITBKI';


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
        $tmp['nama'] = $data['nama'] ?? '';
        $tmp['nup'] = $data['nup'] ?? '';
        $tmp['cabang'] = $data['namadepartemen'] ?? '';

        $ship_data[] = $tmp;

        if($x%100 == 0 || $x == count($response['data']))
        {
          UserList::insert($ship_data);
          $ship_data = [];
        }

        $x++;
      }
    }


    return response()->json(['status'=>'success','message'=>'User data has been succesfully synced']);


  }

  public function uploadCertificate(Request $request)
  {
    // Validasi bahwa user adalah supervisor
    if (!auth()->user()->hasRole('supervisor')) {
        return response()->json([
            'status' => 'error',
            'message' => 'Anda tidak memiliki izin untuk melakukan tindakan ini'
        ], 403);
    }

    // Validasi request
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'qualification' => 'required|string|max:255',
        'certificate_number' => 'required|string|max:255',
        'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
        'expired_date' => 'required|date|after:today',
    ]);

    try {
        // Upload file ke DigitalOcean
        $file = $request->file('certificate_file');
        $user = User::findOrFail($request->user_id);
        $company_id = $user->company_id ?? 'default';

        $certificate_directory = env('DO0_DIRECTORY') . '/' . $company_id . '/certificates/' . $user->id . '/';
        $filename = 'certificate_' . $user->id . '_' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
        $full_path = $certificate_directory . $filename;

        // Upload file ke DigitalOcean
        Storage::disk('digitalocean')->put($full_path, file_get_contents($file));

        // Simpan data sertifikat ke database
        Competency::create([
            'user_id' => $request->user_id,
            'qualification' => $request->qualification,
            'certificate_number' => $request->certificate_number,
            'certificate_file' => $full_path,
            'expired_date' => $request->expired_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Sertifikat berhasil diupload'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengupload sertifikat: ' . $e->getMessage()
        ], 500);
    }
  }

  public function getUserCertificates($user_id)
  {
    // Validasi bahwa user adalah supervisor
    if (!auth()->user()->hasRole('supervisor')) {
        return response()->json([
            'status' => 'error',
            'message' => 'Anda tidak memiliki izin untuk melakukan tindakan ini'
        ], 403);
    }

    $certificates = Competency::where('user_id', $user_id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $certificates
    ]);
  }

}
