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

    public array $currentWebsiteAllIndexesData;

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

        // calculate all details required for indexes cards
        foreach ($this->overallStats['indexes'] as $indexName => $indexData) {
            if (in_array($indexName, $this->currentWebsiteAllIndexes)) {
                $this->currentWebsiteAllIndexesData[$indexName] = $indexData;
            }
        }

        foreach (SearchAbleTable::cases() as $table) {
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['batch_size_for_indexing'] = $table->getBatchSize();
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['model'] = $table->getModelInstance();
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['fulltext_searchable_columns'] = $table->searchAbleColumns();
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['fulltext_searchable_columns'] = $table->meilisearchIndexSettings();
        }



        // dd($this->currentWebsiteAllIndexesData);
    }



    public function render()
    {
        return view('livewire.meilisearch-control-panel');
    }
}
