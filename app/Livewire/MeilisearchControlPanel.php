<?php

namespace App\Livewire;

use App\Services\MeilisearchService;
use Livewire\Component;

class MeilisearchControlPanel extends Component
{
    public bool $isServiceOnline = false;

    public function checkStatus()
    {
        $this->isServiceOnline =  (new MeilisearchService)->isServiceOnline();
    }

    public function render()
    {
        return view('livewire.meilisearch-control-panel');
    }
}
