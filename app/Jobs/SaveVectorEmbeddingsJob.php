<?php

namespace App\Jobs;

use App\Models\Blog;
use App\Models\Tool;
use App\Models\TopSearch;
use App\Services\BlogService;
use App\Services\ToolService;
use App\Services\TopSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;
use Throwable;

/**
 * Update vector embedding of given model instance
 */
class SaveVectorEmbeddingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Model $model)
    {
    }

    public function handle(): void
    {
        // this will update embeddings on DB as well as meilisearch index if available
        match (get_class($this->model)) {
            Tool::class => ToolService::updateVectorEmbeddings($this->model),
            Blog::class => BlogService::updateVectorEmbeddings($this->model),
            TopSearch::class => TopSearchService::updateVectorEmbeddings($this->model),
            default => throw new InvalidArgumentException(
                'Cannot update embeddings of: ' . get_class($this->model)
            )
        };
    }

    // todo send slack notification
    public function failed(Throwable $exception): void
    {
        // Send user notification of failure, etc...
    }
}
