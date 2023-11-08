<?php

namespace App\Jobs;

use App\Models\TopSearch;
use App\Services\TopSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSemanticDistanceBetweenTopSearchAndToolJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public TopSearch $topSearch)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TopSearchService::saveSemanticDistanceBetweenTopSearchAndTools(
            $this->topSearch
        );
    }
}
