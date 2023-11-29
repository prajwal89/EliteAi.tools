<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Artisan;

class CronJobsController extends Controller
{
    public function runAllJobs()
    {
        $exitCode = Artisan::call('queue:work --stop-when-empty  --queue=high,default,low', []);
        //0 means
        var_dump($exitCode);
    }

    public function sendPromotionalMessage()
    {
        $tool = Tool::inRandomOrder()->whereNull('telegram_promotional_message_data')->first();

        TelegramService::sendPromotionalMessageOfTool($tool);
    }
}
