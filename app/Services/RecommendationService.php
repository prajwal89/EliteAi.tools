<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\SearchAbleTable;
use App\Models\SemanticScore;
use App\Models\Tool;
use App\ValueObjects\SemanticScore as ValueObjectsSemanticScore;
use Exception;
use Illuminate\Support\Collection;

class RecommendationService
{


    /**
     * hint for scrutiny: count of all possible combinations of pairs of numbers from e.g, 1,39
     * and do not count pair having same digits
     */
    public static function baseOnSemanticScores(
        Tool $tool,
        float $score = 0.5,
        int $maxTools = 500
    ): Collection {

        $semanticScore = new ValueObjectsSemanticScore($score);

        $scores = SemanticScore::where(function ($query) use ($tool) {
            $query->orWhere('tool1_id', $tool->id);
            $query->orWhere('tool2_id', $tool->id);
        })
            ->where('score', '>', $semanticScore->getScore())
            ->orderBy('score', 'desc')
            ->limit($maxTools)
            ->get();

        $toolIds = $scores->map(function ($score) {
            return [$score->tool1_id, $score->tool2_id];
        })
            ->flatten()
            ->filter(function ($tooId) use ($tool) {
                //* remove current tool that we are getting alternatives for
                return $tooId !== $tool->id;
            })
            ->unique();

        // sort by order of toolids in toolIds
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
