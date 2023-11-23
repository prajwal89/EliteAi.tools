<?php

namespace App\Services;

use App\Models\Tool;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramService
{
    public static function sendPromotionalMessageOfTool(Tool $tool)
    {
        $imageUrl = realpath(public_path('/tools/' . $tool->slug . '/screenshot.webp'));

        // Build caption
        // ! caption length should be below 4096
        // or else it will throws Telegram\\Bot\\Exceptions\\TelegramResponseException(code: 400): Bad Request: message caption is too long at
        $caption = '';

        $caption .= '<u><b>' . $tool->name . '</b></u>' . str_repeat(PHP_EOL, 2);
        $caption .= '🤖 <b>' . $tool->tag_line . '</b>' . str_repeat(PHP_EOL, 2);
        $caption .= '📢 ' . $tool->summary . str_repeat(PHP_EOL, 2);

        // Fill in top features
        if (!empty($tool->getFormattedFeatures())) {
            $caption .= '<b>Features:</b>' . PHP_EOL;
            foreach ($tool->getFormattedFeatures() as $i => $feature) {
                $caption .= " - $feature" . PHP_EOL;
                // show only two list elements
                // ?should i include view all link
                if ($i >= 2) {
                    break;
                }
            }
            $caption .= str_repeat(PHP_EOL, 2);
        }

        // Fill in use cases
        if (!empty($tool->use_cases)) {
            $caption .= '<b>Use Cases:</b>' . PHP_EOL;
            foreach ($tool->use_cases as $i => $useCase) {
                $caption .= " - $useCase" . PHP_EOL;
                if ($i >= 2) {
                    break;
                }
            }
            $caption .= str_repeat(PHP_EOL, 2);
        }

        // Include a link in the caption
        $linkUrl = route('tool.show', $tool->slug);
        $linkText = 'View all tool details';
        $caption .= '<a href="' . $linkUrl . '">' . $linkText . '</a>' . str_repeat(PHP_EOL, 3);

        $response = Telegram::sendPhoto([
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
