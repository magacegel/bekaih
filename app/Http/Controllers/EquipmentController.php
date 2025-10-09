<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Certification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('superadmin')) {
            $equipments = Equipment::all();
        } else {
            $equipments = Equipment::where('company_id', auth()->user()->company_id)->get();
        }
        return view('equipment.index', compact('equipments'));
    }

    public function show($id)
    {
        $equipment = Equipment::findOrFail($id);
        if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('administrator') || auth()->user()->company_id == $equipment->company_id) {
            $activeCertificates = $equipment->activeCertifications;
            return view('equipment.show', compact('equipment', 'activeCertificates'));
        } else {
            return redirect()->route('equipment.index')->with('error', 'Anda tidak memiliki izin untuk melihat detail equipment.');
        }
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['superadmin', 'administrator', 'supervisor'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah equipment.'
            ], 403);
        }

        // Validasi input
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial' => 'required|string|max:255',
            'tolerance' => 'required|numeric',
            'probe_type' => 'required|string|max:255',
            'certificate_number' => 'required|string|max:255',
            'certificate_date' => 'required|date',
            'calibration_certificate' => 'required|file|mimes:pdf|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Buat equipment baru
            $equipment = new Equipment();
            $equipment->name = $request->name;
            $equipment->manufactur = $request->manufacturer;
            $equipment->model = $request->model;
            $equipment->serial = $request->serial;
            $equipment->tolerancy = $request->tolerance;
            $equipment->probe_type = $request->probe_type;
            $equipment->user_id = auth()->id();
            $equipment->company_id = auth()->user()->company_id;
            $equipment->save();

            // Simpan sertifikat kalibrasi
            if ($request->hasFile('calibration_certificate')) {
                $file = $request->file('calibration_certificate');
                $sanitizedName = sanitize_filename($file->getClientOriginalName());
                $fileName = time() . '_' . $sanitizedName;
                $filePath = env('DO0_DIRECTORY', '') . '/' . auth()->user()->company_id . '/equipments/' . $equipment->id . '/certificate/' . $fileName;

                Storage::disk('digitalocean')->put($filePath, file_get_contents($file));

                $equipment->certifications()->create([
                    'url' => $filePath,
                    'certificate_number' => $request->certificate_number,
                    'certificate_date' => $request->certificate_date,
                    'active' => 1
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Equipment berhasil ditambahkan',
                'data' => $equipment
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file yang telah disimpan jika ada
            if ($request->hasFile('calibration_certificate') && isset($filePath)) {
                Storage::disk('digitalocean')->delete($filePath);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        $equipment = Equipment::findOrFail($id);

        if (!$this->canModifyEquipment($equipment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus equipment ini.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Cek apakah equipment sedang digunakan dalam laporan atau relasi lain
            $isUsed = DB::table('reports')->where('equipment_id', $equipment->id)->exists();
            
            if ($isUsed) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Equipment tidak dapat dihapus karena sedang digunakan dalam laporan.'
                ], 400);
            }

            // Hapus sertifikat dan file terkait
            foreach ($equipment->certifications as $certification) {
                // Hapus file jika ada dan URL tidak kosong
                if ($certification->url && Storage::disk('digitalocean')->exists($certification->url)) {
                    try {
                        Storage::disk('digitalocean')->delete($certification->url);
                    } catch (\Exception $fileError) {
                        // Log error tapi lanjutkan proses penghapusan
                        Log::warning('Gagal menghapus file sertifikat: ' . $certification->url . ' - ' . $fileError->getMessage());
                    }
                }

                // Hapus record sertifikasi dari database
                $certification->delete();
            }

            // Hapus equipment
            $equipment->delete();

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Equipment berhasil dihapus'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            
            // Handle foreign key constraint error
            if ($e->getCode() == '23000') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Equipment tidak dapat dihapus karena masih terkait dengan data lain.'
                ], 400);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan database saat menghapus equipment: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function save($id, Request $request)
    {
        $equipment = Equipment::findOrFail($id);

        if (!$this->canModifyEquipment($equipment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit equipment ini.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Validasi input
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'manufacturer' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'serial' => 'required|string|max:255',
                'tolerance' => 'required|numeric',
                'probe_type' => 'required|string|max:255',
                'certificate_number' => 'nullable|string|max:255',
                'certificate_date' => 'nullable|date',
                'calibration_certificate' => 'nullable|file|mimes:pdf|max:20480', // Max 20MB
            ]);

            // Update data equipment
            $equipment->update([
                'name' => $validatedData['name'],
                'manufacturer' => $validatedData['manufacturer'],
                'model' => $validatedData['model'],
                'serial' => $validatedData['serial'],
                'tolerance' => $validatedData['tolerance'],
                'probe_type' => $validatedData['probe_type'],
            ]);

            // Cari sertifikat berdasarkan nomor sertifikat
            $certification = $equipment->certifications()
                ->where('certificate_number', $validatedData['certificate_number'])
                ->first();

            if ($certification) {
                // Update sertifikat yang ada
                $certification->update([
                    'certificate_date' => $validatedData['certificate_date'],
                    'active' => 1
                ]);

                // Jika ada file baru, update file sertifikat
                if ($request->hasFile('calibration_certificate')) {
                    $file = $request->file('calibration_certificate');
                    $sanitizedName = sanitize_filename($file->getClientOriginalName());
                    $fileName = time() . '_' . $sanitizedName;
                    $filePath = Storage::disk('digitalocean')->putFileAs(
                        env('DO0_DIRECTORY') . '/' . $equipment->company_id . '/equipments/' . $equipment->id . '/certificate',
                        $file,
                        $fileName
                    );

                    // Hapus file lama jika ada
                    if ($certification->url) {
                        Storage::disk('digitalocean')->delete($certification->url);
                    }

                    $certification->update(['url' => $filePath]);
                }
            } else {
                // Buat sertifikat baru
                $newCertification = [
                    'certificate_number' => $validatedData['certificate_number'],
                    'certificate_date' => $validatedData['certificate_date'],
                    'active' => 1
                ];

                if ($request->hasFile('calibration_certificate')) {
                    $file = $request->file('calibration_certificate');
                    $sanitizedName = sanitize_filename($file->getClientOriginalName());
                    $fileName = time() . '_' . $sanitizedName;
                    $filePath = Storage::disk('digitalocean')->putFileAs(
                        env('DO0_DIRECTORY') . '/' . $equipment->company_id . '/equipments/' . $equipment->id . '/certificate',
                        $file,
                        $fileName
                    );
                    $newCertification['url'] = $filePath;
                }

                $certification = $equipment->certifications()->create($newCertification);
            }

            // Set semua sertifikat lain menjadi tidak aktif
            $equipment->certifications()
                ->where('id', '!=', $certification->id)
                ->update(['active' => 0]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data equipment berhasil diperbarui',
                'data' => [
                    'equipment' => $equipment,
                    'certificate_file' => $certification->url ?? null
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addCertificate(Request $request)
    {
        $equipment = Equipment::findOrFail($request->equipment_id);

        if (!$this->canModifyEquipment($equipment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menambah sertifikat.'
            ], 403);
        }

        $validatedData = $request->validate([
            'certificate_number' => 'required|string|max:255',
            'certificate_date' => 'required|date',
            'certificate_file' => 'required|file|mimes:pdf|max:20480',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('certificate_file');
            $sanitizedName = sanitize_filename($file->getClientOriginalName());
            $fileName = time() . '_' . $sanitizedName;
            $filePath = Storage::disk('digitalocean')->putFileAs(
                env('DO0_DIRECTORY') . '/' . $equipment->company_id . '/equipments/' . $equipment->id . '/certificate',
                $file,
                $fileName
            );

            $equipment->certifications()->create([
                'certificate_number' => $validatedData['certificate_number'],
                'certificate_date' => $validatedData['certificate_date'],
                'url' => $filePath,
                'active' => 1
            ]);

            // Set semua sertifikat lain menjadi tidak aktif
            $equipment->certifications()
                ->where('certificate_number', '!=', $validatedData['certificate_number'])
                ->update(['active' => 0]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sertifikat berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteCertificate($id)
    {
        $certification = Certification::findOrFail($id);
        $equipment = $certification->equipment;

        if (!$this->canModifyEquipment($equipment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus sertifikat.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Hapus file dari storage
            if ($certification->url) {
                Storage::disk('digitalocean')->delete($certification->url);
            }

            $certification->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sertifikat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCertificate(Request $request, $id)
    {
        $certification = Certification::findOrFail($id);
        $equipment = $certification->equipment;

        if (!$this->canModifyEquipment($equipment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk memperbarui sertifikat.'
            ], 403);
        }

        $validatedData = $request->validate([
            'certificate_number' => 'required|string|max:255',
            'certificate_date' => 'required|date',
            'certificate_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $certification->certificate_number = $validatedData['certificate_number'];
            $certification->certificate_date = $validatedData['certificate_date'];

            if ($request->hasFile('certificate_file')) {
                // Hapus file lama
                if ($certification->url) {
                    Storage::disk('digitalocean')->delete($certification->url);
                }

                // Upload file baru
                $file = $request->file('certificate_file');
                $sanitizedName = sanitize_filename($file->getClientOriginalName());
                $fileName = time() . '_' . $sanitizedName;
                $filePath = Storage::disk('digitalocean')->putFileAs(
                    env('DO0_DIRECTORY') . '/' . $equipment->company_id . '/equipments/' . $equipment->id . '/certificate',
                    $file,
                    $fileName
                );
                $certification->url = $filePath;
            }

            $certification->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sertifikat berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleCertificateActive($id)
    {
        $certification = Certification::findOrFail($id);
        $equipment = $certification->equipment;

        if (!$this->canModifyEquipment($equipment)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengubah status sertifikat.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $certification->active = !$certification->active;
            $certification->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Status sertifikat berhasil diperbarui',
                'active' => $certification->active
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui status sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCertificates($equipmentId)
    {
        $equipment = Equipment::findOrFail($equipmentId);
        $certificates = $equipment->certifications;

        return response()->json([
            'status' => 'success',
            'data' => $certificates
        ]);
    }

    private function canModifyEquipment($equipment)
    {
        $user = auth()->user();
        return $user->hasRole('superadmin') ||
               $user->hasRole('administrator') ||
               ($user->hasRole('supervisor') && $user->company_id == $equipment->company_id);
    }
}
