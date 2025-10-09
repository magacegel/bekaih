<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCompetencyJob;
use App\Models\Competency;
use Illuminate\Console\Command;

class ProcessCompetenciesCommand extends Command
{
    protected $signature = 'competencies:process';
    protected $description = 'Process competencies with null metadata';

    public function handle()
    {
        $competency = Competency::select('id')
            ->whereNotNull('certificate_file')
            ->where('certificate_file', '!=', '[]')
            ->whereNull('metadata')
            ->inRandomOrder()
            ->first();

        if ($competency) {
            ProcessCompetencyJob::dispatch($competency->id);
            $this->info('Dispatched job for competency ID: ' . $competency->id);
        }
    }
}
