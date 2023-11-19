<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\ToolImportController;
use App\Http\Controllers\Admin\ToolsToProcessController;
use App\Http\Controllers\Admin\TopSearchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MeilisearchControlPanelController;
use App\Http\Middleware\AdminAccess;
use Illuminate\Support\Facades\Route;

Route::prefix(config('custom.admin_panel_base_url'))->name('admin.')->middleware(AdminAccess::class)->group(function () {

    Route::get('tools/import', [ToolImportController::class, 'importForm'])->name('tools.import');
    Route::post('tools/import', [ToolImportController::class, 'import'])->name('tools.importGo');
    // we can improve this
    Route::get('tools-to-process/do-not-process/{tool_to_process_id}', [ToolsToProcessController::class, 'doNotProcess'])
        ->name('tools-to-process.do-not-process');

    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tools', ToolController::class);
    Route::resource('tools-to-process', ToolsToProcessController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('tags', TagController::class);
    Route::resource('top-searches', TopSearchController::class);

    Route::get('/meilisearch', MeilisearchControlPanelController::class)->name('meilisearch.control-panel');
});
