<?php

namespace App\Jobs;

use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSemanticDistanceBetweenBlogAndToolJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Blog $blog)
    {
    }

    public function handle(): void
    {
        BlogService::saveSemanticDistanceBetweenBlogAndTools(
            $this->blog
        );
    }
}
