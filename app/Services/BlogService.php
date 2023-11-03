<?php

namespace App\Services;

use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;
use App\Models\Tool;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class BlogService
{

    public static function saveSemanticDistanceBetweenBlogAndTools(
        Blog $blog,
        ModelType $modelType
    ): bool {

        $tools = MeilisearchService::vectorSearch(
            SearchAbleTable::TOOL,
            $blog->getParagraphForVectorEmbeddings()
        );

        foreach ($tools['hits'] as $tool) {
            BlogToolSemanticScore::updateOrCreate([
                'tool_id' => $tool['id'],
                'blog_id' => $blog->id,
            ], [
                'score' => $tool['_semanticScore'],
            ]);
        }

        return true;
    }
}
