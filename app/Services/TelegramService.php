<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;
use App\Models\Tool;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramService
{
    public static function sendPromotionalMessageOfTool(Tool $tool)
    {
        $imageUrl = realpath(public_path('/tools/' . $tool->slug . '/screenshot.webp'));

        // build caption 
        $caption = '';

        // $caption .= $tool->name . PHP_EOL;
        $caption .= $tool->tag_line . str_repeat(PHP_EOL, 3);
        $caption .= $tool->summary . str_repeat(PHP_EOL, 3);

        // Include a link in the caption
        $linkUrl = $tool->home_page_url;
        $linkText = 'Visit website';
        $caption .= '<a href="' . $linkUrl . '">' . $linkText . '</a>' . str_repeat(PHP_EOL, 3);

        $response = Telegram::sendPhoto([
            'chat_id' => config('custom.telegram.chat_id'),
            'photo' => new InputFile($imageUrl),
            'caption' => $caption,
            'parse_mode' => 'HTML', // Specify that the caption contains HTML
        ]);

        dd($response);
    }
}
