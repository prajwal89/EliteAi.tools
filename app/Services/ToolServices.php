<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ToolServices
{
    public static function storeScreenShot(UploadedFile $imageFile, string $slug): bool
    {
        $toolAssetPath = public_path('tool/' . $slug);

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
}
