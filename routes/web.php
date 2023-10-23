<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::controller(ToolController::class)->prefix('tools')->name('tools.')->group(function () {
    Route::get('{tool:slug}', 'show')->name('show');
});

Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
    Route::get('{category:slug}', 'show')->name('show');
});

Route::controller(SocialAuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::get('login', 'loginPage')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth');
    Route::get('redirect/{social}', 'socialRedirect')->name('redirect');
    Route::get('callback/{social}', 'socialCallback')->name('callback');
});

Route::get('/test', TestController::class);

Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-and-conditions', 'pages.terms-and-conditions')->name('terms-and-conditions');

include 'adminRoutes.php';
