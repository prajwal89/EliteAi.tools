<?php

namespace App\Enums;

enum ProviderType: string
{
    case GOOGLE = 'Google';
    // case GITHUB = 'Github';
    // case TWITTER = 'Twitter';

    public static function values()
    {
        return array_column(self::cases(), 'value');
    }
}
