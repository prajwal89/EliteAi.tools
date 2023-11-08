<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use App\Models\TopSearch;
use App\Models\TopSearchToolSemanticScore;
use Illuminate\Http\Request;

class TopSearchController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $topSearch = TopSearch::where('slug', $slug)->firstOrFail();

        // ! N+1 as we are accessing categories on tool model in views
        $searchRelatedTools = TopSearchToolSemanticScore::with(['tool'])
            ->where('top_search_id', $topSearch->id)
            ->where('score', '>', 0.85)
            ->orderBy('score', 'desc')
            ->limit(24)
            ->get()
            ->map(function ($semanticScore) {
                $semanticScore->tool->score = $semanticScore->score;
                return $semanticScore->tool;
            });

        // $categories = Category::has('tools')->get();
        $categories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc') // Order by tool count in ascending order
            ->take(12)
            ->get();


        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: 'Home',
                description: null,
                conicalUrl: route('home')
            ),
            'searchRelatedTools' => $searchRelatedTools,
            'categories' => $categories,
        ]);
    }
}
