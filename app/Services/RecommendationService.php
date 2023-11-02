<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Models\SemanticScore;
use App\Models\Tool;
use App\ValueObjects\SemanticScore as ValueObjectsSemanticScore;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Save distance of $toolId relative to all other tools
     *
     * @return void
     */
    public static function saveSemanticDistanceFor(
        Tool $tool,
        ModelType $modelType,
        int $toolsLimit = 500,
    ) {
        $results = MeilisearchService::vectorSearch(
            SearchAbleTable::TOOL,
            $tool->getParagraphForVectorEmbeddings(),
            ['limit' => $toolsLimit]
        );

        foreach ($results['hits'] as $hit) {
            // *same tool this will always result in 1.00000000
            if ($tool->id == $hit['id']) {
                continue;
            }

            SemanticScore::updateOrCreate([
                'tool1_id' => min($tool->id, $hit['id']),
                'tool2_id' => max($tool->id, $hit['id']),
                'model_type' => $modelType->value
            ], [
                'score' => $hit['_semanticScore'],
            ]);
        }

        return $results;
    }

    /**
     * hint for scrutiny: count of all possible combinations of pairs of numbers from e.g, 1,39
     * and do not count pair having same digits
     */
    public static function baseOnSemanticScores(
        int $forToolId,
        float $score = 0.5,
        int $maxTools = 500
    ): Collection {

        $semanticScore = new ValueObjectsSemanticScore($score);

        $scores = SemanticScore::where(function ($query) use ($forToolId) {
            $query->orWhere('tool1_id', $forToolId);
            $query->orWhere('tool2_id', $forToolId);
        })
            ->where('score', '>', $semanticScore->getScore())
            ->orderBy('score', 'desc')
            ->limit($maxTools)
            ->get();

        $toolIds = $scores->map(function ($score) {
            return [$score->tool1_id, $score->tool2_id];
        })
            ->flatten()
            ->filter(function ($tooId) use ($forToolId) {
                //* remove current tool that we are getting alternatives for
                return $tooId !== $forToolId;
            })
            ->unique();

        $resultTools = Tool::with(['categories'])
            ->whereIn('id', $toolIds->toArray())
            ->get()
            ->sortBy(function ($tool) use ($toolIds) {
                return array_search($tool->id, $toolIds->toArray());
            });

        // dump($scores->toArray());
        // dump($toolIds->toArray());
        // dump($resultTools->toArray());

        return $resultTools;
    }
}
