<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Interfaces\MeilisearchAble;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use MeiliSearch\Client;
use OpenAI\Laravel\Facades\OpenAI;

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
    public static function indexAllDocumentsOfTable(SearchAbleTable $table)
    {
        $output = [];

        $searchableModel = $table->getModelInstance();

        if (!($searchableModel instanceof MeilisearchAble)) {
            throw new Exception(get_class($searchableModel) . ' does not implements MeilisearchAble interface');
        }

        $totalBatches = $searchableModel::documentsForSearchTotalBatches();

        // send documents batch by batch
        for ($batchNo = 0; $batchNo < $totalBatches; $batchNo++) {

            $response = (new self())
                ->meilisearchClient
                ->index($table->getIndexName())
                ->addDocuments($searchableModel::documentsForSearch(batchNo: $batchNo));

            $output[] = $response;

            if ($response['status'] !== 'enqueued') {
                throw new Exception('Meilisearch not able to queue documents for ' . $table->getIndexName());
            }
        }

        return $output;
    }

    public static function deIndexTable(SearchAbleTable $table)
    {
        return (new self())
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

    /**
     * This will also update the document if document is already available
     *
     * @param SearchAbleTable $table
     * @param integer $documentId
     * @return boolean
     */
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

    // todo use OpenAi facade far this when available
    public static function vectorSearch(
        SearchAbleTable $table,
        ?string $query = null,
        array $vectors = [],
        array $configs = []
    ): array {

        if (empty($query) && empty($vectors)) {
            throw new InvalidArgumentException('query or vectors required for performing a search');
        }

        if (!empty($vectors)) {
            $configs['vector'] = $vectors;
        } else {
            $configs['vector'] = MeilisearchService::getVectorEmbeddings($query, config('custom.current_embedding_model'));
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('custom.meilisearch.key'),
        ])->post(
            config('custom.meilisearch.host') . '/indexes/' . $table->getIndexName() . '/search',
            $configs
        );

        $responseData = $response->json();

        return $responseData;
    }

    public static function getVectorEmbeddings(string $text, ModelType $modelType)
    {
        if ($modelType == ModelType::All_MINI_LM_L6_V2) {
            $data = json_encode([
                'model' => $modelType->value,
                'text' => $text,
            ]);

            $response = Http::withBody($data, 'application/json')
                ->post('http://194.163.34.183/Microservices/service/embeddings/GenerateEmbeddings.php');

            if ($response->successful()) {
                return $response->json()['data']['embeddings'];
            }
        }

        if ($modelType == ModelType::OPEN_AI_ADA_002) {

            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-ada-002',
                'input' => $text,
            ]);

            return $response->embeddings[0]->embedding;
        }

        return null;
    }

    public static function enableVectorSearch()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('custom.meilisearch.key'),
        ])->patch(config('custom.meilisearch.host') . '/experimental-features', [
            'vectorStore' => true,
            // 'scoreDetails' => true,
        ]);

        return $response->json();
    }
}
