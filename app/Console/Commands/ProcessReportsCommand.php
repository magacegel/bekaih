<?php

namespace App\Console\Commands;

use App\Jobs\ProcessReportJob;
use App\Models\Report;
use Illuminate\Console\Command;

class ProcessReportsCommand extends Command
{
    protected $signature = 'reports:process';
    protected $description = 'Process reports with null metadata';

    public function handle()
    {
        $report = Report::select('id')->whereNull('metadata')
            ->inRandomOrder()
            ->first();

        if ($report) {
            ProcessReportJob::dispatch($report->id);
            $this->info('Dispatched job for report ID: ' . $report->id);
        } else {
            $this->info('No reports with null metadata found.');
        }
    }
}
