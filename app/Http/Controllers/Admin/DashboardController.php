<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $columnChartModel = (new ColumnChartModel())
            ->setTitle('Daily tools added')
            ->withoutDataLabels();

        // Query to get the count of tools added per day for the last 30 days
        Tool::whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->orderBy('date', 'asc')
            ->get()
            ->each(function ($tool) use ($columnChartModel) {
                $columnChartModel->addColumn($tool->date, $tool->count, '#f6ad55');
            });

        return view('admin.dashboard', [
            'columnChartModel' => $columnChartModel,
        ]);
    }
}
