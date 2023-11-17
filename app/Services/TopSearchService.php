<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Jobs\SaveSemanticDistanceBetweenTopSearchAndToolJob;
use App\Jobs\SaveVectorEmbeddingsJob;
use App\Models\TopSearch;
use App\Models\TopSearchToolSemanticScore;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TopSearchService
{
    public static function store(array $attributes): ?TopSearch
    {
        $topSearch = TopSearch::create([
            'query' => $attributes['query'],
            'slug' => str($attributes['query'])->slug()->toString(),
        ]);

        // ! if this fails semantic distance will not work is vectors are not updated
        // dispatch(new SaveVectorEmbeddingsJob($topSearch));

        TopSearchService::updateVectorEmbeddings($topSearch);

        dispatch(new SaveSemanticDistanceBetweenTopSearchAndToolJob($topSearch));

        return $topSearch;
    }

    public static function saveSemanticDistanceBetweenTopSearchAndTools(
        TopSearch $topSearch,
    ): bool {

        if (empty($topSearch->_vectors) || count($topSearch->_vectors) < 1) {
            throw new Exception('Vectors are not calculated for TopSearch: ' . $topSearch->id);
        }

        $searchResultsTools = MeilisearchService::vectorSearch(
            table: SearchAbleTable::TOOL,
            vectors: $topSearch->_vectors, //already calculated vectors
            configs: [
                'limit' => 100,
                'attributesToRetrieve' => ['id', 'name'],
            ]
        );

        // dd($searchResultsTools);

        foreach ($searchResultsTools['hits'] as $tool) {
            TopSearchToolSemanticScore::updateOrCreate([
                'tool_id' => $tool['id'],
                'top_search_id' => $topSearch->id,
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
    public static function updateVectorEmbeddings(TopSearch $topSearch): bool
    {
        $embeddings = MeilisearchService::getVectorEmbeddings(
            $topSearch->getParagraphForVectorEmbeddings(),
            config('custom.current_embedding_model')
        );

        return $topSearch->update([
            '_vectors' => $embeddings,
            'model_type' => config('custom.current_embedding_model')->value,
        ]);
    }

    /**
     * all auto generated top_search pages that can be indexed on search engin
     * criteria
     *  min 5 tools required on page
     *  tool should have score > 0.85
     *
     * @return array
     */
    // ! should i cache this query
    public static function qualifiedForIndexingTopSearchIds(): Collection
    {
        return DB::table('top_search_tool_semantic_scores')
            ->select([
                // '*',
                'top_searches.id as top_search_id',
                DB::raw('count(*) as total_tools'),
            ])
            ->join('tools', 'tools.id', '=', 'top_search_tool_semantic_scores.tool_id')
            ->join('top_searches', 'top_searches.id', '=', 'top_search_tool_semantic_scores.top_search_id')
            ->where('top_search_tool_semantic_scores.score', '>', 0.85)
            ->having('total_tools', '>', 5)
            ->groupBy('top_searches.id')
            ->get()
            ->pluck('top_search_id');
    }
}
