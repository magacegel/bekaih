<?php

namespace App\Jobs;

use App\Lib\ReportGenerator;
use App\Models\Competency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCompetencyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $competencyId;

    public function __construct($competencyId)
    {
        $this->competencyId = $competencyId;
    }

    public function handle()
    {
        $competency = Competency::find($this->competencyId);

        if (!$competency or blank($competency->certificate_file)) return;

        // Get the first page of the competency
        $newPath = 'users/' . $competency->user_id . '/competencies/' . $competency->id . '-first-page.pdf';
        $firstPage = ReportGenerator::takeFirstPageFromDisk($competency->certificate_file, $newPath);

        if (!$firstPage) return;

        // Get current metadata or initialize as array
        $metadata = is_array($competency->metadata) ? $competency->metadata : [];

        // Update the metadata
        $metadata['first-page'] = $firstPage;

        // Save the competency with updated metadata
        $competency->metadata = $metadata;
        $competency->save();
    }
}
