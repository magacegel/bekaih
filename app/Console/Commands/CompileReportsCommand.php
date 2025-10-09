<?php

namespace App\Console\Commands;

use App\Jobs\ProcessReportJob;
use App\Models\Report;
use Illuminate\Console\Command;

class CompileReportsCommand extends Command
{
    protected $signature = 'reports:compile';
    protected $description = 'Compile reports with null metadata';

    public function handle()
    {
        $report = Report::select('id', 'supervisor_verifikasi', 'metadata')
            ->whereNotNull('metadata')
            ->where('supervisor_verifikasi', 'approved')
            ->inRandomOrder()
            ->first();

        if (!$report) return;
        if (isset($report->metadata['compiled'])) return; // already compiled

        ProcessReportJob::dispatch($report->id);
        $this->info('Dispatched job for report ID: ' . $report->id);

    }
}
