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
}
