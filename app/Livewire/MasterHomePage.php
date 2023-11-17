<?php

namespace App\Livewire;

use App\DTOs\PageDataDTO;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app-full-width-livewire')]
class MasterHomePage extends Component
{
    public array $pageDataDTO;

    public function mount()
    {
        $this->pageDataDTO = (new PageDataDTO(
            title: 'sdfsd',
            description: 'sdf',
            conicalUrl: route('home')
        ))->toArray();
    }

    #[Title('home')]
    public function render()
    {
        return view('livewire.master-home-page');
    }
}
