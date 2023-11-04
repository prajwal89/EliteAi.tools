<?php

namespace App\Jobs;

use App\Models\Tool;
use App\Services\ToolServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateVectorEmbeddingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(public Tool $tool)
    {
    }


    public function handle(): void
    {
        ToolServices::updateVectorEmbeddings($this->tool);
    }
}
