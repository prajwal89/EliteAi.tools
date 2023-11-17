<?php

namespace App\Livewire;

use App\DTOs\PageDataDTO;
use App\Enums\SearchAbleTable;
use App\Models\Category;
use App\Models\Tool;
use App\Models\TopSearchToolSemanticScore;
use App\Services\MeilisearchService;
use Illuminate\Support\Collection;
use Livewire\Component;

// todo add comments as this component will get lot bigger
class MasterHomePage extends Component
{
    public string $pageType;

    public string $alertMessage = 'alert';

    public Collection $allCategories;

    public Collection $recentTools;

    public ?string $searchQuery = '';

    public Collection $searchResults;

    public Collection $popularSearchesTools;

    public $category; // from livewire component tag 

    public $topSearch; // from livewire component tag

    public function mount()
    {
        $this->searchResults = collect([
            'tools' => collect()
        ]);

        match ($this->pageType) {
            'home' => $this->loadHomePage(),
            'category' => $this->loadCategoryPage(),
            'search' => $this->loadSearchPage(),
            'popularSearches' => $this->loadPopularSearches(),
        };

        // * required on most of the pages
        $this->allCategories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc')
            ->take(12)
            ->get();
    }

    public function loadHomePage()
    {
        $this->recentTools = Tool::with(['categories'])->limit(12)->latest()->get();
    }

    public function loadCategoryPage()
    {
    }

    public function loadSearchPage()
    {
        // search if query is available in url
        if (!empty($this->searchQuery)) {
            $this->search();
        }
    }

    public function search()
    {
        // temporary switch page type 
        $this->pageType = 'search';

        if (empty($this->searchQuery)) {
            $this->alertMessage = "Search query is empty";
            return;
        }

        $response = MeilisearchService::vectorSearch(SearchAbleTable::TOOL, trim($this->searchQuery));

        $toolIds = collect($response['hits'])->map(function ($tool) {
            return $tool['id'];
        });

        $resultTools = Tool::with(['categories'])
            ->whereIn('id', $toolIds->toArray())
            ->get()
            ->sortBy(function ($tool) use ($toolIds) {
                return array_search($tool->id, $toolIds->toArray());
            })
            ->map(function ($tool) use ($response) {
                // assign semantic score
                $tool->_semanticScore = collect($response['hits'])
                    ->where('id', $tool->id)
                    ->first()['_semanticScore'];
                return $tool;
            })
            ->filter(function ($tool) {
                return $tool->_semanticScore > 0.6;
            });

        $this->searchResults['tools'] = $resultTools;
    }

    public function loadPopularSearches()
    {
        $this->popularSearchesTools = TopSearchToolSemanticScore::with(['tool.categories'])
            ->where('top_search_id', $this->topSearch->id)
            ->where('score', '>', 0.85)
            ->orderBy('score', 'desc')
            ->limit(24)
            ->get()
            ->map(function ($semanticScore) {
                $semanticScore->tool->score = $semanticScore->score;

                return $semanticScore->tool;
            });
    }
}
