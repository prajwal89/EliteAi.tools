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

function estimateTokenUsage(string $text): int
{
    // Define token-to-char ratio for English text
    $tokensPerChar = 1 / 4;

    // Calculate the token count based on the text length
    $textLength = strlen($text);
    $tokenCount = ceil($textLength * $tokensPerChar);

    return $tokenCount;
}

function formatFileSize($size)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    $i = 0;
    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }

    return round($size, 2) . ' ' . $units[$i];
}

function recursiveArrayDiff($array1, $array2)
{
    $diff = [];

    foreach ($array1 as $key => $value) {
        if (!array_key_exists($key, $array2)) {
            $diff[$key] = $value;
        } elseif (is_array($value) && is_array($array2[$key])) {
            $recursiveDiff = recursiveArrayDiff($value, $array2[$key]);

            if (count($recursiveDiff)) {
                $diff[$key] = $recursiveDiff;
            }
        } elseif ($value !== $array2[$key]) {
            $diff[$key] = $value;
        }
    }

    // Check for additional keys in $array2
    foreach ($array2 as $key => $value) {
        if (!array_key_exists($key, $array1)) {
            $diff[$key] = $value;
        }
    }

    return $diff;
}

function objectToArray($object)
{
    return json_decode(json_encode($object), true);
}
