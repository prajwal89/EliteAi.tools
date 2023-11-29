<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\DTOs\SearchResultsDTO;
use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Interfaces\MeilisearchAble;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use MeiliSearch\Client;
use Meilisearch\Contracts\SearchQuery;
use OpenAI\Laravel\Facades\OpenAI;

class MeilisearchService
{
    public Client $client;

    public array $defaultHttpClientConfigs = [
        'timeout' => 10,
    ];

    public function __construct(array $httpClientConfigs = [])
    {
        $this->client = new Client(
            config('services.meilisearch.host'),
            config('services.meilisearch.key'),
            new GuzzleHttpClient(array_merge($this->defaultHttpClientConfigs, $httpClientConfigs)),
        );
    }

    /**
     * Index all documents for ann table
     */
    public function indexAllDocumentsOfTable(SearchAbleTable $table, int $currentBatchNo = null): array
    {
        $output = [];

        $searchableModel = $table->getModelInstance();

        if (!($searchableModel instanceof MeilisearchAble)) {
            throw new Exception(get_class($searchableModel) . ' does not implements MeilisearchAble interface');
        }

        $totalBatches = $searchableModel::documentsForSearchTotalBatches();

        // index single batch
        if (!empty($currentBatchNo)) {

            if ($totalBatches > $currentBatchNo) {
                throw new Exception('Current batch number is greater than total batches');
            }

            $response = (new self())
                ->client
                ->index($table->getIndexName())
                ->addDocuments($searchableModel::documentsForSearch(batchNo: $currentBatchNo));

            $output[] = $response;

            if ($response['status'] !== 'enqueued') {
                throw new Exception('Meilisearch not able to queue documents for ' . $table->getIndexName());
            }

            return $output;
        }

        // send documents batch by batch
        for ($batchNo = 0; $batchNo < $totalBatches; $batchNo++) {

            $response = (new self())
                ->client
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
    public function deIndexTable(SearchAbleTable $table): array
    {
        return $this
            ->client
            ->deleteIndex(
                $table->getIndexName()
            );
    }

    public function deleteDocument(SearchAbleTable $table, int $documentId): bool
    {
        $response = $this
            ->client
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
    public function indexDocument(SearchAbleTable $table, int $documentId): bool
    {
        $document = $table->getModelInstance()::documentsForSearch(
            documentId: $documentId
        );

        if (empty($document)) {
            Log::info('Cannot get documentsForSearch for id ' . $documentId . ' in ' . $table->getIndexName());
        }

        $response = $this
            ->client
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
            ->client
            ->index($table->getIndexName())
            ->stats()['numberOfDocuments'];
    }

    public function isServiceOnline(): bool
    {
        if (($this->client->health())['status'] == 'available') {
            return true;
        }

        return false;
    }

    public function stats(): array
    {
        return $this->client->stats();
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
        $document = retry(5, function () use ($documentId, $table) {
            // retrying this b.c of
            // Meilisearch\\Exceptions\\ApiException(code: 404): Document `258` not found
            return $this->client
                ->index($table->getIndexName())
                ->getDocument($documentId);
        }, 5000);

        if ($document) {
            $response = $this->client
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
        array $options = [],
        array $retrySettings = [
            'times' => 2,
            'sleepMilliseconds' => 1000,
        ]
    ): ?SearchResultsDTO {

        try {
            // b.c external apis are should be treated as unreliable
            $results = retry(
                times: $retrySettings['times'],
                callback: function () use ($table, $query, $searchParams, $options) {
                    return $this
                        ->client
                        ->index($table->getIndexName())
                        ->search($query, $searchParams, $options);
                },
                sleepMilliseconds: $retrySettings['sleepMilliseconds']
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if (!app()->isProduction()) {
                throw $e;
            }

            return null;
        }

        return SearchResultsDTO::fromMeilisearchResults(searchResult: $results);
    }

    /**
     * Wrapper on meilisearch muiltisearch
     *
     * @see https://www.meilisearch.com/docs/reference/api/multi_search
     */
    public function multiSearch(
        array $multiSearchSettings,
        array $retrySettings = [
            'times' => 2,
            'sleepMilliseconds' => 1000,
        ]
    ): ?array {

        $searchObjects = [];

        foreach ($multiSearchSettings as $searchData) {
            $searchObjects[] = (new SearchQuery())
                ->setIndexUid($searchData['searchableTable']->getIndexName())
                ->setQuery($searchData['query'])
                ->setLimit(20);
        }

        try {
            // b.c external apis are should be treated as unreliable
            $meilisearchResults = retry(
                times: $retrySettings['times'],
                callback: function () use ($searchObjects) {
                    return $this->client->multiSearch($searchObjects);
                },
                sleepMilliseconds: $retrySettings['sleepMilliseconds']
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if (!app()->isProduction()) {
                throw $e;
            }

            return null;
        }

        $resultsArray = [];

        foreach ($meilisearchResults['results'] as $mResult) {
            $resultsArray[] = SearchResultsDTO::fromArray(searchResult: $mResult);
        }

        return $resultsArray;
    }

    /**
     * Use only if you want to search with vectors
     */
    public static function vectorSearch(
        SearchAbleTable $table,
        string $query = null,
        array $vectors = [],
        array $configs = [],
        array $retrySettings = [
            'times' => 2,
            'sleepMilliseconds' => 2000,
        ]
    ): ?SearchResultsDTO {

        $configs = array_merge($configs, [
            // these settings like this because we using this to saving semantic distances one to all
            // 'limit' => 1000,
            // 'hitsPerPage' => 1000
        ]);

        if (empty($query) && empty($vectors)) {
            if (!app()->isProduction()) {
                throw new InvalidArgumentException('query or vectors required for performing a vector search');
            } else {
                Log::info('query or vectors required for performing a search');

                return null;
            }
        }

        if (count($vectors) > 1) {
            $configs['vector'] = $vectors;
        } else {
            if (empty($query)) {
                if (!app()->isProduction()) {
                    throw new InvalidArgumentException('Query is not given for search');
                } else {
                    Log::info('Query is not given for search');

                    return null;
                }
            }

            $configs['vector'] = (new MeilisearchService)->getVectorEmbeddings($query, config('custom.current_embedding_model'));
        }

        try {
            $responseData = retry(
                times: $retrySettings['times'],
                callback: function () use ($table, $configs) {
                    // todo replace with client->vectorSearch when available
                    return Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . config('services.meilisearch.key'),
                    ])->post(
                        config('services.meilisearch.host') . '/indexes/' . $table->getIndexName() . '/search',
                        $configs
                    )->json();
                },
                sleepMilliseconds: $retrySettings['sleepMilliseconds']
            );
        } catch (Exception $e) {
            if (!app()->isProduction()) {
                throw $e;
            }

            Log::info('Meilisearch API error');

            return null;
        }

        if (!isset($responseData['hits'])) {
            if (!app()->isProduction()) {
                throw new Exception($responseData['message']);
            }

            Log::info('Meilisearch API error: Hits key not found');

            return null;
        }

        return new SearchResultsDTO(
            hits: collect($responseData['hits']),
            totalHits: $responseData['estimatedTotalHits'],
            searchQuery: $query,
            timeTakenInMilliseconds: 1,
            strategyUsed: 'vector_meilisearch'
        );
    }

    //! this method should not be here as it violets single responsibility
    public static function fulltextSearch(
        SearchAbleTable $table,
        string $searchQuery,
        array $settings = []
    ): ?SearchResultsDTO {

        $defaultSettings = [
            'limit' => 20,
            'filters' => [],
        ];

        $settings = array_merge($defaultSettings, $settings);

        try {

            $query = $table->getModelInstance()::query()
                ->whereFullText($table->searchAbleColumns(), $searchQuery);

            if (!empty($settings['filters'])) {
                foreach ($settings['filters'] as $filter) {

                    // todo improve this
                    [$column, $value] = explode('=', $filter);
                    $query->where(trim($column), trim($value));
                }
            }

            $results = $query->get();

            // dd($results);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if (app()->isLocal()) {
                throw $e;
            }

            return null;
        }

        return new SearchResultsDTO(
            hits: $results,
            totalHits: $results->count(),
            searchQuery: $searchQuery,
            timeTakenInMilliseconds: 1,
            strategyUsed: 'vector_meilisearch'
        );
    }

    public function getVectorEmbeddings(string $text, ModelType $modelType): ?array
    {
        $closure = match ($modelType) {
            ModelType::OPEN_AI_ADA_002 => function () use ($text) {
                try {
                    $response = retry(2, function () use ($text) {
                        return OpenAI::embeddings()->create([
                            'model' => 'text-embedding-ada-002',
                            'input' => $text,
                        ]);
                    }, 2000);

                    return $response->embeddings[0]->embedding;
                } catch (Exception $e) {
                    if (!app()->isProduction()) {
                        throw $e;
                    }
                    Log::info($e->getMessage());

                    return null;
                }
            },

            ModelType::All_MINI_LM_L6_V2 => function () use ($text, $modelType) {
                $data = json_encode([
                    'model' => $modelType->value,
                    'text' => $text,
                ]);

                $response = Http::withBody($data, 'application/json')
                    ->post('http://194.163.34.183/Microservices/service/embeddings/GenerateEmbeddings.php');

                if ($response->successful()) {
                    return $response->json()['data']['embeddings'];
                }
            },

            default => throw new Exception('No strategy for calculating vector embeddings for modelType: ' . $modelType->value)
        };

        return $closure();
    }

    public static function enableVectorSearch()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('services.meilisearch.key'),
        ])->patch(config('services.meilisearch.host') . '/experimental-features', [
            'vectorStore' => true,
            // 'scoreDetails' => true,
        ]);

        return $response->json();
    }

    /**
     * sync local settings with Meilisearch Service
     */
    public function syncLocalSettings(SearchAbleTable $searchAbleTable): bool
    {
        $localSettings = $searchAbleTable->meilisearchIndexSettings();

        $response = (new MeilisearchService())
            ->client
            ->index($searchAbleTable->getIndexName())
            ->updateSettings($localSettings);

        if ($response['status'] !== 'enqueued') {
            throw new Exception('Meilisearch not able to sync settings for in ' . $searchAbleTable->getIndexName());
        }

        return true;
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
            'nonSeparatorTokens' => [],
            'separatorTokens' => [],
            'dictionary' => [],
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
                'sortFacetValuesBy' => [
                    '*' => 'alpha',
                ],
            ],
            'pagination' => [
                'maxTotalHits' => 1000,
            ],
        ];
    }
}
