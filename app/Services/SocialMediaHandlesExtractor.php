<?php

namespace App\Services;

use App\DTOs\ToolSocialHandlesDTO;
use voku\helper\HtmlDomParser;

// todo what if there are two social media handles of same provider
// !we are assuming that every handle is company handle
class SocialMediaHandlesExtractor
{
    // Define a mapping of platforms to regular expressions
    public $platformRegexMap = [
        'instagram' => [
            '/instagram\.com\/([^\/]+)/',
        ],
        'tiktok' => [
            '/tiktok\.com\/@([^\/]+)/',
        ],
        'twitter' => [
            // *Order matters
            // https://twitter.com/intent/follow?screen_name=MURFAISTUDIO
            '/twitter\.com\/intent\/follow\?screen_name=([^&]+)/',
            // https://twitter.com/intent/user?screen_name=PublerNation
            '/twitter\.com\/intent\/user\?screen_name=([^&]+)/',
            '/twitter\.com\/([^\/]+)/',
        ],
        'linkedin' => [
            '/linkedin\.com\/in\/([^\/]+)/'
        ],
        'linkedin_company' => [
            '/linkedin\.com\/company\/([^\/]+)/'
        ],
        'facebook' => [
            '/facebook\.com\/profile\.php\?id=([^&]+)/'
        ],
        // https://in.pinterest.com/PublerNation/
        'pinterest' => [
            '/pinterest\.com\/([^\/]+)/'
        ],
        'youtube_channel' => [
            // This is channel id
            '/youtube\.com\/channel\/([^\/]+)/',
            // https://www.youtube.com/c/PublerNation?sub_confirmation=1
            //   this is different 
            // '/youtube\.com\/c\/([^\/\?]+)/',
        ],
        'discord_channel_invite_id' => [
            '/discord\.com\/invite\/([^\/]+)/',
            // https://discord.gg/jURWSjARb5
            '/discord\.gg\/([^\/]+)/',
        ],
        'subreddit_id' => [
            '/www.reddit.com\/r\/([^\/]+)/'
        ],
        'telegram_channel_id' => [
            '/t.me\/([^\/]+)/'
        ],
        'github_repository_path' => [
            // https://github.com/DavidTParks/pixelfy
            '/github\.com\/([^\/]+\/[^\/]+)/'
        ],

        // *Extension
        'chrome_extension_id' => [
            '/chrome\.google\.com\/webstore\/detail\/.*?\/([^\/]+)/'
        ],
        'firefox_extension_id' => [
            '/addons.mozilla.org\/en-US\/firefox\/([^\/]+)/'
        ],

        // *other social handles that requires special handling
        'email' => [
            '/mailto:(.*)/'
        ],
    ];

    // *write manual logic for all this handles
    public $manuallyExtractHandle = [
        'android_app' => [
            'whole_url_pattern' => '/play\.google\.com/i'
        ],
        'ios_app' => [
            'whole_url_pattern' => '/apps\.apple\.com/i'
        ]
    ];

    public function __construct(public HtmlDomParser $dom)
    {
    }

    public function extractSocialHandles(): ToolSocialHandlesDTO
    {
        $socialHandles = [];

        // Iterate through anchor elements
        foreach ($this->dom->find('a') as $a) {
            $href = $a->getAttribute('href');

            // Iterate through the platform-to-regex mapping
            foreach ($this->platformRegexMap as $platform => $regexPatterns) {
                foreach ($regexPatterns as $pattern) {
                    if (preg_match($pattern, $href, $matches)) {
                        // Extracted a handle for the current platform
                        $handle = end($matches);
                        $socialHandles[$platform] = trim($handle);
                        break;
                    }
                }
            }
        }

        $allSocialMedias = $socialHandles + $this->extractOtherHandles();

        // dd($allSocialMedias);

        return ToolSocialHandlesDTO::fromArray($allSocialMedias);
    }

    private function extractOtherHandles(): array
    {

        $socialHandles = [];

        foreach ($this->dom->find('a') as $a) {
            $href = $a->getAttribute('href');

            // Iterate through the platform-to-regex mapping
            foreach ($this->platformRegexMap as $platform => $regexPatterns) {
                foreach ($regexPatterns as $pattern) {
                    if ($platform === 'email') {
                        preg_match($pattern, $href, $matches);
                        if (isset($matches[1])) {
                            $handle = str($matches[1])->before('?')->toString();
                            $socialHandles[$platform] = trim($handle);
                            break;
                        }
                    }
                }
            }


            // ! we are iterating through all urls
            foreach ($this->manuallyExtractHandle as $platform => $data) {
                if ($platform === 'android_app') {
                    if (preg_match($data['whole_url_pattern'], $href)) {
                        // Parse the URL and extract the query string
                        $queryString = parse_url($href, PHP_URL_QUERY);

                        // Parse the query string and extract the 'id' parameter
                        parse_str($queryString, $queryParameters);

                        $socialHandles[$platform] = trim($queryParameters['id'] ?? '');
                    }
                } elseif ($platform === 'ios_app' && preg_match('/id(\d+)/', $href, $matches)) {
                    if (preg_match($data['whole_url_pattern'], $href)) {
                        $socialHandles[$platform] = 'id' .  trim($matches[1]);
                    }
                }
            }
        }

        // dd($socialHandles);
        return $socialHandles;
    }
}
