<?php

namespace App\Http\Controllers;

use App\Lib\ReportGenerator;
use App\Models\Company;
use App\Models\CompanyCertificate;
use App\Models\User;
use App\Models\Competency;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    /**
     * Helper method to generate organized file path structure
     * @param int $companyId
     * @param int $userId
     * @param string $fileType (certificates, reports, company_files)
     * @param string $fileName
     * @return string
     */
    private function generateFilePath($companyId, $userId = null, $fileType = 'certificates', $fileName = '')
    {
        $doDirectory = env('DO_DIRECTORY', 'staging');
        
        if ($userId) {
            // For user-specific files: staging/{company_id}/{file_type}/{user_id}/filename
            return $doDirectory . '/' . $companyId . '/' . $fileType . '/' . $userId . '/' . $fileName;
        } else {
            // For company files: staging/{company_id}/{file_type}/filename
            return $doDirectory . '/' . $companyId . '/' . $fileType . '/' . $fileName;
        }
    }

    //

    public function index()
    {
        if (auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('inspektor')) {
            return redirect()->route('company.show', ['id' => auth()->user()->company_id]);
        }

        $companies = Company::all();
        return view('company.index', compact('companies'));
    }

    public function data()
    {
        $companies = Company::with('activeCertificate')->get();

        return datatables()->of($companies)
            ->addColumn('name', function ($company) {
                return $company->name . ($company->branch ? ' (' . $company->branch . ')' : '');
            })
            ->addColumn('certificate_number', function ($company) {
                return $company->activeCertificate ? $company->activeCertificate->number : 'N/A';
            })
            ->addColumn('approval_number', function ($company) {
                return $company->activeCertificate ? $company->activeCertificate->approval_number : 'N/A';
            })
            ->addColumn('approval_date', function ($company) {
                return $company->activeCertificate ? \Carbon\Carbon::parse($company->activeCertificate->approval_date)->format('d/m/Y') : 'N/A';
            })
            ->addColumn('expired_date', function ($company) {
                return $company->activeCertificate ? \Carbon\Carbon::parse($company->activeCertificate->expired_date)->format('d/m/Y') : 'N/A';
            })
            ->rawColumns(['name', 'certificate_number', 'approval_number', 'approval_date', 'expired_date'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'branch' => 'nullable',
            'zip_code' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificate_number' => 'required',
            'approval_number' => 'required',
            'approval_date' => 'required|date',
            'expired_date' => 'required|date',
            'certificate_file' => 'required|file|mimes:pdf|max:20480'
        ]);

        $perusahaan = new Company();
        $perusahaan->name = $request->name;
        $perusahaan->branch = $request->branch;
        $perusahaan->address = $request->address;
        $perusahaan->city = $request->city;
        $perusahaan->zip_code = $request->zip_code;

        $perusahaan->save();

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $sanitizedName = sanitize_filename($logoFile->getClientOriginalName());
            $logoFileName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoPath = $this->generateFilePath($perusahaan->id, null, 'company_logo', $logoFileName);

            // Save original logo to DigitalOcean
            Storage::disk('digitalocean')->put($logoPath, file_get_contents($logoFile));
            $perusahaan->logo = $logoPath;

            // Create and save resized version
            $image = Image::make($logoFile);
            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resizedFileName = 'logo_resized_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $resizedPath = $this->generateFilePath($perusahaan->id, null, 'company_logo', $resizedFileName);
            Storage::disk('digitalocean')->put($resizedPath, (string)$image->encode());
            $perusahaan->logo_resized = $resizedPath;
        }

        $perusahaan->save();

        $certificate = new CompanyCertificate();
        $certificate->number = $request->certificate_number;
        $certificate->approval_number = $request->approval_number;
        $certificate->approval_date = $request->approval_date;
        $certificate->expired_date = $request->expired_date;

        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $sanitizedName = sanitize_filename($file->getClientOriginalName());
            $fileName = 'company_certificate_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $this->generateFilePath($perusahaan->id, null, 'company_certificates', $fileName);
            
            // Save certificate file to DigitalOcean
            Storage::disk('digitalocean')->put($filePath, file_get_contents($file));
            $certificate->certificate_file = $filePath;
            ReportGenerator::takeFirstPageFromUploadedFile($certificate, $file, $filePath);
        } else if ($certificate->certificate_file && blank(Arr::get($certificate->metadata, 'first-page'))) {
            if (!is_array($certificate->metadata)) $certificate->metadata = [];
            $certificate->metadata['first-page'] = ReportGenerator::takeFirstPageFromDisk($certificate->certificate_file);
        }

        $certificate->company_id = $perusahaan->id;
        $certificate->save();

        $perusahaan->company_certificate_id = $certificate->id;
        $perusahaan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data perusahaan berhasil disimpan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'city' => 'nullable',
            'branch' => 'nullable',
            'zip_code' => 'nullable',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificate_number' => 'required',
            'approval_number' => 'required',
            'approval_date' => 'nullable|date',
            'expired_date' => 'required|date',
            'certificate_file' => 'mimes:pdf|max:20480'
        ]);

        $perusahaan = Company::findOrFail($id);
        $perusahaan->name = $request->name;
        $perusahaan->address = $request->address;
        $perusahaan->city = $request->city;
        $perusahaan->zip_code = $request->zip_code;

        if ($request->hasFile('logo')) {
            // Delete old logo files from DigitalOcean if they exist
            if ($perusahaan->logo) {
                Storage::disk('digitalocean')->delete($perusahaan->logo);
                Storage::disk('digitalocean')->delete($perusahaan->logo_resized);
            }
            
            $logoFile = $request->file('logo');
            $sanitizedName = sanitize_filename($logoFile->getClientOriginalName());
            $logoFileName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoPath = $this->generateFilePath($perusahaan->id, null, 'company_logo', $logoFileName);

            // Save original logo to DigitalOcean
            Storage::disk('digitalocean')->put($logoPath, file_get_contents($logoFile));
            $perusahaan->logo = $logoPath;

            // Create and save resized version
            $image = Image::make($logoFile);
            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resizedFileName = 'logo_resized_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $resizedPath = $this->generateFilePath($perusahaan->id, null, 'company_logo', $resizedFileName);
            Storage::disk('digitalocean')->put($resizedPath, (string)$image->encode());
            $perusahaan->logo_resized = $resizedPath;
        }

        $perusahaan->save();

        $activeCertificate = $perusahaan->activeCertificate;

        if ($activeCertificate &&
            $activeCertificate->number == $request->certificate_number &&
            $activeCertificate->approval_number == $request->approval_number) {
            $certificate = $activeCertificate;
        } else {
            $certificate = new CompanyCertificate();
        }
        $certificate->number = $request->certificate_number;
        $certificate->approval_number = $request->approval_number;
        $certificate->approval_date = $request->approval_date;
        $certificate->expired_date = $request->expired_date;

        if ($request->hasFile('certificate_file')) {
            // Delete old certificate file from DigitalOcean if it exists
            if ($certificate->certificate_file) {
                Storage::disk('digitalocean')->delete($certificate->certificate_file);
            }
            
            $file = $request->file('certificate_file');
            $sanitizedName = sanitize_filename($file->getClientOriginalName());
            $fileName = 'company_certificate_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $this->generateFilePath($perusahaan->id, null, 'company_certificates', $fileName);
            
            // Save certificate file to DigitalOcean
            Storage::disk('digitalocean')->put($filePath, file_get_contents($file));
            $certificate->certificate_file = $filePath;
        }

        $certificate->company_id = $perusahaan->id;
        $certificate->save();

        $perusahaan->company_certificate_id = $certificate->id;
        $perusahaan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data perusahaan berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $perusahaan = Company::findOrFail($id);

        // Delete logo files from DigitalOcean if they exist
        if ($perusahaan->logo) {
            Storage::disk('digitalocean')->delete($perusahaan->logo);
            Storage::disk('digitalocean')->delete($perusahaan->logo_resized);
        }

        // Delete certificate files from DigitalOcean
        foreach ($perusahaan->certificates as $certificate) {
            if ($certificate->certificate_file) {
                Storage::disk('digitalocean')->delete($certificate->certificate_file);
            }
            $certificate->delete();
        }

        $perusahaan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data perusahaan berhasil dihapus'
        ]);
    }


    public function user_data(Company $company)
    {
        $users = $company->users;
        return datatables()->of($users)
            ->make(true);
    }

    /**
     * Display a company certificate
     *
     * @param string $token
     * @param int $id
     */
    public function comcerts($id)
    {
        $certificate = CompanyCertificate::findOrFail($id);

        if (!$certificate->certificate_file) {
            abort(404, 'Certificate file not found');
        }

        try {
            // Check if file exists first
            if (!Storage::disk('digitalocean')->exists($certificate->certificate_file)) {
                abort(404, 'Certificate file not found');
            }

            // Get the configuration for DigitalOcean
            $doConfig = config('filesystems.disks.digitalocean');
            $baseUrl = $doConfig['url'] ?? $doConfig['bucket_endpoint'];
            
            // Construct the URL manually
            $url = rtrim($baseUrl, '/') . '/' . ltrim($certificate->certificate_file, '/');

            return redirect()->away($url);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to generate certificate URL: ' . $e->getMessage());
            abort(500, 'Failed to generate certificate URL');
        }
    }

    /**
     * Store a new inspector for a company (User data only)
     */
    public function storeInspector(Request $request)
    {
        try {
            $request->validate([
                'company_id' => 'required|exists:companies,id',
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:15',
                'ktp' => 'required|string|max:16|unique:users,ktp',
                'role' => 'required|string',
                'password' => 'required|min:6|confirmed'
            ]);

            // Validate role exists and user has permission to assign it
            $role = Role::where('name', $request->role)->first();
            if (!$role) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Role tidak valid.'
                ], 400);
            }

            // Security check: prevent creating superadmin users
            if ($request->role === 'superadmin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat membuat user dengan role Super Admin untuk keamanan sistem.'
                ], 403);
            }

            // Check user permissions
            if (auth()->user()->hasRole('administrator') && !in_array($request->role, ['inspektor', 'supervisor'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses untuk memberikan role ini.'
                ], 403);
            }


            // Create the user
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->ktp = $request->ktp;
            $user->company_id = $request->company_id;
            $user->password = bcrypt($request->password);
            $user->level = Role::where('name', $request->role)->first()->name;
            $user->save();

            // Assign role
            $user->assignRole($request->role);

            return response()->json([
                'status' => 'success',
                'message' => 'Inspektor berhasil ditambahkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an inspector (User data only)
     */
    public function updateInspector(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validationRules = [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:15',
                'ktp' => 'required|string|max:16|unique:users,ktp,' . $id,
                'role' => 'required|string',
                'password' => 'nullable|min:6|confirmed'
            ];

            $request->validate($validationRules);

            // Validate role exists and user has permission to assign it
            $role = Role::where('name', $request->role)->first();
            if (!$role) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Role tidak valid.'
                ], 400);
            }

            // Security check: prevent creating superadmin users
            if ($request->role === 'superadmin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memberikan role Super Admin untuk keamanan sistem.'
                ], 403);
            }

            // Check user permissions
            if (auth()->user()->hasRole('administrator') && !in_array($request->role, ['inspektor', 'supervisor'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses untuk memberikan role ini.'
                ], 403);
            }

            // Update user basic info
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->ktp = $request->ktp;
            
            // Update password only if provided
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            
            $user->save();

            // Update role
            $user->syncRoles([$request->role]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data inspektor berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new competency for a user
     */
    public function storeCompetency(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'qualification' => 'required|string|max:255',
                'certificate_number' => 'required|string|max:255',
                'expired_date' => 'required|date',
                'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
            ]);

            // Get user and company information
            $user = User::findOrFail($request->user_id);
            $company = $user->company;

            $competency = new Competency();
            $competency->user_id = $request->user_id;
            $competency->qualification = $request->qualification;
            $competency->certificate_number = $request->certificate_number;
            $competency->expired_date = $request->expired_date;

            // Handle file upload with organized folder structure
            if ($request->hasFile('certificate_file')) {
                $file = $request->file('certificate_file');
                $sanitizedName = sanitize_filename($file->getClientOriginalName());
                $timestamp = date('YmdHis');
                $fileName = 'certificate_' . $user->id . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
                
                // Create organized folder structure: staging/{company_id}/certificates/{user_id}/
                $doDirectory = env('DO_DIRECTORY', 'staging');
                $filePath = $doDirectory . '/' . $company->id . '/certificates/' . $user->id . '/' . $fileName;
                
                // Store to Digital Ocean
                Storage::disk('digitalocean')->put($filePath, file_get_contents($file));
                $competency->certificate_file = $filePath;
            }

            $competency->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Kompetensi berhasil ditambahkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a competency
     */
    public function deleteCompetency($id)
    {
        try {
            $competency = Competency::findOrFail($id);
            
            // Delete file if exists
            if ($competency->certificate_file) {
                Storage::disk('digitalocean')->delete($competency->certificate_file);
            }
            
            $competency->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Kompetensi berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an inspector
     */
    public function deleteInspector($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Check if user has any reports
            $reportCount = Report::where('user_id', $user->id)->count();
            if ($reportCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus inspektor karena masih memiliki laporan yang terkait.'
                ], 400);
            }

            // Delete competency files first
            $competencies = Competency::where('user_id', $user->id)->get();
            foreach ($competencies as $competency) {
                if ($competency->certificate_file) {
                    Storage::disk('digitalocean')->delete($competency->certificate_file);
                }
            }

            // Delete competencies
            Competency::where('user_id', $user->id)->delete();
            
            // Delete user
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Inspektor berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
