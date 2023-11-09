<?php

namespace App\Jobs;

use App\Models\Blog;
use App\Models\Tool;
use App\Models\TopSearch;
use App\Services\BlogService;
use App\Services\ToolServices;
use App\Services\TopSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

/**
 * Update vector embedding of given model instance
 */
class UpdateVectorEmbeddingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Model $model)
    {
    }

    public function handle(): void
    {
        // this wil update embeddings on DB as well as meilisearch index if available
        match (get_class($this->model)) {
            Tool::class => ToolServices::updateVectorEmbeddings($this->model),
            Blog::class => BlogService::updateVectorEmbeddings($this->model),
            TopSearch::class => TopSearchService::updateVectorEmbeddings($this->model),
            default => throw new InvalidArgumentException(
                'Cannot update embeddings of: ' . get_class($this->model)
            )
        };
    }
}
