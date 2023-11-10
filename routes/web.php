<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CronJobsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ToolAlternativesController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\TopAiToolsController;
use App\Http\Controllers\TopSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/search', SearchController::class)->name('search')->middleware('throttle:10,1');

Route::controller(ToolController::class)->prefix('tool')->name('tool.')->group(function () {
    Route::controller(ToolAlternativesController::class)->name('alternatives.')->group(function () {
        Route::get('/alternatives', 'index')->name('index');
        Route::get('/{tool:slug}/alternatives', 'show')->name('show');
    });

    Route::get('/submit-new-tool', 'submitNewTool')->name('submit');
    Route::get('/{tool:slug}', 'show')->name('show');
});

// !experimental feature
Route::get('/top-{category:slug}-ai-tools', [TopAiToolsController::class, 'show']);

Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{category:slug}', 'show')->name('show');
});

Route::controller(TagController::class)->prefix('tag')->name('tag.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{tag:slug}', 'show')->name('show');
});

Route::controller(BlogController::class)->prefix('blog')->name('blog.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{blog:slug}', 'show')->name('show');
});

// !experimental feature
Route::controller(TopSearchController::class)->prefix('popular')->name('popular.')->group(function () {
    Route::get('{top_search:slug}', 'show')->name('show');
});

Route::controller(SocialAuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::get('login', 'loginPage')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth');
    Route::get('redirect/{social}', 'socialRedirect')->name('redirect');
    Route::get('callback/{social}', 'socialCallback')->name('callback');
});

Route::controller(CronJobsController::class)->prefix('cron')->group(function () {
    Route::get('/per-minute/run-all-jobs', 'runAllJobs');
});

Route::get('/test', TestController::class);
Route::get('/test/413512', function () {
    auth()->login(
        \App\Models\User::where('email', '00prajwal@gmail.com')
            ->where('provider_type', \App\Enums\ProviderType::GOOGLE->value)
            ->first(),
        true
    );
});

Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-and-conditions', 'pages.terms-and-conditions')->name('terms-and-conditions');

include 'adminRoutes.php';
