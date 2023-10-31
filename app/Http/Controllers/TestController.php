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
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use HeadlessChromium\BrowserFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use League\Uri\Uri;
use voku\helper\HtmlDomParser;

/**
 * For testing out misc things
 */
class TestController extends Controller
{
    public function __construct()
    {
        // if (app()->isProduction()) {
        //     abort(404);
        // }
    }

    public function __invoke()
    {
        // dump((new MeilisearchService)->meilisearchClient->version());
        // return (new MeilisearchService())->indexAllDocumentsOfTable(SearchAbleTable::TOOL);
        // return (new MeilisearchService())->deIndexTable(SearchAbleTable::TOOL);

        // return $this->vectorSearch();

        return $this->sendVectorEmbeddingsToMeilisearch();
        // return $this->loginSuperAdmin();

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

    public function vectorSearch()
    {
        // Start the timer
        $start_time = microtime(true);

        // Call the function you want to measure
        $results = MeilisearchService::vectorSearch(SearchAbleTable::TOOL, 'real time crypto news');

        // Stop the timer
        $end_time = microtime(true);

        // Calculate the elapsed time in seconds
        $elapsed_time = $end_time - $start_time;

        // Print or log the elapsed time
        echo "Total time taken: {$elapsed_time} seconds";

        dd($results);
    }

    public function sendVectorEmbeddingsToMeilisearch()
    {
        // $allTools = Tool::whereNotIn('id', [])->get();
        $allTools = Tool::whereNull('vectors')->get();

        // dd($allTools);

        foreach ($allTools as $tool) {
            $embeddings = MeilisearchService::getVectorEmbeddings($tool->paragraphToEmbed);

            // dd($embeddings);

            (new MeilisearchService)->updateDocument(
                SearchAbleTable::TOOL,
                $tool->id,
                [
                    '_vectors' => $embeddings,
                ]
            );

            $tool->update(['vectors' => $embeddings]);

            dump($tool->paragraphToEmbed);
            // dd($response);
            // dd($paragraphToEmbed);
        }
        echo 'done';
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
