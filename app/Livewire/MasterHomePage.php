<?php

namespace App\Livewire;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Collection;
use Livewire\Component;

class MasterHomePage extends Component
{
    public string  $pageType;

    public Collection $allCategories;

    public Collection $recentTools;

    public $category;

    // public PageDataDTO $pageDataDTO;

    public function mount()
    {
        match ($this->pageType) {
            'home' => $this->loadHomePage(),
            'category' => $this->loadCategoryPage(),
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

    public function render()
    {
        return view('livewire.master-home-page');
    }
}
