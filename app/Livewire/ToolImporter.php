<?php

namespace App\Livewire;

use App\DTOs\ToolSocialHandlesDTO;
use App\Services\ExtractedToolProcessor;
use App\Services\WebPageFetcher;
use Livewire\Component;
use voku\helper\HtmlDomParser;

class ToolImporter extends Component
{
    public $url;

    public $contentForPrompt;

    public string $promptForSystem;

    public $toolSocialHandlesDTO = [];

    public function mount()
    {
        $this->promptForSystem = \App\Services\ExtractedToolProcessor::buildSystemPrompt(public_path('/prompts/prompt.txt'));
    }

    public function getData()
    {
        $this->url = rtrim($this->url, '/');

        $html = (new WebPageFetcher(
            // $this->extractedToolDomain->home_page_url
            // 'https://www.getrecall.ai/',
            // 'https://www.photoecom.com/',
            // 'https://voyp.app/', //has email
            // 'https://qrdiffusion.com/'
            $this->url
        ))->get()->content;

        // todo match all social media handles urls
        $dom = HtmlDomParser::str_get_html($html);

        $this->toolSocialHandlesDTO = collect($this->socialMediaHandles($dom))->toArray();

        // dd($this->toolSocialHandlesDTO);

        $cleanContent = '
---------------------
Tool Url: ' . $this->url . '
---------------------
        ';

        $cleanContent .= ExtractedToolProcessor::removeUnNecessaryThingsFromHTML(
            $html
        );

        $this->contentForPrompt = $cleanContent;
    }

    public function socialMediaHandles($dom): ToolSocialHandlesDTO
    {
        // Define an array of regular expressions for common social media URLs
        $patterns = [
            'twitter' => '/twitter\.com/i',
            'facebook' => '/facebook\.com/i',
            'instagram' => '/instagram\.com/i',
            'tiktok' => '/tiktok\.com/i',
            'youtube_channel' => '/youtube\.com/i',
            'linkedin_company' => '/linkedin\.com\/company\//i',

            'discord_channel_invite_id' => '/discord\.com/i',
            'subreddit_id' => '/reddit\.com/i',
            'telegram_channel_id' => '/t\.me/i',

            'android_app' => '/play\.google\.com/i',
            'ios_app' => '/apps\.apple\.com/i',

            'email' => '/mailto\:/i',
        ];

        // Initialize an empty array to store the matched social media user handles
        $socialMediaUserHandles = [];

        foreach ($dom->find('a') as $a) {
            $href = $a->href;

            // Check if the href attribute matches any of the defined patterns
            foreach ($patterns as $platform => $pattern) {
                if (preg_match($pattern, $href)) {
                    // Extract the user handle from the URL
                    $userHandle = $this->extractUserHandle($href, $platform);
                    if (!empty($userHandle)) {
                        $socialMediaUserHandles[$platform] = $userHandle;
                    }
                    break; // Stop checking other patterns for this URL
                }
            }
        }

        return ToolSocialHandlesDTO::fromArray($socialMediaUserHandles);
    }

    // Function to extract user handles based on platform
    public function extractUserHandle($url, $platform)
    {
        // return $url;

        if ($platform === 'instagram') {
            preg_match('/instagram\.com\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'tiktok') {
            preg_match('/tiktok\.com\/@([^\/]+)/', $url, $matches);
        } elseif ($platform === 'twitter') {
            preg_match('/twitter\.com\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'linkedin') {
            preg_match('/linkedin\.com\/in\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'linkedin_company') {
            preg_match('/linkedin\.com\/company\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'facebook') {
            preg_match('/facebook\.com\/profile\.php\?id=([^&]+)/', $url, $matches);
        } elseif ($platform === 'youtube_channel') {
            preg_match('/youtube\.com\/channel\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'discord_channel_invite_id') {
            // https://discord.com/invite/Naa2qkyMkt
            preg_match('/discord\.com\/invite\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'subreddit_id') {
            // https://www.reddit.com/r/NSFWCharacterAI/
            preg_match('/www.reddit.com\/r\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'telegram_channel_id') {
            // https://t.me/+J_J28uSPzTM1NWZl
            preg_match('/t.me\/([^\/]+)/', $url, $matches);
        } elseif ($platform === 'email') {
            preg_match('/mailto:(.*)/', $url, $matches);
            if (isset($matches[1])) {
                $handle = str($matches[1])->before('?')->toString();
            }
        } elseif ($platform === 'android_app') {
            // Parse the URL and extract the query string
            $queryString = parse_url($url, PHP_URL_QUERY);

            // Parse the query string and extract the 'id' parameter
            parse_str($queryString, $queryParameters);

            $handle = isset($queryParameters['id']) ? $queryParameters['id'] : '';
        } elseif ($platform === 'ios_app') {
            if (preg_match('/id(\d+)/', $url, $matches)) {
                $handle = 'id' . $matches[1];
            }
        }

        return $handle ?? $matches[1] ?? '';
    }

    public function render()
    {
        return view('livewire.tool-importer');
    }
}
