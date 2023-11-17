<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Enums\BlogType;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tool;
use App\Models\TopSearch;
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
            },
        ])->where('slug', $slug)->firstOrFail();

        $relatedTools = RecommendationService::baseOnSemanticScores(
            tool: $tool,
            score: 0.4,
            maxTools: 3 * 2
        );

        // http://127.0.0.1:8000/tool/visily
        // wrong top searches tool count is less than required
        // ! should i take intersection $qualifiedTopSearches

        $topSearches = TopSearch::select([
            // '*',
            'top_searches.slug as top_search_slug',
            'top_searches.query as top_search_query',
            DB::raw('count(*) as total_tools')
        ])
            ->join('top_search_tool_semantic_scores', 'top_search_tool_semantic_scores.top_search_id', '=', 'top_searches.id')
            ->join('tools', 'tools.id', '=', 'top_search_tool_semantic_scores.tool_id')
            ->where('tools.id', $tool->id)
            ->groupBy('top_searches.id')
            ->where('score', '>', 0.85)
            ->orderBy('score', 'desc')
            ->limit(24)
            ->get();

        // dd($topSearches->toArray());

        $relatedBlogs = Blog::select([
            // '*',
            'blogs.slug as blog_slug',
            'blogs.title as blog_title',
        ])
            ->join('blog_tool_semantic_scores', 'blog_tool_semantic_scores.blog_id', '=', 'blogs.id')
            ->join('tools', 'tools.id', '=', 'blog_tool_semantic_scores.tool_id')
            ->where('tools.id', $tool->id)
            ->groupBy('blogs.id')
            ->where('blog_type', BlogType::SEMANTIC_SCORE->value)
            ->where('score', '>', 0.85)
            ->orderBy('score', 'desc')
            ->limit(24)
            ->get();

        // dd($relatedBlogs);

        // dd($alternativeTools);

        // $categories = Category::has('tools')->take(9)->get();

        return view('tools.show', [
            'tool' => $tool,
            'topSearches' => $topSearches,
            'relatedBlogs' => $relatedBlogs,
            'relatedTools' => $relatedTools,
            // 'categories' => $categories,
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
