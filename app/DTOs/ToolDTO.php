<?php

namespace App\DTOs;

use App\Enums\PricingType;

class ToolDTO
{
    public function __construct(
        public string $name,
        public string $tagLine,
        public string $summary,
        public array $topFeatures,
        public array $useCases,
        public bool $hasApi,
        public PricingType $subscriptionType,
    ) {
    }
}
