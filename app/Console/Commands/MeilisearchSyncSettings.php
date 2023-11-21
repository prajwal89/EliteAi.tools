<?php

namespace App\Console\Commands;

use App\Enums\SearchAbleTable;
use App\Services\MeilisearchService;
use Illuminate\Console\Command;

class MeilisearchSyncSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:sync-settings {table-name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync local settings with meilisearch service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('table-name');

        $searchAbleTable = SearchAbleTable::from(trim($tableName));

        MeilisearchService::syncLocalSettings($searchAbleTable);

        $this->info("Settings will synced for {$searchAbleTable->value}");
    }
}
