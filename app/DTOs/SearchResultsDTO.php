<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class SearchResultsDTO
{
    public function __construct(
        public Collection $hits, //array of model instances
        public string $searchQuery,
        public float $timeTakenInSec,
        public ?string $strategyUsed, // default_meilisearch | vector_meilisearch | fulltext 
    ) {
    }
}
