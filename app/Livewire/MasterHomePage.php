<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Collection;
use Livewire\Component;

class MasterHomePage extends Component
{
    public string  $pageType;

    public Collection $allCategories;

    public Collection $recentTools;

    public function mount()
    {
        if ($this->pageType == 'home') {
            $this->loadHomePage();
        }
    }

    public function loadHomePage()
    {
        $this->recentTools = Tool::with(['categories'])->limit(12)->latest()->get();
        // $categories = Category::has('tools')->get();
        $this->allCategories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc') // Order by tool count in ascending order
            ->take(12)
            ->get();
    }

    public function render()
    {
        return view('livewire.master-home-page');
    }
}
