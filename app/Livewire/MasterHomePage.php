<?php

namespace App\Livewire;

use App\DTOs\PageDataDTO;
use App\Enums\SearchAbleTable;
use App\Models\Category;
use App\Models\Tool;
use App\Services\MeilisearchService;
use Illuminate\Support\Collection;
use Livewire\Component;

// todo add comments as this component will get lot bigger
class MasterHomePage extends Component
{
    public string  $pageType;

    public Collection $allCategories;

    public Collection $recentTools;

    public $category;

    public string  $searchQuery = '';

    public Collection  $searchResults;
    // public PageDataDTO $pageDataDTO;

    public function mount()
    {
        $this->searchResults = collect([
            'tools' => collect()
        ]);

        match ($this->pageType) {
            'home' => $this->loadHomePage(),
            'category' => $this->loadCategoryPage(),
            'search' => $this->loadSearchPage(),
        };
    }

    public function loadHomePage()
    {
        $this->recentTools = Tool::with(['categories'])->limit(12)->latest()->get();

        $this->allCategories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc')
            ->take(12)
            ->get();
    }

    public function loadCategoryPage()
    {
        $this->allCategories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc')
            ->take(12)
            ->get();
    }

    public function loadSearchPage()
    {
    }

    public function search()
    {
        $response = MeilisearchService::vectorSearch(SearchAbleTable::TOOL, $this->searchQuery);

        $toolIds = collect($response['hits'])->map(function ($tool) {
            return $tool['id'];
        });

        $resultTools = Tool::with(['categories'])
            ->whereIn('id', $toolIds->toArray())
            ->get()
            ->sortBy(function ($tool) use ($toolIds) {
                return array_search($tool->id, $toolIds->toArray());
            });

        $this->searchResults['tools'] = $resultTools;
    }

    public function render()
    {
        return view('livewire.master-home-page');
    }
}
