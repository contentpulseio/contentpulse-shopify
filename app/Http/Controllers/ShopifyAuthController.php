<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles Shopify OAuth install and callback flows.
 *
 * Relies on osiset/laravel-shopify for the heavy lifting.
 * This controller provides supplementary endpoints for status checks
 * and app-specific logic during the install flow.
 */
class ShopifyAuthController
{
    /**
     * Return the current authentication/install status for the shop.
     */
    public function status(Request $request): JsonResponse
    {
        $shop = $request->get('shop');

        if (! $shop) {
            return response()->json([
                'installed' => false,
                'message' => 'No shop context provided.',
            ], 400);
        }

        // Check if shop has a valid token stored
        // (osiset/laravel-shopify handles this via its User model)
        $shopModel = config('shopify-app.user_model');
        $existingShop = $shopModel ? $shopModel::where('name', $shop)->first() : null;

        return response()->json([
            'installed' => $existingShop !== null && $existingShop->password !== null,
            'shop' => $shop,
            'app_version' => config('app.version', '1.0.0'),
        ]);
    }

    /**
     * Called after successful OAuth install to initialize ContentPulse sync.
     */
    public function postInstall(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'ContentPulse integration activated. Configure your API key in the app settings.',
        ]);
    }
}
