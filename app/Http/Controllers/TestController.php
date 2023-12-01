<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600); //10 min

use App\Enums\ModelType;
use App\Enums\SearchAbleTable;
use App\Jobs\SaveVectorEmbeddingsJob;
use App\Models\Tool;
use App\Services\BlogService;
use App\Services\MeilisearchService;
use App\Services\TelegramService;
use App\Services\ToolService;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use kornrunner\Blurhash\Blurhash;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Api;

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
        BlogService::qualifiedForIndexingBlogIds();

        return 'd';
        dd(ToolService::syncAllEmbeddings(Tool::find(1)));

        dd(dispatch(new SaveVectorEmbeddingsJob(Tool::find(1)))->onQueue('high'));
        $result = (new MeilisearchService())->multiSearch([
            'tools' => [
                'searchableTable' => SearchAbleTable::TOOL,
                'query' => 'ai',
                'searchParams' => [],
                'options' => [],
            ],
            'blogs' => [
                'searchableTable' => SearchAbleTable::BLOG,
                'query' => 'text to speech',
                'searchParams' => [],
                'options' => [],
            ],
        ]);

        dd($result);

        dd(ModelType::OPEN_AI_ADA_002->totalVectorDimensions());
        dd((new MeilisearchService)->getVectorEmbeddings('ss', ModelType::OPEN_AI_ADA_002));

        // return $this->binanceData();
        // return TelegramService::sendPromotionalMessageOfTool(Tool::find(1));

        dd(Tool::find(1)->getParagraphForVectorEmbeddings());
    }

    public function binanceData()
    {
        $baseUrl = 'https://api.binance.com';
        $apiKey = '';
        $apiSecret = '';

        // Make a request to Binance API to get user details
        $endpoint = '/api/v3/account';
        $endpoint = '/sapi/v1/asset/wallet/balance';
        // $endpoint = '/sapi/v1/account/apiRestrictions';
        $timestamp = Http::get($baseUrl . '/api/v3/time')->json()['serverTime']; // Get Binance server time
        $recvWindow = 5000; // Adjust this value based on Binance recommendations

        $signature = hash_hmac('sha256', "timestamp={$timestamp}&recvWindow={$recvWindow}", $apiSecret);

        $response = Http::withHeaders([
            'X-MBX-APIKEY' => $apiKey,
        ])->get($baseUrl . $endpoint, [
            'timestamp' => $timestamp,
            'signature' => $signature,
            'recvWindow' => $recvWindow,
        ]);

        $data = $response->json();

        dd($data);
        // Process $data as needed

        return response()->json($data);
    }

    public function updateEmbeddingsOfAllTools()
    {
        foreach (Tool::offset(0)->limit(20)->get() as $tool) {
            $results = ToolService::updateVectorEmbeddings($tool);
            // dd($results);
        }
        dump('done upto 20');

        foreach (Tool::offset(20)->limit(20)->get() as $tool) {
            $results = ToolService::updateVectorEmbeddings($tool);
            // dd($results);
        }
        dump('done upto 40');

        foreach (Tool::offset(40)->limit(20)->get() as $tool) {
            $results = ToolService::updateVectorEmbeddings($tool);
            // dd($results);
        }
        dump('done upto 60');
    }

    public function saveSemanticDistanceForAllTools()
    {
        foreach (Tool::offset(0)->limit(20)->get() as $tool) {
            $results = ToolService::saveSemanticDistanceBetweenToolAndTools($tool);
            // dd($results);
        }
        dump('done upto 20');

        foreach (Tool::offset(20)->limit(20)->get() as $tool) {
            $results = ToolService::saveSemanticDistanceBetweenToolAndTools($tool);
            // dd($results);
        }
        dump('done upto 40');

        foreach (Tool::offset(40)->limit(20)->get() as $tool) {
            $results = ToolService::saveSemanticDistanceBetweenToolAndTools($tool);
            // dd($results);
        }
        dump('done upto 60');
    }

    public function openAiEmbeddings()
    {
        dd((new MeilisearchService)->getVectorEmbeddings(
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
            $embeddings = (new MeilisearchService)->getVectorEmbeddings(
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
