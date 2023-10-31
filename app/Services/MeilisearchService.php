<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\SearchAbleTable;
use App\Interfaces\MeilisearchAble;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Http;
use MeiliSearch\Client;

class MeilisearchService
{
    public $meilisearchClient;

    public array $defaultHttpClientConfigs = [
        'timeout' => 10,
    ];

    public function __construct(array $httpClientConfigs = [])
    {
        $this->meilisearchClient = new Client(
            config('custom.meilisearch.host'),
            config('custom.meilisearch.key'),
            new GuzzleHttpClient(array_merge($this->defaultHttpClientConfigs, $httpClientConfigs)),
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
        return $this
            ->meilisearchClient
            ->deleteIndex(
                $table->getIndexName()
            );
    }

    public static function deleteDocument(SearchAbleTable $table, int $documentId): bool
    {
        $response = (new self())
            ->meilisearchClient
            ->index($table->getIndexName())
            ->deleteDocument($documentId);

        if ($response['status'] === 'enqueued') {
            return true;
        } else {
            throw new Exception('Meilisearch failed to delete document for ' . $table->getIndexName());
        }
    }

    public static function indexDocument(SearchAbleTable $table, int $documentId): bool
    {
        $response = (new self())
            ->meilisearchClient
            ->index($table->getIndexName())
            ->addDocuments($table->getModelInstance()::documentsForSearch(
                documentId: $documentId
            ));

        if ($response['status'] !== 'enqueued') {
            throw new Exception('Meilisearch not able to queue document for ' . $table->getIndexName());
        }

        return true;
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

    public function updateDocument(SearchAbleTable $table, $documentId, array $newData): bool
    {
        $searchableModel = $table->getModelInstance();

        if (!($searchableModel instanceof MeilisearchAble)) {
            throw new Exception(get_class($searchableModel) . ' does not implement MeilisearchAble interface');
        }

        // Check if the document exists in the index
        $document = $this->meilisearchClient
            ->index($table->getIndexName())
            ->getDocument($documentId);

        if ($document) {
            // Update the document with new data
            $response = $this->meilisearchClient
                ->index($table->getIndexName())
                ->updateDocuments(['id' => $documentId] + $newData);
            // dd($response);
            if ($response['status'] !== 'enqueued') {
                throw new Exception('Meilisearch not able to update document in ' . $table->getIndexName());
            }
        } else {
            // If the document does not exist, you can choose to handle it accordingly (e.g., insert it).
            // You may also throw an exception if you require the document to exist beforehand.
            // Example:
            // $this->indexDocument($table, $newData);
        }

        return true;
    }

    public static function vectorSearch(SearchAbleTable $table, string $query, array $configs = [])
    {
        // Define the full URL for the search endpoint
        $searchEndpoint = config('custom.meilisearch.host') . '/indexes/' . $table->getIndexName() . '/search';

        // Send the POST request using the HTTP facade
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            // 'X-Meili-API-Key' => config('custom.meilisearch.key'),
            'Authorization' => 'Bearer ' . config('custom.meilisearch.key'),
        ])->post($searchEndpoint, [
            'vector' => self::getVectorEmbeddings($query)
        ] + $configs);

        // Get the JSON response content
        $responseData = $response->json();

        // Print or use the response data as needed
        return $responseData;
    }

    public static function getVectorEmbeddings(string $text)
    {
        // if (app()->isLocal()) {
        //     exec('python E:\PHP\Microservices\service\embeddings\generate_embeddings.py "' . $text . '"', $output, $result);
        //     $response = json_decode($output[0]);
        //     // try {
        //     //     $response = json_decode($output[0]);
        //     // } catch (Exception $e) {
        //     // }
        //     return $response->embeddings;
        // }

        $response = file_get_contents('http://194.163.34.183/Microservices/service/embeddings/GenerateEmbeddings.php?text=' . urlencode($text));

        // dd($response);
        return json_decode($response, true)['data']['embeddings'];
    }

    public static function enableVectorSearch()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            // 'X-Meili-API-Key' => config('custom.meilisearch.key'),
            'Authorization' => 'Bearer ' . config('custom.meilisearch.key'),
        ])->patch(config('custom.meilisearch.host') . '/experimental-features/', [
            'vectorStore' => true,
        ]);

        dd($response);
    }
}
