<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use App\Models\Tool;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ToolController extends Controller
{
    public function index()
    {
    }

    public function show(Request $request, string $slug): View
    {
        $tool = Tool::with([
            'categories',
            'tags' => function ($query) {
                $query->withCount('tools');
            }
        ])->where('slug', $slug)->firstOrFail();

        $relatedTools = RecommendationService::baseOnSemanticScores(
            tool: $tool,
            score: 0.4,
            maxTools: 3 * 2
        );

        // dd($alternativeTools);

        $categories = Category::has('tools')->take(9)->get();

        return view('tools.show', [
            'tool' => $tool,
            'relatedTools' => $relatedTools,
            'categories' => $categories,
            'pageDataDTO' => new PageDataDTO(
                // title: $tool->name . ' - Pricing, Use cases, Reviews, Features',
                // title: $tool->name . ' - Use cases, Features',
                title: $tool->name . ' - ' . $tool->tag_line,
                description: $tool->summary,
                conicalUrl: route('tool.show', ['tool' => $tool->slug]),
                thumbnailUrl: asset('/tools/' . $tool->slug . '/screenshot.webp')
            ),
        ]);
    }

    public function submitNewTool()
    {
        return view('tools.submit', [
            'pageDataDTO' => new PageDataDTO(
                title: 'Submit your AI tool',
                description: null,
                conicalUrl: route('tool.submit'),
                thumbnailUrl: null
            ),
        ]);
    }
}
