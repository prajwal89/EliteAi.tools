<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Artisan;

class CronJobsController extends Controller
{
    public function runAllJobs()
    {
        // https://laravel.com/docs/10.x/queues
        // --max-time=3600
        // --stop-when-empty

        // --max-time=50  // run every worker for 50 seconds only
        // as cron job will invoke new worker every minute and we do not require concurrent jobs
        //? we can run low priority jobs concurrently

        // $exitCode = Artisan::call('queue:work --queue=high,default,low --max-time=50', []);

        $exitCode = Artisan::call('queue:work', [
            '--queue' => 'high,default,low',
            '--max-time' => '50',
            '--stop-when-empty' => true,
        ]);

        return $exitCode;
    }

    public function sendPromotionalMessage()
    {
        $tool = Tool::inRandomOrder()->whereNull('telegram_promotional_message_data')->first();

        TelegramService::sendPromotionalMessageOfTool($tool);
    }
}
