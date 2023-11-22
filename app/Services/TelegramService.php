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

        // Build caption 
        $caption = '';

        $caption .= '<u><b>' . $tool->name . '</b></u>' . str_repeat(PHP_EOL, 2);
        $caption .= '🤖 <b>' . $tool->tag_line . '</b>' . str_repeat(PHP_EOL, 2);
        $caption .= '📢 ' . $tool->summary . str_repeat(PHP_EOL, 2);

        // Fill in top features
        if (!empty($tool->getFormattedFeatures())) {
            $caption .= '<b>Features:</b>' . PHP_EOL;
            foreach ($tool->getFormattedFeatures() as $feature) {
                $caption .= " - $feature" . PHP_EOL;
            }
            $caption .= str_repeat(PHP_EOL, 2);
        }

        // Fill in use cases
        if (!empty($tool->use_cases)) {
            $caption .= '<b>Use Cases:</b>' . PHP_EOL;
            foreach ($tool->use_cases as $useCase) {
                $caption .= " - $useCase" . PHP_EOL;
            }
            $caption .= str_repeat(PHP_EOL, 2);
        }


        // Include a link in the caption
        $linkUrl = route('tool.show', $tool->slug);
        $linkText = 'View all tool details';
        $caption .= '<a href="' . $linkUrl . '">' . $linkText . '</a>' . str_repeat(PHP_EOL, 3);

        $response =  Telegram::sendPhoto([
            'chat_id' => config('custom.telegram.chat_id'),
            'photo' => new InputFile($imageUrl),
            'caption' => $caption,
            'parse_mode' => 'HTML', //https://core.telegram.org/bots/api#html-style
        ]);

        $tool->update([
            'telegram_promotional_message_data' => $response,
        ]);

        return $response;
    }
}
