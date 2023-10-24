<?php

namespace App\Services;

use App\Enums\PricingType;
use App\Models\Category;
use App\Models\ExtractedToolDomain;
use voku\helper\HtmlDomParser;

class ExtractedToolProcessor
{
    public function __construct(public ExtractedToolDomain $extractedToolDomain)
    {
    }

    public function process()
    {
        // todo get raw hml
        $html = (new WebPageFetcher(
            // $this->extractedToolDomain->home_page_url
            // 'https://www.getrecall.ai/',
            // 'https://www.photoecom.com/',
            // 'https://voyp.app/', //has email
            'https://qrdiffusion.com/'
        ))->get()->content;

        $cleanContent = $this->removeUnNecessaryThingsFromHTML(
            $html
        );

        $systemPrompt = $this->buildSystemPrompt(public_path('/prompts/system-1.txt'));

        echo $systemPrompt;

        echo '</br>-------</br>';

        echo $cleanContent;

        $prompt = $this->buildPrompt($cleanContent, $systemPrompt);

        dump($prompt);

        //todo extract email id
        $emails = $this->extractEmails($html);
        dump($emails);

        //todo extract playstore and appstore app id

        //todo extract favicon

        //todo capture screenshot

        dump($cleanContent);
    }

    public static function removeUnNecessaryThingsFromHTML(string $htmlString): string
    {
        $dom = HtmlDomParser::str_get_html($htmlString);

        foreach ($dom->find('*') as $element) {
            $element->removeAttributes();

            //* remove empty tags
            if (empty(trim($element->innertext))) {
                $element->outertext = '';
            }
        }

        $filteredContents = str($dom->innerHtml())
            ->replaceMatches('/<script(.*?)<\/script>/is', '') //remove script tags
            ->replaceMatches('/<style(.*?)<\/style>/is', '') //remove script tags
            ->replaceMatches('/<ins(.*?)<\/ins>/is', '') //remove google ads
            ->replace("\t", '')
            ->replaceMatches('/<!--(.|\s)*?-->/', '') //remove comment
            ->stripTags()
            ->replaceMatches('/\n+/', "\n")
            ->trim()
            ->toString();

        return html_entity_decode($filteredContents);
    }

    public static function extractEmails(string $htmlString): array
    {
        // Regular expression to match email addresses
        // match domain name also
        $pattern = '/[\w\.-]+@[\w\.-]+/';

        // Find all email addresses in the HTML string
        preg_match_all($pattern, $htmlString, $matches);

        // $matches[0] now contains an array of matched email addresses
        return $matches[0];
    }

    public function buildPrompt(string $cleanContent, string $systemPrompt): array
    {
        return [
            // 'model' => 'gpt-3.5-turbo-16k',
            'model' => 'gpt-4',
            'max_tokens' => 5000,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ], [
                    'role' => 'user',
                    'content' => $cleanContent,
                ],
            ],
        ];
    }

    public static function buildSystemPrompt($systemPromptTxtFileLocation): string
    {
        $categoriesString = Category::all()->pluck('name')->implode(', ');

        $pricingTypeString = collect(PricingType::values())->implode(', ');

        return str(file_get_contents($systemPromptTxtFileLocation))
            ->replace('{{ $categoriesString }}', $categoriesString)
            ->replace('{{ $pricingTypeString }}', $pricingTypeString);
    }
}
