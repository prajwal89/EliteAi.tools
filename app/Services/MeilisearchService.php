<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Interfaces\MeilisearchAble;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    /**
     * Index all documents for ann table
     */
    public static function indexAllDocumentsOfTable(SearchAbleTable $table): array
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

    /**
     * De-Index Whole table
     */
    public static function deIndexTable(SearchAbleTable $table): array
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
     */
    public static function indexDocument(SearchAbleTable $table, int $documentId): bool
    {
        $document = $table->getModelInstance()::documentsForSearch(
            documentId: $documentId
        );

        if (empty($document)) {
            Log::info('Cannot get documentsForSearch for id ' . $documentId . ' in ' . $table->getIndexName());
        }

        $response = (new self())
            ->meilisearchClient
            ->index($table->getIndexName())
            ->addDocuments($document);

        if ($response['status'] !== 'enqueued') {
            throw new Exception('Meilisearch not able to queue document ' . $documentId . ' for ' . $table->getIndexName());
        }

        return true;
    }

    public function getTotalDocumentsInIndex(SearchAbleTable $table)
    {
        return $this
            ->meilisearchClient
            ->index($table->getIndexName())
            ->stats()['numberOfDocuments'];
    }

    public function isServiceOnline(): bool
    {
        if (($this->meilisearchClient->health())['status'] == 'available') {
            return true;
        }

        return false;
    }

    public function stats(): array
    {
        return $this->meilisearchClient->stats();
    }

    public function updateDocument(
        SearchAbleTable $table,
        int $documentId,
        array $newData
    ): bool {
        $searchableModel = $table->getModelInstance();

        if (!($searchableModel instanceof MeilisearchAble)) {
            throw new Exception(get_class($searchableModel) . ' does not implement MeilisearchAble interface');
        }

        // Check if the document exists in the index
        $document = $this->meilisearchClient
            ->index($table->getIndexName())
            ->getDocument($documentId);

        if ($document) {
            $response = $this->meilisearchClient
                ->index($table->getIndexName())
                ->updateDocuments(['id' => $documentId] + $newData);

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

    /**
     * Default wrapper for meilisearch search
     *
     * @return void
     */
    // ! return types are different
    public function search(
        SearchAbleTable $table,
        string $query,
        array $searchParams = [],
        array $options = []
    ) {

        try {
            $results = $this
                ->meilisearchClient
                ->index($table->getIndexName())
                ->search($query, $searchParams, $options);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if (app()->isLocal()) {
                throw $e;
            }

            // fallback to fulltext search
            try {
                $results = self::fulltextSearch($table, $query);
            } catch (Exception $es) {

                Log::error($es->getMessage());

                if (app()->isLocal()) {
                    throw $e;
                }
            }
        }

        return $results;
    }

    /**
     * Use only if you want to search with vectors
     */
    public static function vectorSearch(
        SearchAbleTable $table,
        string $query = null,
        array $vectors = [],
        array $configs = []
    ): array {

        $configs = array_merge($configs, [
            // these settings like this because we using this to saving semantic distances one to all
            // 'limit' => 1000,
            // 'hitsPerPage' => 1000
        ]);

        if (empty($query) && empty($vectors)) {
            throw new InvalidArgumentException('query or vectors required for performing a search');
        }

        if (count($vectors) > 1) {
            $configs['vector'] = $vectors;
        } else {
            if (empty($query)) {
                throw new InvalidArgumentException('Query is not available search');
            }
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
        // if ($modelType == ModelType::All_MINI_LM_L6_V2) {
        //     $data = json_encode([
        //         'model' => $modelType->value,
        //         'text' => $text,
        //     ]);

        //     $response = Http::withBody($data, 'application/json')
        //         ->post('http://194.163.34.183/Microservices/service/embeddings/GenerateEmbeddings.php');

        //     if ($response->successful()) {
        //         return $response->json()['data']['embeddings'];
        //     }
        // }

        if ($modelType == ModelType::OPEN_AI_ADA_002) {

            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-ada-002',
                'input' => $text,
            ]);

            return $response->embeddings[0]->embedding;
        }

        return null;
    }

    public static function fulltextSearch(SearchAbleTable $table, string $query): Collection
    {
        return $table->getModelInstance()::query()
            ->whereFullText($table->searchAbleColumns(), $query)
            ->get();
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

    public static function defaultIndexSettings(): array
    {
        return [
            'displayedAttributes' => ['*'],
            'searchableAttributes' => ['*'],
            'filterableAttributes' => [],
            'sortableAttributes' => [],
            'rankingRules' => [
                'words',
                'typo',
                'proximity',
                'attribute',
                'sort',
                'exactness',
            ],
            'stopWords' => [
                // common
                'a', 'an', 'and', 'are', 'as', 'at', 'be', 'but', 'by', 'for',
                'if', 'in', 'into', 'is', 'it', 'no', 'not', 'of', 'on',
                'or', 'such', 'that', 'the', 'their', 'then', 'there', 'these',
                'they', 'this', 'to', 'was', 'will', 'with',
            ],
            "nonSeparatorTokens" => [],
            "separatorTokens" => [],
            "dictionary" => [],
            // 'synonyms' => [],
            'distinctAttribute' => null,
            'typoTolerance' => [
                'enabled' => true,
                'minWordSizeForTypos' => [
                    'oneTypo' => 5,
                    'twoTypos' => 9,
                ],
                'disableOnWords' => [],
                'disableOnAttributes' => [],
            ],
            'faceting' => [
                'maxValuesPerFacet' => 100,
                "sortFacetValuesBy" =>  [
                    "*" => "alpha"
                ]
            ],
            'pagination' => [
                'maxTotalHits' => 1000,
            ],
        ];
    }
}
