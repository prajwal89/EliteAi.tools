<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\SearchAbleTable;
use App\Interfaces\MeilisearchAble;
use Exception;
use MeiliSearch\Client;

class MeilisearchService
{
    public $meilisearchClient;

    public function __construct()
    {
        $this->meilisearchClient = new Client(
            config('custom.meilisearch.host'),
            config('custom.meilisearch.key')
        );
    }

    // index all documents
    public function indexAllDocumentsOfTable(SearchAbleTable $table)
    {
        $output = [];

        $searchableModel = $table->getModelInstance();

        if (!($searchableModel instanceof MeilisearchAble)) {
            throw new Exception(get_class($searchableModel) . ' does not implements MeilisearchAble interface');
        }

        $totalBatches = $searchableModel::documentsForSearchTotalBatches();

        // send documents batch by batch
        for ($batchNo = 0; $batchNo < $totalBatches; $batchNo++) {

            $response = $this->meilisearchClient
                ->index($table->getIndexName())
                ->addDocuments($searchableModel::documentsForSearch(batchNo: $batchNo));

            $output[] = $response;

            if ($response['status'] !== 'enqueued') {
                throw new Exception('Meilisearch not able to queue documents for ' . $table->getIndexName());
            }
        }

        return $output;
    }

    public function deIndexTable(SearchAbleTable $table)
    {
        return $this->meilisearchClient
            ->deleteIndex($table->getIndexName());
    }

    public function getTotalDocumentsInIndex(SearchAbleTable $table)
    {
        return $this->meilisearchClient->index($table->getIndexName())
            ->stats()['numberOfDocuments'];
    }

    public function isServiceOnline(): bool
    {
        if (($this->meilisearchClient->health())['status'] == 'available') {
            return true;
        }

        return false;
    }
}
