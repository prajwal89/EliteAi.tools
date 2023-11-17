<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Models\SemanticScore;
use App\Models\Tool;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ToolServices
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

        $tool->update([
            '_vectors' => $embeddings,
            'model_type' => config('custom.current_embedding_model')->value,
        ]);

        // todo check if document id available try 2 or 3 times before throwing error
        $retryCount = 3; // Number of times to retry

        while ($retryCount > 0) {
            try {
                return (new MeilisearchService)->updateDocument(
                    table: SearchAbleTable::TOOL,
                    documentId: $tool->id,
                    newData: [
                        '_vectors' => $embeddings,
                    ]
                );
            } catch (Exception $e) {
                if ($e->getCode() === 404 && $retryCount > 1) {
                    // Document not found, retry
                    $retryCount--;
                    sleep(1); // Optional: You can add a delay between retries
                    continue;
                }

                // If it's not a 404 error or no more retries, rethrow the exception
                throw $e;
            }
        }

        // This point is reached only if all retries fail
        throw new \RuntimeException('Failed to update document after multiple retries.');


        // return (new MeilisearchService)->updateDocument(
        //     table: SearchAbleTable::TOOL,
        //     documentId: $tool->id,
        //     newData: [
        //         '_vectors' => $embeddings,
        //     ]
        // );
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
        if (empty($tool->_vectors) || count($tool->_vectors) < 1) {
            // we need to calculated b.c this will run multiple times
            throw new Exception('Vectors are not calculated for tool: ' . $tool->id);
        }
        // todo we can send already calculated vectors here
        // todo move this function in service class
        $results = MeilisearchService::vectorSearch(
            table: SearchAbleTable::TOOL,
            query: $tool->getParagraphForVectorEmbeddings(),
            configs: [
                'limit' => $toolsLimit,
                'attributesToRetrieve' => ['id', 'name'],
            ]
        );

        foreach ($results['hits'] as $hit) {
            // *same tool this will always result in 1.00000000
            if ($tool->id == $hit['id']) {
                continue;
            }

            SemanticScore::updateOrCreate([
                'tool1_id' => min($tool->id, $hit['id']),
                'tool2_id' => max($tool->id, $hit['id']),
            ], [
                'score' => $hit['_semanticScore'],
                'model_type' => config('custom.current_embedding_model')->value,
            ]);
        }

        return $results;
    }
}
