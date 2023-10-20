<?php

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        if (
            auth()->check() &&
            (auth()->user()->email == '00prajwal@gmail.com' && auth()->user()->provider_type->value == \App\Enums\ProviderType::GOOGLE->value)
        ) {
            return true;
        }

        return false;
    }
}
