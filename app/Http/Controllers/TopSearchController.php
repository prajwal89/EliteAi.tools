<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use App\Models\TopSearch;
use App\Models\TopSearchToolSemanticScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopSearchController extends Controller
{
    public function index()
    {
        // Blogs what matches criteria for indexing on google
        $qualifiedTopSearches = DB::table('top_search_tool_semantic_scores')
            ->select(['*', 'top_searches.id as top_search_id', DB::raw('count(*) as total_tools')])
            ->join('tools', 'tools.id', '=', 'top_search_tool_semantic_scores.tool_id')
            ->join('top_searches', 'top_searches.id', '=', 'top_search_tool_semantic_scores.top_search_id')
            ->where('top_search_tool_semantic_scores.score', '>', 0.85)
            ->having('total_tools', '>', 3)
            ->groupBy('top_searches.id')
            ->get()
            ->pluck('top_search_id');

        $allTopSearches = TopSearch::whereIn('id', $qualifiedTopSearches)->get();

        return view('top-searches.index', [
            'pageDataDTO' => new PageDataDTO(
                title: 'Popular searches',
                description: null,
                conicalUrl: route('popular.index')
            ),
            'allTopSearches' => $allTopSearches,
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $topSearch = TopSearch::where('slug', $slug)->firstOrFail();

        $searchRelatedTools = TopSearchToolSemanticScore::with(['tool.categories'])
            ->where('top_search_id', $topSearch->id)
            ->where('score', '>', 0.85)
            ->orderBy('score', 'desc')
            ->limit(24)
            ->get()
            ->map(function ($semanticScore) {
                $semanticScore->tool->score = $semanticScore->score;

                return $semanticScore->tool;
            });

        // dd($searchRelatedTools);

        // $categories = Category::has('tools')->get();
        $categories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc') // Order by tool count in ascending order
            ->take(12)
            ->get();

        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: $topSearch->query . ' - AI tools',
                description: null,
                conicalUrl: route('popular.show', ['top_search' => $topSearch->slug])
            ),
            'searchRelatedTools' => $searchRelatedTools,
            'categories' => $categories,
            'topSearch' => $topSearch,
        ]);
    }
}
