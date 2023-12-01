<?php

namespace App\Console\Commands\Meilisearch;

use App\Enums\SearchAbleTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ReIndexTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:reindex-documents-of-table {table-name}';

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

        Artisan::call('meilisearch:deindex-documents-of-table ' . $searchAbleTable->value);
        $this->info("De-Indexing table {$searchAbleTable->value}");

        Artisan::call('meilisearch:index-all-documents-of-table ' . $searchAbleTable->value);
        $this->info("Documents send for indexing for table {$searchAbleTable->value}");
    }
}
