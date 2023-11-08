<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Models\Tool;
use App\Models\TopSearch;
use App\Models\TopSearchToolSemanticScore;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TopSearchService
{

    public static function saveSemanticDistanceBetweenTopSearchAndTools(
        TopSearch $topSearch,
    ): bool {

        $tools = MeilisearchService::vectorSearch(
            table: SearchAbleTable::TOOL,
            vectors: $topSearch->_vectors //already calculated vectors
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
