<?php

use App\DTOs\SearchResultsDTO;
use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Services\MeilisearchService;

// todo use dummy table
// it('can index all document of table', function () {
//     $response = (new MeilisearchService())->indexAllDocumentsOfTable(SearchAbleTable::TOOL);

//     $this->assertNotEmpty($response);

//     $this->assertEquals('enqueued', $response[0]['status']);
// });

it('can search', function () {
    $searchResults = (new MeilisearchService())->search(SearchAbleTable::TOOL, 'gpt');

    $this->assertInstanceOf(SearchResultsDTO::class, $searchResults);
});

it('can calculate vector embeddings', function () {

    $searchResults = (new MeilisearchService)->getVectorEmbeddings(
        'sample text',
        ModelType::OPEN_AI_ADA_002
    );

    $this->assertEquals(
        count($searchResults),
        ModelType::OPEN_AI_ADA_002->totalVectorDimensions()
    );
});
