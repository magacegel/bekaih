<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEquipmentCertificateJob;
use App\Models\Certification;
use Illuminate\Console\Command;

class ProcessEquipmentCertificatesCommand extends Command
{
    protected $signature = 'equipment-certificates:process';
    protected $description = 'Process equipment certificates with null metadata';

    public function handle()
    {
        $certificate = Certification::select('id')
            ->whereNotNull('url')
            ->where('url', '!=', '[]')
            ->whereNull('metadata')
            ->inRandomOrder()
            ->first();

        if ($certificate) {
            ProcessEquipmentCertificateJob::dispatch($certificate->id);
            $this->info('Dispatched job for equipment-certificate ID: ' . $certificate->id);
        }
    }
}
