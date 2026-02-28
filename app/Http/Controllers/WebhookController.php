<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Http\Controllers;

use ContentPulse\Shopify\Services\ContentPulseBridge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Handles incoming Shopify webhooks.
 *
 * osiset/laravel-shopify validates webhook HMAC signatures automatically
 * via its middleware. This controller processes the webhook payloads.
 */
class WebhookController
{
    public function __construct(
        private readonly ContentPulseBridge $bridge,
    ) {}

    /**
     * Handle app/uninstalled webhook — clean up shop data.
     */
    public function appUninstalled(Request $request): JsonResponse
    {
        $shopDomain = $request->header('X-Shopify-Shop-Domain', '');

        Log::info('Shopify app uninstalled', ['shop' => $shopDomain]);

        // Clean up shop-specific ContentPulse sync data
        $this->bridge->handleShopUninstall($shopDomain);

        return response()->json(['received' => true]);
    }

    /**
     * Handle products/update webhook — optional product content sync.
     */
    public function productsUpdate(Request $request): JsonResponse
    {
        $payload = $request->all();
        $shopDomain = $request->header('X-Shopify-Shop-Domain', '');

        Log::info('Shopify product updated', [
            'shop' => $shopDomain,
            'product_id' => $payload['id'] ?? null,
        ]);

        return response()->json(['received' => true]);
    }

    /**
     * Handle blog articles/create and articles/update webhooks.
     */
    public function articlesSync(Request $request): JsonResponse
    {
        $payload = $request->all();
        $shopDomain = $request->header('X-Shopify-Shop-Domain', '');

        Log::info('Shopify article event received', [
            'shop' => $shopDomain,
            'article_id' => $payload['id'] ?? null,
        ]);

        return response()->json(['received' => true]);
    }
}
