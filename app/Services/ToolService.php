<?php

namespace App\Services;

use App\Enums\BlogType;
use App\Enums\SearchAbleTable;
use App\Jobs\SaveSemanticDistanceBetweenBlogAndToolJob;
use App\Jobs\SaveSemanticDistanceBetweenToolAndToolJob;
use App\Jobs\SaveSemanticDistanceBetweenTopSearchAndToolJob;
use App\Jobs\SaveVectorEmbeddingsJob;
use App\Models\Blog;
use App\Models\SemanticScore;
use App\Models\Tool;
use App\Models\TopSearch;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ToolService
{
    public static function storeScreenShot(UploadedFile $imageFile, string $slug): bool
    {
        $toolAssetPath = public_path('tools/' . $slug);

        if (!File::isDirectory($toolAssetPath)) {
            File::makeDirectory($toolAssetPath, 0755, true, true);
        }

        $originalImage = Image::make($imageFile);

        // Save the original screenshot
        $originalImage->save($toolAssetPath . '/original.png');

        // Resize and save screenshots in different sizes
        $sizes = [
            ['width' => 1280, 'height' => 1000, 'suffix' => 'screenshot'],
            ['width' => 600, 'height' => 400, 'suffix' => 'screenshot-large'],
            ['width' => 400, 'height' => 400, 'suffix' => 'screenshot-medium'],
            ['width' => 100, 'height' => 400, 'suffix' => 'screenshot-small'],
        ];

        foreach ($sizes as $size) {
            $originalImage->resize($size['width'], $size['height'], function ($constraint) {
                $constraint->aspectRatio();
            })->save($toolAssetPath . '/' . $size['suffix'] . '.webp');
        }

        return true;
    }

    public static function storeFavicon(UploadedFile $imageFile, string $slug): bool
    {
        $toolAssetPath = public_path('tools/' . $slug);

        if (!File::isDirectory($toolAssetPath)) {
            File::makeDirectory($toolAssetPath, 0755, true, true);
        }

        $newImage = Image::make($imageFile);

        $newImage->resize(100, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toolAssetPath . '/favicon.webp');

        return true;
    }

    public static function saveFaviconFromGoogle(Tool $tool): bool
    {
        $toolAssetPath = public_path('tools/' . $tool->slug);

        if (!File::isDirectory($toolAssetPath)) {
            File::makeDirectory($toolAssetPath, 0755, true, true);
        }

        $newImage = Image::make(getGoogleThumbnailUrl($tool->home_page_url));

        $newImage->resize(100, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toolAssetPath . '/favicon.webp');

        return true;
    }

    /**
     * updates in DB and Meilisearch
     */
    public static function updateVectorEmbeddings(Tool $tool): bool
    {
        $embeddings = MeilisearchService::getVectorEmbeddings(
            text: $tool->getParagraphForVectorEmbeddings(),
            modelType: config('custom.current_embedding_model')
        );

        $vectorData = [
            '_vectors' => $embeddings,
            'model_type' => config('custom.current_embedding_model')->value,
        ];

        $tool->update($vectorData);

        return (new MeilisearchService)->updateDocument(
            table: SearchAbleTable::TOOL,
            documentId: $tool->id,
            newData: $vectorData
        );
    }

    /**
     * Save distance of $toolId relative to all other tools
     *
     * @return void
     */
    public static function saveSemanticDistanceBetweenToolAndTools(
        Tool $tool,
        int $toolsLimit = 500,
    ) {
        $configs = [
            'limit' => $toolsLimit,
            'attributesToRetrieve' => ['id', 'name'],
        ];

        if (empty($tool->_vectors) || count($tool->_vectors) < 1) {
            // !we need to calculated b.c this will run multiple times (confirm this)

            if (!app()->isProduction()) {
                throw new Exception('Vectors are not calculated for tool: ' . $tool->id);
            }

            // !this can be expensive if executed multiple times
            $searchResults = MeilisearchService::vectorSearch(
                table: SearchAbleTable::TOOL,
                query: $tool->getParagraphForVectorEmbeddings(),
                // vectors: $tool->_vectors,
                configs: $configs
            );
        } else {

            $searchResults = MeilisearchService::vectorSearch(
                table: SearchAbleTable::TOOL,
                // query: $tool->getParagraphForVectorEmbeddings(),
                vectors: $tool->_vectors,
                configs: $configs
            );
        }

        if (empty($searchResults)) {
            throw new Exception('Did not get search results for: ' . $tool->id);
        }

        foreach ($searchResults->hits as $hitTool) {
            // *same tool this will always result in 1.00000000
            if ($tool->id == $hitTool['id']) {
                continue;
            }

            SemanticScore::updateOrCreate([
                'tool1_id' => min($tool->id, $hitTool['id']),
                'tool2_id' => max($tool->id, $hitTool['id']),
            ], [
                'score' => $hitTool['_semanticScore'],
                'model_type' => config('custom.current_embedding_model')->value,
            ]);
        }

        return true;
    }

    /**
     * When tool updated or created run this
     * this will recalculate all embeddings
     * and assign this tool to appropriate pages
     */
    // ? should i move this to observer in model
    public static function syncAllEmbeddings(Tool $tool): bool
    {
        dispatch(function () use ($tool) {
            // *this will calculate vector embeddings
            dispatch(new SaveVectorEmbeddingsJob($tool))->delay(now()->addMinutes(3));

            dispatch(new SaveSemanticDistanceBetweenToolAndToolJob($tool))->delay(now()->addMinutes(5));

            Blog::where('blog_type', BlogType::SEMANTIC_SCORE->value)->get()->map(function ($blog) {
                dispatch(new SaveSemanticDistanceBetweenBlogAndToolJob($blog))->delay(now()->addMinutes(7));
            });

            // todo optimize this
            TopSearch::get()->map(function ($topSearch) {
                dispatch(new SaveSemanticDistanceBetweenTopSearchAndToolJob($topSearch))->delay(now()->addMinutes(9));
            });
        });

        return true;
    }
}
