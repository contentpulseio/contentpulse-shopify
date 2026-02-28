<?php

declare(strict_types=1);

use ContentPulse\Shopify\Http\Controllers\ShopifyAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| osiset/laravel-shopify registers its own install/callback routes.
| These routes provide supplementary endpoints for the app.
|
*/

Route::get('/auth/status', [ShopifyAuthController::class, 'status']);
Route::post('/auth/post-install', [ShopifyAuthController::class, 'postInstall']);

Route::get('/', function () {
    return view('welcome');
});
