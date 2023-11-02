<?php

namespace App\Services;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use League\Uri\Uri;
use voku\helper\HtmlDomParser;

class DomainNameExtractors
{
    public static function insertToolsExtractedFromTopAiTools()
    {
        $rawContent = file_get_contents(public_path('/responses/findmyaitool.com/tool-list.json'));
        $html = json_decode($rawContent)->html;

        $dom = HtmlDomParser::str_get_html($html);

        $allLinks = [];
        foreach ($dom->find('a') as $a) {
            $allLinks[] = $a->href;
        }

        $filteredLinks = collect($allLinks)
            ->filter(function ($url) {
                if (str_contains($url, 'https://findmyaitool.com/')) {
                    return false;
                }

                return true;
            })->map(function ($url) {
                return str($url)->before('?')->trim()->rtrim('/')->toString();
            })
            ->unique()
            ->values()
            ->map(function ($url) {
                $domain = Uri::createFromString($url)->getHost();
                $domainWithoutWWW = preg_replace('/^www\./', '', $domain);

                DB::table('extracted_tool_domains')->insertOrIgnore([
                    'domain_name' => $domainWithoutWWW,
                    'home_page_url' => $url,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $url;
            });

        dd($filteredLinks);
    }

    public static function extractsDomainsFromToolify()
    {
        // max pages 69
        // https://www.toolify.ai/self-api/v1/tools?order_by=recommended_at&page=1&per_page=100
        for ($i = 1; $i < 70; $i++) {
            $response = Http::get("https://www.toolify.ai/self-api/v1/tools?order_by=recommended_at&page=$i&per_page=100");
            foreach ($response->json()['data']['data'] as $tool) {
                $domain = Uri::createFromString($tool['website'])->getHost();
                $domainWithoutWWW = preg_replace('/^www\./', '', $domain);

                $homePage = str($tool['website'])->before('?')->trim()->rtrim('/')->toString();

                // dump($domain, $homePage);
                // dd($domain);

                DB::table('extracted_tool_domains')->insertOrIgnore([
                    'domain_name' => $domainWithoutWWW,
                    'home_page_url' => $homePage,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            // dd($response->json()['data']['data']);

            dump('done page:' . $i);
        }
    }
}
