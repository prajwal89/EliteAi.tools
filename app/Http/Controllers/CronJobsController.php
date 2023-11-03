<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronJobsController extends Controller
{
    public function runAllJobs()
    {
        $exitCode = Artisan::call('queue:work --stop-when-empty', []);
        //0 means
        var_dump($exitCode);
    }
}
