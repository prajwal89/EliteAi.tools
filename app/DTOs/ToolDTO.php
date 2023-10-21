<?php

namespace App\DTOs;

use App\Enums\PricingType;

class ToolDTO
{
    public function __construct(
        public string $name,
        public string $tagLine,
        public string $summary,
        public array $features,
        public array $useCases,
        public bool $hasApi,
        public bool $hasDocumentation,
        public PricingType $subscriptionType,
    ) {
    }
}
