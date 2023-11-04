<?php

namespace App\Livewire;

use App\DTOs\ToolDTO;
use App\DTOs\ToolSocialHandlesDTO;
use App\Models\Tool;
use App\Services\ExtractedToolProcessor;
use App\Services\WebPageFetcher;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;
use voku\helper\HtmlDomParser;

class ToolImporter extends Component
{
    public $url;

    public $contentForPrompt;

    public string $promptForSystem;

    public string $responseJson;

    public int $jsonParseStatus = 0;

    public $toolSocialHandlesDTO = [];

    public function mount()
    {
        $this->promptForSystem = \App\Services\ExtractedToolProcessor::buildSystemPrompt(public_path('/prompts/prompt.txt'));
    }

    public function getData()
    {
        $this->url = rtrim($this->url, '/');

        if (Tool::where('domain_name', getDomainFromUrl($this->url))->exists()) {
            dd('We already have this tool.');
        }

        $html = (new WebPageFetcher($this->url))->get()->content;

        // todo match all social media handles urls
        $dom = HtmlDomParser::str_get_html($html);

        $this->toolSocialHandlesDTO = collect($this->socialMediaHandles($dom))->toArray();

        // dd($this->toolSocialHandlesDTO);
        $this->getContentForPrompt($html);
    }

    public function getResponseFromOpenAi()
    {
        $prompt = $this->promptForSystem;
        $prompt .= "\n\nContent of the website as follows:\n\n";
        $prompt .= $this->contentForPrompt;

        // dd($prompt);
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4',
                // 'model' => 'gpt-3.5-turbo',
                'max_tokens' => 2000,
                'messages' => [
                    // [
                    //     'role' => 'system',
                    //     'content' => $systemPrompt,
                    // ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            $this->responseJson = trim($response->choices[0]->message->content);

            Log::info($this->responseJson);

            try {
                if (empty(ToolDTO::fromJson($this->responseJson))) {
                    $this->jsonParseStatus = -1;
                } else {
                    $this->jsonParseStatus = 1;
                }
            } catch (Exception $e) {
                $this->jsonParseStatus = -1;
            }
        } catch (Exception $e) {
            Log::error($e);

            dd($e->getMessage());
        }
    }

    public function getContentForPrompt($html)
    {

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

            'chrome_extension_id' => '/chrome.google.com\/webstore\//i',
            'firefox_extension_id' => '/addons\.mozilla\.org/i',
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

    public function extractUserHandle(string $url, string $platform): string
    {
        // Define a mapping of platforms to regular expressions
        $platformRegexMap = [
            'instagram' => '/instagram\.com\/([^\/]+)/',
            'tiktok' => '/tiktok\.com\/@([^\/]+)/',
            // match x.com
            'twitter' => '/twitter\.com\/([^\/]+)/',
            'linkedin' => '/linkedin\.com\/in\/([^\/]+)/',
            'linkedin_company' => '/linkedin\.com\/company\/([^\/]+)/',
            'facebook' => '/facebook\.com\/profile\.php\?id=([^&]+)/',
            'youtube_channel' => '/youtube\.com\/channel\/([^\/]+)/',
            'discord_channel_invite_id' => '/discord\.com\/invite\/([^\/]+)/',
            'subreddit_id' => '/www.reddit.com\/r\/([^\/]+)/',
            'telegram_channel_id' => '/t.me\/([^\/]+)/',

            'chrome_extension_id' => '/chrome\.google\.com\/webstore\/detail\/.*?\/([^\/]+)/',
            'firefox_extension_id' => '/addons.mozilla.org\/en-US\/firefox\/([^\/]+)/',
        ];

        // Check if the platform exists in the mapping
        if (isset($platformRegexMap[$platform])) {
            // Use the corresponding regular expression
            preg_match($platformRegexMap[$platform], $url, $matches);

            return $matches[1] ?? '';
        } elseif ($platform === 'email') {
            preg_match('/mailto:(.*)/', $url, $matches);
            if (isset($matches[1])) {
                $handle = str($matches[1])->before('?')->toString();

                return $handle;
            }
        } elseif ($platform === 'android_app') {
            // Parse the URL and extract the query string
            $queryString = parse_url($url, PHP_URL_QUERY);

            // Parse the query string and extract the 'id' parameter
            parse_str($queryString, $queryParameters);

            return $queryParameters['id'] ?? '';
        } elseif ($platform === 'ios_app' && preg_match('/id(\d+)/', $url, $matches)) {
            return 'id' . $matches[1];
        }

        return '';
    }

    public function render()
    {
        return view('livewire.tool-importer');
    }
}
