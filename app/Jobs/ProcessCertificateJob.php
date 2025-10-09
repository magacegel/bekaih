<?php

namespace App\Jobs;

use App\Lib\ReportGenerator;
use App\Models\CompanyCertificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certificateId;

    public function __construct($certificateId)
    {
        $this->certificateId = $certificateId;
    }

    public function handle()
    {
        $certificate = CompanyCertificate::find($this->certificateId);

        if (!$certificate or blank($certificate->certificate_file)) return;

        // Get the first page of the certificate
        $newPath = 'companies/' . $certificate->company_id . '/certificates/' . $certificate->id . '-first-page.pdf';
        $firstPage = ReportGenerator::takeFirstPageFromDisk($certificate->certificate_file,$newPath);

        if (!$firstPage) return;

        // Get current metadata or initialize as array
        $metadata = is_array($certificate->metadata) ? $certificate->metadata : [];

        // Update the metadata
        $metadata['first-page'] = $firstPage;

        // Save the certificate with updated metadata
        $certificate->metadata = $metadata;
        $certificate->save();
    }
}
