<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Query to get the count of tools added per day for the last 30 days
        $dailyToolCounts = Tool::whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->orderBy('date', 'asc')
            ->get();

        $columnChartModel = (new ColumnChartModel())
            ->setTitle('Daily tools added')
            ->withoutDataLabels();

        foreach ($dailyToolCounts as $dailyToolCount) {
            $columnChartModel->addColumn($dailyToolCount->date, $dailyToolCount->count, '#f6ad55');
        }

        // dd($columnChartModel);

        return view('admin.dashboard', [
            'columnChartModel' => $columnChartModel
        ]);
    }
}
