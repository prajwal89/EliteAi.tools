<?php

namespace App\Livewire;

use App\Enums\SearchAbleTable;
use App\Models\Category;
use App\Models\Tool;
use App\Models\TopSearchToolSemanticScore;
use App\Services\MeilisearchService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

// todo add comments as this component will get lot bigger
class MasterHomePage extends Component
{
    public string $pageType;

    public $pageDataDTO;

    public string $alertMessage = '';

    public Collection $allCategories;

    public Collection $recentTools;

    public Collection $searchResults;

    public Collection $popularSearchesTools;

    public $category; // from livewire component tag

    public $topSearch; // from livewire component tag

    // search section
    public array $filters = [
        'pricingType' => '*',
    ];

    public int $totalFiltersApplied = 0;

    // https://livewire.laravel.com/docs/url
    // #[Url(as: 'q')]
    public ?string $searchQuery = '';

    public function mount()
    {
        $this->searchResults = collect([
            'tools' => collect(),
        ]);

        if (!empty($this->searchQuery)) {
            $this->search();
        }

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

    // search section
    public function applyFilters()
    {
        $this->totalFiltersApplied = 0;

        $this->filters['pricingType'] != '*' ? $this->totalFiltersApplied++ : null;

        $this->search();
    }

    public function search()
    {
        // temporary switch page type
        $this->pageType = 'search';

        if (empty($this->searchQuery)) {
            $this->alertMessage = 'Search query is empty';

            return;
        }
        if (strlen($this->searchQuery) > 1000) {
            $this->alertMessage = 'Search query should be less than 1000 characters';

            return;
        }

        // $meilisearchFilters['sort'] = ['monthly_subscription_starts_from:desc'];

        // if ($this->filters['pricingType'] != '*') {
        //     $meilisearchFilters['filter'] = ['pricing_type = "' . $this->filters['pricingType'] . '"'];
        // }

        // dd($meilisearchFilters);

        $response = MeilisearchService::vectorSearch(
            SearchAbleTable::TOOL,
            trim($this->searchQuery),
            // $meilisearchFilters
        );

        $response = $response ?? (new MeilisearchService())->search(
            SearchAbleTable::TOOL,
            trim($this->searchQuery),
            // $meilisearchFilters
        );

        // $response = MeilisearchService::fulltextSearch(SearchAbleTable::TOOL, trim($this->searchQuery), [
        //     'filters' => ['pricing_type = Freemium'],
        // ]);

        // dd($response);

        if (empty($response)) {
            $this->alertMessage = 'Something went wrong please try again later';

            return;
        }

        $toolIds = $response->hits->pluck('id');

        $resultTools = Tool::with(['categories'])
            ->whereIn('id', $toolIds->toArray())
            ->get();

        if ($response->strategyUsed == 'vector_meilisearch') {

            $resultTools = $resultTools
                ->sortBy(function ($tool) use ($toolIds) {
                    return array_search($tool->id, $toolIds->toArray());
                })

                ->map(function ($tool) use ($response) {
                    // assign semantic score
                    $tool->_semanticScore = collect($response->hits)
                        ->where('id', $tool->id)
                        ->first()['_semanticScore'];

                    return $tool;
                })

                ->filter(function ($tool) {
                    return $tool->_semanticScore > 0.6;
                })
                ->sortByDesc('_semanticScore');
        }

        // dd($resultTools);

        $this->searchResults['tools'] = $resultTools;
    }

    public function updatedPricingType($value)
    {
    }
}
