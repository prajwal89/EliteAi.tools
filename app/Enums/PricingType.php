<?php

namespace App\Enums;

enum PricingType: string
{
    case FREE = 'Free';
    case PAID = 'Paid';
    case FREEMIUM = 'Freemium';
    case FREE_TRIAL = 'Free Trial';
    case CONTACT_FOR_PRICING = 'Contact for Pricing';
    case USAGE_BASED = 'Usage Based';
    case PAY_ONCE = 'Pay Once';
    case OTHER = 'Other';

    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public function explain(): string
    {
        return match ($this) {
            PricingType::FREE => 'AI tool that all of its services for free',
            PricingType::PAID => 'AI tool that has all its subscriptions paid',
            PricingType::FREEMIUM => 'AI tool that offers basic services for free while more advanced features must be paid for',
            PricingType::FREE_TRIAL => 'AI tool that offers free trial',
            PricingType::CONTACT_FOR_PRICING => 'Contact tool the owner for pricing',
            PricingType::USAGE_BASED => 'AI tools where a user pays per use or only charges on usage',
            PricingType::PAY_ONCE => 'Pay once use it for a lifetime',
            PricingType::OTHER => 'Any other type of pricing type',
        };
    }
}
