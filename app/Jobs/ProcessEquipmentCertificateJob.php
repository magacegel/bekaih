<?php

namespace App\Jobs;

use App\Lib\ReportGenerator;
use App\Models\Certification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEquipmentCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certificateId;

    public function __construct($certificateId)
    {
        $this->certificateId = $certificateId;
    }

    public function handle()
    {
        $certificate = Certification::find($this->certificateId);

        if (!$certificate or blank($certificate->url)) return;

        // Get the first page of the certificate
        $newPath = 'equipments/' . $certificate->equipment_id . '/certificates/' . $certificate->id . '-first-page.pdf';
        $firstPage = ReportGenerator::takeFirstPageFromDisk($certificate->url, $newPath);

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
