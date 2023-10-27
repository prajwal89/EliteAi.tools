<?php

namespace App\Livewire;

use App\Services\ExtractedToolProcessor;
use App\Services\WebPageFetcher;
use Livewire\Component;
use voku\helper\HtmlDomParser;

class ToolImporter extends Component
{
    public $url;

    public $contentForPrompt;
    public string $promptForSystem;

    public function mount()
    {
        $this->promptForSystem = \App\Services\ExtractedToolProcessor::buildSystemPrompt(public_path('/prompts/prompt.txt'));
    }

    public function getData()
    {
        $html = (new WebPageFetcher(
            // $this->extractedToolDomain->home_page_url
            // 'https://www.getrecall.ai/',
            // 'https://www.photoecom.com/',
            // 'https://voyp.app/', //has email
            // 'https://qrdiffusion.com/'
            $this->url
        ))->get()->content;


        // todo match all social media handles urls
        // $dom = HtmlDomParser::str_get_html($html);

        // $allHandles = $this->socialMediaHandles($dom);

        // dd($allHandles);

        $cleanContent = "
---------------------
Tool Url: " . $this->url . "
---------------------
        ";

        $cleanContent .= ExtractedToolProcessor::removeUnNecessaryThingsFromHTML(
            $html
        );


        $this->contentForPrompt = $cleanContent;
    }

    public function socialMediaHandles($dom)
    {
        // Define an array of regular expressions for common social media URLs
        $patterns = array(
            'twitter.com' => '/twitter\.com/i',
            'facebook.com' => '/facebook\.com/i',
            'instagram.com' => '/instagram\.com/i',
            'linkedin.com' => '/linkedin\.com/i',
            // Add more patterns for other social media platforms as needed
        );

        // Initialize an empty array to store the matched social media user handles
        $socialMediaUserHandles = array();

        foreach ($dom->find('a') as $a) {
            $href = $a->href;
            // dd($href);

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

        dd($socialMediaUserHandles);

        // Now, $socialMediaUserHandles will contain the social media user handles by platform
        return $socialMediaUserHandles;
    }

    // Function to extract user handles based on platform
    public function extractUserHandle($url, $platform)
    {
        $userHandle = '';
        // You can implement custom logic to extract user handles based on the platform
        // For example, for Twitter, you might use a regular expression to extract the Twitter handle from the URL.
        if ($platform === 'twitter.com') {
            // Implement Twitter user handle extraction logic here
            // Example: preg_match('/twitter\.com\/([^\/]+)/', $url, $matches);
        }
        // Add similar logic for other platforms as needed
        return $userHandle;
    }


    public function render()
    {
        return view('livewire.tool-importer');
    }
}
