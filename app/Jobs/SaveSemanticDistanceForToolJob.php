<?php

namespace App\Jobs;

use App\Enums\ModelType;
use App\Models\Tool;
use App\Services\RecommendationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveSemanticDistanceForToolJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Tool $tool)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        RecommendationService::saveSemanticDistanceFor(
            tool: $this->tool,
            modelType: ModelType::All_MINI_LM_L6_V2,
        );
    }
}
