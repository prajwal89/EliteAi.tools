<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Models\Blog;
use App\Models\Tool;
use App\Models\TopSearch;
use App\Services\BlogService;
use App\Services\MeilisearchService;
use App\Services\RecommendationService;
use App\Services\SocialMediaHandlesExtractor;
use App\Services\ToolServices;
use App\Services\TopSearchService;
use App\Services\WebPageFetcher;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use kornrunner\Blurhash\Blurhash;
use OpenAI\Laravel\Facades\OpenAI;
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
        // dd(
        //     BlogService::saveSemanticDistanceBetweenBlogAndTools(Blog::find(1))
        // );
        // dd($this->totalCombos(132));

        MeilisearchService::deIndexTable(SearchAbleTable::TOOL);
        MeilisearchService::indexAllDocumentsOfTable(SearchAbleTable::TOOL);

        TopSearch::all()->map(function ($topSearch) {
            TopSearchService::saveSemanticDistanceBetweenTopSearchAndTools($topSearch);
            dump($topSearch);
        });

        exit('sdsf');



        $html = (new WebPageFetcher('https://www.pixelfy.ai/'))->get()->content;

        // todo match all social media handles urls
        $dom = HtmlDomParser::str_get_html($html);


        $handle = (new SocialMediaHandlesExtractor($dom))->extractSocialHandles();

        dd($handle);

        dd((new MeilisearchService())->search(SearchAbleTable::TOOL, 'Chat with pdf'));

        dd(MeilisearchService::fulltextSearch(SearchAbleTable::TOOL, 'Chat with the pdf'));
        // dd(MeilisearchService::deIndexTable(SearchAbleTable::TOOL));

        // dd(MeilisearchService::indexAllDocumentsOfTable(SearchAbleTable::TOOL));

        return $this->saveSemanticDistanceForAllTools();
        // return $this->updateEmbeddingsOfAllTools();

        dump(BlogService::updateVectorEmbeddings(Blog::find(1)));
        dd(BlogService::saveSemanticDistanceBetweenBlogAndTools(Blog::find(1)));

        // dd(Tool::documentsForSearch(1));
        // dd(MeilisearchService::deIndexTable(SearchAbleTable::TOOL));

        dd(MeilisearchService::getVectorEmbeddings(
            'ds',
            ModelType::All_MINI_LM_L6_V2
        ));
        // return $this->blurHash();
        dd(estimateTokenUsage('You miss 100% of the shots you don\'t take'));

        // return $this->loginSuperAdmin();
    }

    public function updateEmbeddingsOfAllTools()
    {
        foreach (Tool::offset(0)->limit(20)->get() as $tool) {
            $results = ToolServices::updateVectorEmbeddings($tool);
            // dd($results);
        }
        dump('done upto 20');

        foreach (Tool::offset(20)->limit(20)->get() as $tool) {
            $results = ToolServices::updateVectorEmbeddings($tool);
            // dd($results);
        }
        dump('done upto 40');

        foreach (Tool::offset(40)->limit(20)->get() as $tool) {
            $results = ToolServices::updateVectorEmbeddings($tool);
            // dd($results);
        }
        dump('done upto 60');
    }

    public function saveSemanticDistanceForAllTools()
    {
        foreach (Tool::offset(0)->limit(20)->get() as $tool) {
            $results = RecommendationService::saveSemanticDistanceFor($tool);
            // dd($results);
        }
        dump('done upto 20');

        foreach (Tool::offset(20)->limit(20)->get() as $tool) {
            $results = RecommendationService::saveSemanticDistanceFor($tool);
            // dd($results);
        }
        dump('done upto 40');

        foreach (Tool::offset(40)->limit(20)->get() as $tool) {
            $results = RecommendationService::saveSemanticDistanceFor($tool);
            // dd($results);
        }
        dump('done upto 60');
    }

    public function openAiEmbeddings()
    {
        dd(MeilisearchService::getVectorEmbeddings(
            Tool::find(1)->getParagraphForVectorEmbeddings(),
            ModelType::OPEN_AI_ADA_002
        ));

        $start_time = microtime(true);  // Record the start time

        // Make the API request
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => 'The food was delicious and the waiter...',
        ]);

        $end_time = microtime(true);  // Record the end time
        $elapsed_time = $end_time - $start_time;  // Calculate the elapsed time in seconds

        echo 'API request took ' . round($elapsed_time, 2) . ' seconds.';

        dd($response);
    }

    public function blurHash()
    {
        // L26a@nDj00.R0D.5-gDj~kVZA2l6
        $blurhash = 'LEHV6nWB2yk8pyo0adR*.7kCMdnj';
        $width = 269;
        $height = 173;

        $pixels = Blurhash::decode($blurhash, $width, $height);
        $image = imagecreatetruecolor($width, $height);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                [$r, $g, $b] = $pixels[$y][$x];
                imagesetpixel($image, $x, $y, imagecolorallocate($image, $r, $g, $b));
            }
        }

        // Set the appropriate HTTP headers to display the image
        header('Content-Type: image/png');

        // Output the image
        return imagejpeg($image);

        // Free up memory
        imagedestroy($image);
        // dump($img);

        // $tool = Tool::inRandomOrder()->first();
        // $file  = asset('/tools/' . $tool->slug . '/screenshot.webp');
        // $image = Image::make($file);
        // $width = $image->width();
        // $height = $image->height();

        // $pixels = [];
        // for ($y = 0; $y < $height; ++$y) {
        //     $row = [];
        //     for ($x = 0; $x < $width; ++$x) {
        //         $colors = $image->pickColor($x, $y);

        //         $row[] = [$colors[0], $colors[1], $colors[2]];
        //     }
        //     $pixels[] = $row;
        // }

        // $components_x = 4;
        // $components_y = 3;
        // $blurhash = Blurhash::encode($pixels, $components_x, $components_y);

        // dump($blurhash);
    }

    public function totalCombos($max = 57)
    {
        $min = 1;
        $count = 0;

        for ($num1 = $min; $num1 <= $max; $num1++) {
            for ($num2 = $num1 + 1; $num2 <= $max; $num2++) {
                // Check if the pair has different numbers
                if ($num1 !== $num2) {
                    $count++;
                }
            }
        }

        dd('Total count of pairs: ' . $count);
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
            $embeddings = MeilisearchService::getVectorEmbeddings(
                $tool->getParagraphForVectorEmbeddings(),
                ModelType::All_MINI_LM_L6_V2
            );

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
