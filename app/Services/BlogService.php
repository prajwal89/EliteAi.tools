<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;
use Exception;
use Illuminate\Support\Facades\DB;

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

        foreach ($searchResults->hits as $tool) {
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

    /**
     * all blogs with blog_type = Semantic Score that can be indexed on search engine
     * criteria
     *  min 5 tools required on page
     *  tool should have score > 0.85
     *
     * @return array
     */
    // ! should i cache this query
    public static function qualifiedForIndexingBlogIds()
    {
        return DB::table('blog_tool_semantic_scores')
            ->select(['blog_id', DB::raw('count(*) as total_tools')])
            ->join('tools', 'tools.id', '=', 'blog_tool_semantic_scores.tool_id')
            ->join('blogs', 'blogs.id', '=', 'blog_tool_semantic_scores.blog_id')
            // ->where('blog_tool_semantic_scores.score', '>',  config('custom.blog_page.minimum_semantic_score'))
            ->where('blog_tool_semantic_scores.score', '>', DB::raw('blogs.min_semantic_score'))
            ->having('total_tools', '>=', config('custom.blog_page.minimum_tools_required'))
            ->groupBy('blogs.id')
            ->get()
            ->pluck('blog_id');
    }
}
