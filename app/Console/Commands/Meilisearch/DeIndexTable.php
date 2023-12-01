<?php

namespace App\Console\Commands\Meilisearch;

use App\Enums\SearchAbleTable;
use App\Services\MeilisearchService;
use Illuminate\Console\Command;

class DeIndexTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:deindex-documents-of-table {table-name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('table-name');

        $searchAbleTable = SearchAbleTable::from(trim($tableName));

        (new MeilisearchService())->deIndexTable($searchAbleTable);

        $this->info("De-Indexing table {$searchAbleTable->value}");
    }
}
