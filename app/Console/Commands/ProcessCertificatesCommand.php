<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCertificateJob;
use App\Models\CompanyCertificate;
use Illuminate\Console\Command;

class ProcessCertificatesCommand extends Command
{
    protected $signature = 'certificates:process';
    protected $description = 'Process certificates with null metadata';

    public function handle()
    {
        $certificate = CompanyCertificate::select('id')
            ->whereNotNull('certificate_file')
            ->where('certificate_file', '!=', '[]')
            ->whereNull('metadata')
            ->inRandomOrder()
            ->first();

        if ($certificate) {
            ProcessCertificateJob::dispatch($certificate->id);
            $this->info('Dispatched job for certificate ID: ' . $certificate->id);
        }
    }
}
