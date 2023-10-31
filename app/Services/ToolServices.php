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

        $newImage = Image::make($imageFile);

        $newImage->resize(1280, 1000, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toolAssetPath . '/screenshot.webp');

        $toolData['uploaded_screenshot'] = now();

        $newImage->resize(400, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toolAssetPath . '/screenshot-large.webp');

        $newImage->resize(200, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toolAssetPath . '/screenshot-medium.webp');

        $newImage->resize(100, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toolAssetPath . '/screenshot-small.webp');

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
        // $googleEndpoint = "https://www.google.com/s2/favicons?domain=$domainName";

        $params['client'] = 'SOCIAL';
        $params['type'] = 'FAVICON';
        $params['fallback_opts'] = 'TYPE,SIZE,URL';
        $params['url'] = $tool->home_page_url;
        $params['size'] = '128';

        $googleEndpoint = 'https://t0.gstatic.com/faviconV2';
        $googleEndpoint .= '?' . http_build_query($params);

        $toolAssetPath = public_path('tools/' . $tool->slug);

        if (!File::isDirectory($toolAssetPath)) {
            File::makeDirectory($toolAssetPath, 0755, true, true);
        }

        $newImage = Image::make($googleEndpoint);

        $newImage->resize(100, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($toolAssetPath . '/favicon.webp');

        return true;
    }

    public static function updateVectorEmbeddings(int $toolId): bool
    {
        $tool = Tool::find($toolId);

        $embeddings = MeilisearchService::getVectorEmbeddings($tool->paragraphToEmbed);

        $tool->update(['vectors' => $embeddings]);

        return (new MeilisearchService)->updateDocument(SearchAbleTable::TOOL, $tool->id, [
            '_vectors' => $embeddings,
        ]);
    }
}
