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
                conicalUrl: route('search.popular.index')
            ),
            'allTopSearches' => TopSearch::whereIn('id', TopSearchService::qualifiedForIndexingTopSearchIds())->get(),
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $topSearch = TopSearch::where('slug', $slug)->firstOrFail();

        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: $topSearch->query . ' - AI tools',
                description: null,
                conicalUrl: route('search.popular.show', ['top_search' => $topSearch->slug])
            ),
            'topSearch' => $topSearch,
            'pageType' => 'popularSearches'
        ]);
    }
}
