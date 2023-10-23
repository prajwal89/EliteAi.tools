<?php

namespace App\DTOs;

use App\Enums\PricingType;
use Exception;

class ToolDTO
{
    public function __construct(
        public string $name,
        public string $tagLine,
        public string $summary,
        public array $topFeatures,
        public array $useCases,
        public bool $hasApi,
        public PricingType $pricingType,
        public array $categories,
    ) {
    }

    public static function fromJson(string $jsonString): ?ToolDTO
    {
        try {
            $toolData = json_decode($jsonString);

            // dd($toolData);
            return new self(
                name: $toolData->name,
                tagLine: $toolData->tag_line,
                summary: $toolData->summary,
                topFeatures: $toolData->top_features,
                useCases: $toolData->use_cases,
                hasApi: $toolData->has_api,
                pricingType: PricingType::from($toolData->pricing_type),
                categories: $toolData->categories,
            );
        } catch (Exception $e) {
            return null;
        }
    }
}
