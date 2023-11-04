<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Enums\SearchAbleTable;
use App\Models\Tool;
use App\Services\MeilisearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('query');

        if (!empty($query)) {
            $startTime = microtime(true);

            $response = MeilisearchService::vectorSearch(SearchAbleTable::TOOL, $query);

            $toolIds = collect($response['hits'])->map(function ($tool) {
                return $tool['id'];
            });

            // dump($response['hits']);

            $resultTools = Tool::with(['categories'])
                ->whereIn('id', $toolIds->toArray())
                ->get()
                ->sortBy(function ($tool) use ($toolIds) {
                    return array_search($tool->id, $toolIds->toArray());
                });

            // dump('Total time: ' . round(microtime('true') - $startTime, 2) . ' sec');
            // dd($resultTools->toArray());
        }

        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: 'search',
                description: null,
                conicalUrl: route('search')
            ),
            'query' => $query,
            'resultTools' => $resultTools ?? collect([]),
        ]);
    }
}
