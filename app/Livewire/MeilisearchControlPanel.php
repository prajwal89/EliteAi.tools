<?php

namespace App\Livewire;

use App\Enums\SearchAbleTable;
use App\Services\MeilisearchService;
use Livewire\Component;

class MeilisearchControlPanel extends Component
{
    public bool $isServiceOnline = false;

    public array $overallStats = [];

    public array $currentWebsiteAllIndexes = [];

    public int $totalDocuments = 0;

    public int $totalDocumentsOfCurrentWebsite = 0;

    public function mount()
    {
        foreach (SearchAbleTable::cases() as $table) {
            $this->currentWebsiteAllIndexes[] = $table->getIndexName();
        }
    }

    public function init()
    {
        $this->isServiceOnline =  (new MeilisearchService())->isServiceOnline();

        $this->overallStats = (new MeilisearchService())->stats();


        foreach ($this->overallStats['indexes'] as $indexName => $indexData) {
            $this->totalDocuments += $indexData['numberOfDocuments'];
            if (in_array($indexName, $this->currentWebsiteAllIndexes)) {
                $this->totalDocumentsOfCurrentWebsite += $indexData['numberOfDocuments'];
            }
        }



        // dd($this->overallStats);
    }



    public function render()
    {
        return view('livewire.meilisearch-control-panel');
    }
}
