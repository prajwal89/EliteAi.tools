<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;

class BlogService
{
    public static function saveSemanticDistanceBetweenBlogAndTools(
        Blog $blog,
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

    /**
     *  updates in DB 
     *
     * @param Blog $blog
     * @return boolean
     */
    public static function updateVectorEmbeddings(Blog $blog): bool
    {
        $embeddings = MeilisearchService::getVectorEmbeddings(
            $blog->getParagraphForVectorEmbeddings(),
            config('custom.current_embedding_model')
        );

        return $blog->update([
            '_vectors' => $embeddings,
            'model_type' => config('custom.current_embedding_model')->value,
        ]);
    }
}
