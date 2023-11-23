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
        public string $description,
        public string $descriptionForNlProcessing,
        public array $topFeatures,
        public array $useCases,
        public array $relatedSearches,
        public bool $hasApi,
        public PricingType $pricingType,
        public ?int $monthlySubscriptionStartsFrom,
        public array $categories,
        public array $tags,
    ) {
    }

    // * building from mostly openAi response
    public static function fromJson(string $jsonString): ?ToolDTO
    {
        try {
            $toolData = json_decode($jsonString);

            // try {
            //     $pricingType = PricingType::from($toolData->pricing_type);
            // } catch (Exception $e) {
            //     // Handle the exception here
            //     // You can log the error, provide a default value, or take other appropriate actions
            // }

            // dd($toolData);

            return new self(
                name: $toolData->name,
                tagLine: $toolData->tag_line,
                summary: $toolData->summary,
                description: $toolData->description,
                descriptionForNlProcessing: $toolData->description_for_nl_processing,
                topFeatures: $toolData->top_features,
                useCases: $toolData->use_cases,
                relatedSearches: $toolData->related_searches,
                hasApi: $toolData->has_api,
                pricingType: PricingType::from($toolData->pricing_type),
                monthlySubscriptionStartsFrom: $toolData->monthly_subscription_starts_from,
                categories: $toolData->categories,
                tags: $toolData->tags,
            );
        } catch (Exception $e) {
            return null;
        }
    }
}
