<?php

namespace App\Livewire;

use App\DTOs\ToolDTO;
use App\DTOs\ToolSocialHandlesDTO;
use App\Models\Tool;
use App\Services\ExtractedToolProcessor;
use App\Services\SocialMediaHandlesExtractor;
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

        // dd((new SocialMediaHandlesExtractor($dom))->extractSocialHandles());

        $this->toolSocialHandlesDTO = collect(
            (new SocialMediaHandlesExtractor($dom))->extractSocialHandles()
        )->toArray();

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

        $cleanContent = '---------------------
Tool Url: ' . $this->url . '
---------------------';

        $cleanContent .= ExtractedToolProcessor::removeUnNecessaryThingsFromHTML(
            $html
        );

        // todo include pricing page if available  e.g  /pricing  /plans

        $this->contentForPrompt = $cleanContent;
    }

    public function render()
    {
        return view('livewire.tool-importer');
    }
}
