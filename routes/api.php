<?php

declare(strict_types=1);

use ContentPulse\Shopify\Http\Controllers\ContentSyncController;
use ContentPulse\Shopify\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::post('/sync', [ContentSyncController::class, 'sync']);
    Route::get('/sync/status', [ContentSyncController::class, 'status']);
});

Route::prefix('webhooks')->group(function () {
    Route::post('/app-uninstalled', [WebhookController::class, 'appUninstalled']);
    Route::post('/products-update', [WebhookController::class, 'productsUpdate']);
    Route::post('/articles-sync', [WebhookController::class, 'articlesSync']);
});
