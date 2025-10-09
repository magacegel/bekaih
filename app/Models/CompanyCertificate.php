<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CompanyCertificate extends Model
{
    use HasFactory;
    protected $table = 'company_certificates';

    protected $fillable = [
        'certificate_file',
        'certificate_resized_file',
        'number',
        'approval_number',
        'approval_date',
        'expired_date',
        'company_id'
    ];

    protected $casts = [
        'approval_date' => 'date',
        'expired_date' => 'date',
        'metadata' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the full URL for the certificate file
     */
    public function getCertificateUrlAttribute()
    {
        if (!$this->certificate_file) {
            return null;
        }

        try {
            $doConfig = config('filesystems.disks.digitalocean');
            $baseUrl = $doConfig['url'] ?? $doConfig['bucket_endpoint'] ?? '';
            return rtrim($baseUrl, '/') . '/' . ltrim($this->certificate_file, '/');
        } catch (\Exception $e) {
            Log::error('Failed to generate certificate URL: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the certificate file exists in storage
     */
    public function certificateFileExists()
    {
        if (!$this->certificate_file) {
            return false;
        }

        try {
            return Storage::disk('digitalocean')->exists($this->certificate_file);
        } catch (\Exception $e) {
            Log::error('Failed to check certificate file existence: ' . $e->getMessage());
            return false;
        }
    }
}
