<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Jobs\SaveVectorEmbeddingsJob;
use App\Jobs\SaveSemanticDistanceBetweenTopSearchAndToolJob;
use App\Models\TopSearch;
use App\Models\TopSearchToolSemanticScore;
use Exception;

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

        $tools = MeilisearchService::vectorSearch(
            table: SearchAbleTable::TOOL,
            vectors: $topSearch->_vectors, //already calculated vectors
            configs: [
                'limit' => 100,
                'attributesToRetrieve' => ['id', 'name'],
            ]
        );

        foreach ($tools['hits'] as $tool) {
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
}
