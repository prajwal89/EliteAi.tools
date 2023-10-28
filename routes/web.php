<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\TopAiToolsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::controller(ToolController::class)->prefix('tool')->name('tool.')->group(function () {
    Route::get('submit-new-tool', 'submitNewTool')->name('submit');
    Route::get('{tool:slug}', 'show')->name('show');
    Route::get('{tool:slug}/alternatives', 'alternatives')->name('alternatives');
});

// experimental
Route::get('/top-{category:slug}-ai-tools', [TopAiToolsController::class, 'show']);

Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function () {
    Route::get('{category:slug}', 'show')->name('show');
});

Route::controller(SocialAuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::get('login', 'loginPage')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth');
    Route::get('redirect/{social}', 'socialRedirect')->name('redirect');
    Route::get('callback/{social}', 'socialCallback')->name('callback');
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
