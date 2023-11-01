<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\DTOs\ToolDTO;
use App\Enums\SearchAbleTable;
use App\Models\ExtractedToolDomain;
use App\Models\Tool;
use App\Services\DomainNameExtractors;
use App\Services\ExtractedToolProcessor;
use App\Services\MeilisearchService;
use App\Services\RecommendationService;
use App\Services\ToolServices;
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
        DomainNameExtractors::extractsDomainsFromToolify();
        // dd(MeilisearchService::enableVectorSearch());
        // dd(MeilisearchService::indexAllDocumentsOfTable(SearchAbleTable::TOOL));

        // dd(Tool::offset(10)->limit(20)->get());

        // foreach (Tool::offset(0)->limit(10)->get() as $tool) {
        //     dump(RecommendationService::saveSemanticDistanceFor($tool));
        // }

        // dump('done till 10');

        // foreach (Tool::offset(10)->limit(10)->get() as $tool) {
        //     dump(RecommendationService::saveSemanticDistanceFor($tool));
        // }

        // dump('done till 20');

        // foreach (Tool::offset(20)->limit(10)->get() as $tool) {
        //     dump(RecommendationService::saveSemanticDistanceFor($tool));
        // }

        // dump('done till 30');

        // foreach (Tool::offset(30)->limit(10)->get() as $tool) {
        //     dump(RecommendationService::saveSemanticDistanceFor($tool));
        // }

        // dump('done till 40');



        // dd(MeilisearchService::getVectorEmbeddings('sdf'));
        // dd(MeilisearchService::indexAllDocumentsOfTable(SearchAbleTable::TOOL));


        // dump((new MeilisearchService)->meilisearchClient->version());
        // return (new MeilisearchService())->indexAllDocumentsOfTable(SearchAbleTable::TOOL);
        // return (new MeilisearchService())->deIndexTable(SearchAbleTable::TOOL);

        // return $this->vectorSearch();

        // return $this->sendVectorEmbeddingsToMeilisearch();
        // return $this->loginSuperAdmin();

        // return $this->buildToolDto();

        // $prompt = ExtractedToolProcessor::buildSystemPrompt(public_path('/prompts/system-1.txt'));

        // echo nl2br($prompt);

        // (new ExtractedToolProcessor(ExtractedToolDomain::find(1)))->process();

        // $this->insertTools();

        // code...
        // auth()->login(\App\Models\User::find(1));
        // return $this->crawlTopAiTools3();

        // return $this->loginSuperAdmin();
    }


    public function totalCombos()
    {
        $min = 1;
        $max = 39;
        $count = 0;

        for ($num1 = $min; $num1 <= $max; $num1++) {
            for ($num2 = $num1 + 1; $num2 <= $max; $num2++) {
                // Check if the pair has different numbers
                if ($num1 !== $num2) {
                    $count++;
                }
            }
        }

        dd("Total count of pairs: " . $count);
    }

    public function vectorSearch()
    {
        // Start the timer
        $start_time = microtime(true);

        // Call the function you want to measure
        $results = MeilisearchService::vectorSearch(
            SearchAbleTable::TOOL,
            'real time crypto news'
        );

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
            $embeddings = MeilisearchService::getVectorEmbeddings($tool->getParagraphForVectorEmbeddings());

            // dd($embeddings);

            (new MeilisearchService)->updateDocument(
                SearchAbleTable::TOOL,
                $tool->id,
                [
                    '_vectors' => $embeddings,
                ]
            );

            $tool->update(['vectors' => $embeddings]);

            dump($tool->getParagraphForVectorEmbeddings());
            // dd($response);
            // dd($getParagraphForVectorEmbeddings());
        }
        echo 'done';
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
}
