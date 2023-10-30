<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\DTOs\ToolDTO;
use App\Enums\SearchAbleTable;
use App\Models\ExtractedToolDomain;
use App\Models\Tool;
use App\Services\ExtractedToolProcessor;
use App\Services\MeilisearchService;
use Exception;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use HeadlessChromium\BrowserFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use League\Uri\Uri;
use voku\helper\HtmlDomParser;
use Intervention\Image\Facades\Image;

/**
 * For testing out misc things
 */
class TestController extends Controller
{
    public function __construct()
    {
        if (app()->isProduction()) {
            abort(404);
        }
    }

    public function __invoke()
    {
        // return (new MeilisearchService())->indexAllDocumentsOfTable(SearchAbleTable::TOOL);
        // return (new MeilisearchService())->deIndexTable(SearchAbleTable::TOOL);

        return $this->vectorEmbedding();
        // return $this->vectorSearchWithCurl();
        // return $this->vectorSearch();


        return $this->loginSuperAdmin();

        // return $this->buildToolDto();

        $prompt = ExtractedToolProcessor::buildSystemPrompt(public_path('/prompts/system-1.txt'));

        echo nl2br($prompt);

        // (new ExtractedToolProcessor(ExtractedToolDomain::find(1)))->process();

        // $this->insertTools();

        // code...
        // auth()->login(\App\Models\User::find(1));
        // return $this->crawlTopAiTools3();

        return $this->loginSuperAdmin();
    }

    public function vectorEmbedding()
    {
        // exec('python E:\PHP\Microservices\service\embeddings\generate_embeddings.py "sample"', $output, $result);
        // dd($output);

        $allTools = Tool::whereNotIn('id', [])->get();

        foreach ($allTools as $tool) {
            $paragraphToEmbed = '';

            $paragraphToEmbed .= $tool->name . PHP_EOL;
            $paragraphToEmbed .= $tool->tag_line . PHP_EOL;
            $paragraphToEmbed .= $tool->summary . PHP_EOL;
            $paragraphToEmbed .= strip_tags($tool->description) . PHP_EOL;

            if (!empty($tool->top_features)) {
                $paragraphToEmbed .=  PHP_EOL . 'Features' . PHP_EOL;

                foreach ($tool->top_features as $feature) {
                    $paragraphToEmbed .= $feature . PHP_EOL;
                }
            }

            if (!empty($tool->use_cases)) {
                $paragraphToEmbed .=   PHP_EOL . 'Use-Cases' . PHP_EOL;

                foreach ($tool->use_cases as $useCase) {
                    $paragraphToEmbed .= $useCase . PHP_EOL;
                }
            }

            exit($paragraphToEmbed);


            exec('python E:\PHP\Microservices\service\embeddings\generate_embeddings.py "' . $paragraphToEmbed . '"', $output, $result);

            try {
                $response = json_decode($output[0]);
            } catch (Exception $e) {
            }

            (new MeilisearchService)->updateDocument(
                SearchAbleTable::TOOL,
                $tool->id,
                [
                    '_vectors' => $response->embeddings
                ]
            );

            $tool->update(['vectors' => $response->embeddings]);

            unset($output);
            unset($response);

            // dump($paragraphToEmbed);
            // dd($response);
            // dd($paragraphToEmbed);
        }
    }

    public function vectorSearch()
    {
        $query = 'Crypto News Today';

        exec('python E:\PHP\Microservices\service\embeddings\generate_embeddings.py "' . $query . '"', $output, $result);

        try {
            $response = json_decode($output[0]);
        } catch (Exception $e) {
        }

        $response->embeddings;


        // Define the search query
        $query = ['query' => 'Your Search Query', 'vector' => [0, 1, 2]]; // Replace 'Your Search Query' with the actual search query


        $searchResult = (new MeilisearchService)
            ->meilisearchClient
            ->index(SearchAbleTable::TOOL->getIndexName())
            ->search('yo');


        // Print the search result
        dd($searchResult);
    }

    public function vectorSearchWithCurl()
    {
        $meiliSearchUrl = 'http://localhost:7700';
        $apiKey = 'YOUR_MEILISEARCH_API_KEY'; // Replace with your MeiliSearch API key


        $query = 'i want ai for doing citation';

        exec('python E:\PHP\Microservices\service\embeddings\generate_embeddings.py "' . $query . '"', $output, $result);

        try {
            $response = json_decode($output[0]);
        } catch (Exception $e) {
        }

        // Specify the search query
        $searchQuery = [
            'vector' =>  $response->embeddings
        ];

        // Create an array with headers, including the content-type and authentication
        $headers = [
            'Content-Type' => 'application/json',
            'X-Meili-API-Key' => $apiKey,
        ];

        // Define the full URL for the search endpoint
        $searchEndpoint = $meiliSearchUrl . '/indexes/' . SearchAbleTable::TOOL->getIndexName() . '/search';

        // Send the POST request using the HTTP facade
        $response = Http::withHeaders($headers)->post($searchEndpoint, $searchQuery);

        // Get the JSON response content
        $responseData = $response->json();

        // Print or use the response data as needed
        dd($responseData);
    }

    public function buildToolDto()
    {
        $toolDto = ToolDTO::fromJson(file_get_contents(public_path('jsons/1.json')));

        dd($toolDto);
    }

    public function loginSuperAdmin()
    {
        if (app()->isProduction()) {
            abort(404);
        }

        auth()->login(
            \App\Models\User::where('email', '00prajwal@gmail.com')
                ->where('provider_type', \App\Enums\ProviderType::GOOGLE->value)
                ->first(),
            true
        );
    }

    public function crawlTopAiTools3()
    {
        $content = Http::get('https://findmyaitool.com/api/tool-list?page_no=6&limit=15&pricing=&features=&sort_by=New&search_qry=')->getBody()->getContents();

        dd($content);
    }

    public function crawlTopAiTools2()
    {
        $chromeOptions = new \Facebook\WebDriver\Chrome\ChromeOptions();
        $chromeOptions->addArguments(['--headless']); // Run Chrome in headless mode (no GUI)

        // Set desired capabilities
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(\Facebook\WebDriver\Chrome\ChromeOptions::CAPABILITY, $chromeOptions);

        // Start a new WebDriver session
        $driver = RemoteWebDriver::create('http://localhost:9515', $capabilities);

        // Navigate to the website
        $driver->get('https://topai.tools/');

        // Wait for the page to load (you can customize the wait time)
        $driver->wait(10)->until(
            \Facebook\WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated(
                \Facebook\WebDriver\WebDriverBy::tagName('body')
            )
        );

        // Get the page source (HTML)
        $html = $driver->getPageSource();

        // Output the HTML (you can store or manipulate it as needed)
        echo $html;

        // Close the WebDriver session
        $driver->quit();
    }

    public function crawlTopAiTools()
    {
        $url = 'https://topai.tools/';

        $browserFactory = new BrowserFactory();

        // starts headless Chrome
        $browser = $browserFactory->createBrowser([
            'headless' => false, // disable headless mode
        ]);

        // creates a new page and navigate to an URL
        $page = $browser->createPage();

        // navigate
        $navigation = $page->navigate($url);

        // wait for the page to be loaded
        $navigation->waitForNavigation();

        // evaluate script in the browser
        $evaluation = $page->evaluate('document.documentElement.innerHTML');

        // wait for the value to return and get it
        $value = $evaluation->getReturnValue();

        dd($value);
    }

    public function insertTools()
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
}
