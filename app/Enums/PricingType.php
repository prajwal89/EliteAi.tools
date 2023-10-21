<?php

namespace App\Enums;

enum PricingType: string
{
    case FREE = 'Free';
    case PAID = 'Paid';
    case FREEMIUM = 'Freemium';
    case FREE_TRIAL = 'Free Trial';
    case CONTACT_FOR_PRICING = 'Contact for pricing';
    case OTHER = 'Other';

    public static function values()
    {
        return array_column(self::cases(), 'value');
    }
}
