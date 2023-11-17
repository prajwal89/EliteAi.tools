<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use App\Models\TopSearch;
use App\Models\TopSearchToolSemanticScore;
use App\Services\TopSearchService;
use Illuminate\Http\Request;

class TopSearchController extends Controller
{
    public function index()
    {
        return view('top-searches.index', [
            'pageDataDTO' => new PageDataDTO(
                title: 'Popular searches',
                description: null,
                conicalUrl: route('popular.index')
            ),
            'allTopSearches' => TopSearch::whereIn('id', TopSearchService::qualifiedForIndexingTopSearchIds())->get(),
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
