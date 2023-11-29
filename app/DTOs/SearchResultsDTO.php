<?php

namespace App\DTOs;

use Illuminate\Support\Collection;
use Meilisearch\Search\SearchResult;

class SearchResultsDTO
{
    // ?should i include error message here for frontend
    public function __construct(
        public Collection $hits,
        public int $totalHits, //for pagination etc
        public ?string $searchQuery,
        public float $timeTakenInMilliseconds,
        public string $strategyUsed, // default_meilisearch | vector_meilisearch | fulltext   todo create enum for this
    ) {
    }

    public static function fromMeilisearchResults(
        SearchResult $searchResult
    ): SearchResultsDTO {
        return new SearchResultsDTO(
            hits: collect($searchResult->getHits()),
            totalHits: $searchResult->getEstimatedTotalHits(),
            searchQuery: $searchResult->getQuery(),
            timeTakenInMilliseconds: 1,
            strategyUsed: 'default_meilisearch'
        );
    }

    public static function fromArray(
        array $searchResult
    ): SearchResultsDTO {
        return new SearchResultsDTO(
            hits: collect($searchResult['hits']),
            totalHits: $searchResult['estimatedTotalHits'],
            searchQuery: $searchResult['query'],
            timeTakenInMilliseconds: 1,
            strategyUsed: 'default_meilisearch'
        );
    }
}
