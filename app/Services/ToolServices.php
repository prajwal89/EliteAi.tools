<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Models\Tool;
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

        return (new MeilisearchService)->updateDocument(
            table: SearchAbleTable::TOOL,
            documentId: $tool->id,
            newData: [
                '_vectors' => $embeddings,
            ]
        );
    }
}
