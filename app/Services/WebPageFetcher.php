<?php

namespace App\Services;

use App\DTOs\WebPageDataDTO;
use Illuminate\Support\Facades\Http;
use League\Url\Url;

// todo use this configs
$defaults = [
    'follow_redirects' => true,
    'follow_meta_refresh' => true,
    'max_redirects' => 5,
    'agent' => 'Mozilla/5.0 (compatible; PHP Scraper/1.x; +https://phpscraper.de)',
    'proxy' => null,
    'timeout' => 10,
    'disable_ssl' => false,
];

// todo use strategy enum
// todo return same type of DTO or ErrorEnum
// todo check for "You need to enable JavaScript"
class WebPageFetcher
{
    public function __construct(
        public string $url,
        public array $config = []
    ) {
        // todo validate url
    }

    // use all strategies
    public function get(): WebPageDataDTO
    {
        $response = Http::get($this->url);

        // dd($response->getBody()->getContents());

        return new WebPageDataDTO(
            content: $response->getBody()->getContents(),
            contentType: 'sample',
            statusCode: $response->status(),
        );
    }

    public function fetchWithGuzzle()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($this->url);

            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function fetchWithCurl()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $htmlContent = curl_exec($ch);

        if ($htmlContent === false) {
            return curl_error($ch);
        }

        curl_close($ch);

        return $htmlContent;
    }
}
