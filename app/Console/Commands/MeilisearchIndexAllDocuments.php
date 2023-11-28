<?php

namespace App\Console\Commands;

use App\Enums\SearchAbleTable;
use App\Services\MeilisearchService;
use Illuminate\Console\Command;

class MeilisearchIndexAllDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:index-all-documents-of-table {table-name}';

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

        (new MeilisearchService)->indexAllDocumentsOfTable($searchAbleTable);

        $this->info("Documents send for indexing for table {$searchAbleTable->value}");
    }
}
