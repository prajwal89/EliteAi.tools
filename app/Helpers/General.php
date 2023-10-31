<?php

use App\Enums\UtmSource;
use App\Services\UtmQueryBuilder;

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

if (!function_exists('getDomainFromUrl')) {
    function getDomainFromUrl($url): string
    {
        $urlParts = parse_url($url);
        if (isset($urlParts['host'])) {
            $domain = $urlParts['host'];

            // Remove "www" prefix if present
            if (strpos($domain, 'www.') === 0) {
                $domain = substr($domain, 4); // Remove the first 4 characters ("www.")
            }

            return $domain;
        } else {
            return ''; // Return an empty string if the URL doesn't have a host (domain).
        }
    }
}

// function defaultUtmQuery()
// {
//     (new UtmQueryBuilder(UtmSource::))
// }

function truncate($string, $length, $dots = '...')
{
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}


if (!function_exists('getGoogleThumbnailUrl')) {
    function getGoogleThumbnailUrl(string $url): string
    {
        $params['client'] = 'SOCIAL';
        $params['type'] = 'FAVICON';
        $params['fallback_opts'] = 'TYPE,SIZE,URL';
        $params['url'] = $url;
        $params['size'] = '128';

        $faviconUrl = 'https://t0.gstatic.com/faviconV2';
        $faviconUrl .= '?' . http_build_query($params);

        return $faviconUrl;
    }
}
