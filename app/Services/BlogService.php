<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;
use Exception;

class BlogService
{
    public static function saveSemanticDistanceBetweenBlogAndTools(
        Blog $blog,
    ): bool {

        if (empty($blog->_vectors) || count($blog->_vectors) < 1) {
            throw new Exception('Vectors are not calculated for blog: ' . $blog->id);
        }

        $searchResults = MeilisearchService::vectorSearch(
            table: SearchAbleTable::TOOL,
            vectors: $blog->_vectors, //already calculated vectors
            configs: [
                'limit' => 100,
                'attributesToRetrieve' => ['id', 'name'],
            ]
        );

        // dd($searchResults);

        foreach ($searchResults['hits'] as $tool) {
            BlogToolSemanticScore::updateOrCreate([
                'tool_id' => $tool['id'],
                'blog_id' => $blog->id,
            ], [
                'score' => $tool['_semanticScore'],
                'model_type' => config('custom.current_embedding_model')->value,
            ]);
        }

        return true;
    }

    /**
     *  updates in DB only
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
