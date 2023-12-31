<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Tool;
use App\Services\RecommendationService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ToolAlternativesController extends Controller
{
    public function show(string $slug): View
    {
        $tool = Tool::where('slug', $slug)->firstOrFail();

        // todo improve this
        $alternativeTools = RecommendationService::baseOnSemanticScores(
            tool: $tool,
            score: 0.85,
            maxTools: 40
        );

        return view('tools.alternatives.show', [
            'tool' => $tool,
            'alternativeTools' => $alternativeTools,
            'pageDataDTO' => new PageDataDTO(
                // title: ($alternativeTools->count() - 1) . '+ ' . $tool->name . ' - Alternatives',
                title: $tool->name . ' - Alternatives and Competitors',
                description: 'All Tools alternatives for ' . $tool->name,
                conicalUrl: route('tool.alternatives.show', ['tool' => $tool->slug]),
                thumbnailUrl: asset('/tools/' . $tool->slug . '/screenshot.webp')
            ),
        ]);
    }

    public function index()
    {
        // *improve this  as we are comparing tool1_id to tool2_id not vice verse
        $qualifiedAlternativeToolPages = DB::table('semantic_scores')
            ->select(['*', 'tool1.id as tool_id', DB::raw('count(*) as total_tools')])
            ->join('tools as tool1', 'tool1.id', '=', 'semantic_scores.tool1_id')
            ->join('tools as tool2', 'tool2.id', '=', 'semantic_scores.tool2_id')
            ->where('semantic_scores.score', '>', config('custom.alternative_tools.minimum_semantic_score'))
            ->having('total_tools', '>=', config('custom.alternative_tools.minimum_tools_required'))
            ->groupBy('tool1.id')
            ->get()
            ->pluck('tool_id');

        $allTools = Tool::whereIn('id', $qualifiedAlternativeToolPages)->get();

        // dd($allTools);

        return view('tools.alternatives.index', [
            'allTools' => $allTools,
            'pageDataDTO' => new PageDataDTO(
                title: 'Tool - Alternatives',
                description: null,
                conicalUrl: route('tool.alternatives.index')
            ),
        ]);
    }
}
