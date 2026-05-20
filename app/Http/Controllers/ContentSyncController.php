<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Http\Controllers;

use ContentPulse\Shopify\Services\ContentPulseBridge;
use ContentPulse\Shopify\Services\Shopify\ArticleAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * API endpoints for syncing ContentPulse content to Shopify stores.
 */
class ContentSyncController
{
    public function __construct(
        private readonly ContentPulseBridge $bridge,
        private readonly ArticleAdapter $articleAdapter,
    ) {}

    /**
     * Pull latest content from ContentPulse and sync to Shopify.
     */
    public function sync(Request $request): JsonResponse
    {
        $request->validate([
            'shop' => 'required|string',
            'content_id' => 'nullable|string|min:1|max:64',
        ]);

        $shop = (string) $request->input('shop');

        try {
            if ($request->filled('content_id')) {
                $result = $this->bridge->syncSingleContent(
                    $shop,
                    (string) $request->input('content_id'),
                );
            } else {
                $result = $this->bridge->syncAllContent($shop);
            }

            return response()->json([
                'success' => true,
                'synced' => $result['synced'] ?? 0,
                'errors' => $result['errors'] ?? [],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return the current sync status for a shop.
     */
    public function status(Request $request): JsonResponse
    {
        $shop = $request->input('shop', '');

        return response()->json([
            'shop' => $shop,
            'connected' => ! empty(config('contentpulse.api_key')),
            'last_sync' => null,
            'total_synced' => 0,
        ]);
    }
}
