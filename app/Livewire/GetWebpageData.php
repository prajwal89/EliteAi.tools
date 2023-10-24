<?php

namespace App\Livewire;

use App\Services\ExtractedToolProcessor;
use App\Services\WebPageFetcher;
use Livewire\Component;

class GetWebpageData extends Component
{
    public $url;

    public $contentForPrompt;

    public function render()
    {
        return view('livewire.get-webpage-data');
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
}
