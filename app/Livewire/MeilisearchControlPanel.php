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
        $meilisearchService = new MeilisearchService();

        $this->isServiceOnline = $meilisearchService->isServiceOnline();

        $this->overallStats = $meilisearchService->stats();

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
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['tableName'] = $table->value;
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['batch_size_for_indexing'] = $table->getBatchSize();
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['model'] = $table->getModelInstance();
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['fulltext_searchable_columns'] = $table->searchAbleColumns();

            $serverSettings = json_decode(json_encode($meilisearchService->meilisearchClient->index($table->getIndexName())->getSettings()), true);

            $localSettings = collect($table->meilisearchIndexSettings())->toArray();

            $settingsDifference = recursiveArrayDiff($serverSettings, $localSettings);

            // dd(array_diff($currentSettings, $settingsInSearchableTable));

            // dd($currentSettings, $settingsInSearchableTable);

            // dd($settingsDifference);

            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['settingsDifference'] = $settingsDifference;
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['serverSettings'] = $serverSettings;
            $this->currentWebsiteAllIndexesData[$table->getIndexName()]['localSettings'] = $localSettings;
            // $this->currentWebsiteAllIndexesData[$table->getIndexName()]['settingsDifference'] = array_diff_assoc_recursive($currentSettings, $settingsInSearchableTable);
        }

        // dd($this->currentWebsiteAllIndexesData);
    }

    public function syncSettings($tableName)
    {
        $table = SearchAbleTable::from($tableName);

        $localSettings = $table->meilisearchIndexSettings();

        // dd($localSettings);

        $response = (new MeilisearchService())
            ->meilisearchClient
            ->index($table->getIndexName())
            ->updateSettings($localSettings);

        dd($response);
    }

    public function render()
    {
        return view('livewire.meilisearch-control-panel');
    }
}
