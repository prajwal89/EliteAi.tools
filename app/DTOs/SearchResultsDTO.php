<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class SearchResultsDTO
{
    // ?should i include error message here for frontend
    public function __construct(
        public Collection $hits,
        public int $totalHits, //for pagination etc
        public ?string $searchQuery = null,
        public float $timeTakenInMilliseconds,
        public string $strategyUsed, // default_meilisearch | vector_meilisearch | fulltext   todo create enum for this
    ) {
    }
}
