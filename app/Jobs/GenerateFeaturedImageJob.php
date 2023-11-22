<?php

namespace App\Jobs;

use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateFeaturedImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Blog $blog)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        BlogService::generateFeaturedImage($this->blog);
    }
}
