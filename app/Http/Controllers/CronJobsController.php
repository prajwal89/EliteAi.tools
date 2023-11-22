<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Support\Facades\Artisan;
use App\Services\TelegramService;

class CronJobsController extends Controller
{
    public function runAllJobs()
    {
        $exitCode = Artisan::call('queue:work --stop-when-empty', []);
        //0 means
        var_dump($exitCode);
    }

    public function sendPromotionalMessage()
    {
        $tool = Tool::inRandomOrder()->whereNull('telegram_promotional_message_data')->first();

        TelegramService::sendPromotionalMessageOfTool($tool);
    }
}
