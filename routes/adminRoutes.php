<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\ToolsToProcessController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminAccess;
use Illuminate\Support\Facades\Route;

Route::prefix(config('custom.admin_panel_base_url'))->name('admin.')->middleware(AdminAccess::class)->group(function () {

    Route::match(['get', 'post'], 'tools/import', [ToolController::class, 'import'])->name('tools.import');

    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tools', ToolController::class);
    Route::resource('tools-to-process', ToolsToProcessController::class);
});
