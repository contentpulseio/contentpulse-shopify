<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Services;

use ContentPulse\Core\DTO\ContentFilters;
use ContentPulse\Core\DTO\ContentItem;
use ContentPulse\Http\ContentPulseClient;
use ContentPulse\Publishing\PublishPayloadBuilder;
use ContentPulse\Rendering\HtmlRenderer;
use ContentPulse\Shopify\Services\Shopify\ArticleAdapter;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * The bridge between ContentPulse SDK and Shopify.
 *
 * Fetches content from the ContentPulse API, transforms it using the SDK,
 * and pushes it to Shopify via the ArticleAdapter.
 */
class ContentPulseBridge
{
    private ContentPulseClient $client;

    private PublishPayloadBuilder $payloadBuilder;

    private LoggerInterface $logger;

    public function __construct(
        private readonly ArticleAdapter $articleAdapter,
        string $apiUrl,
        string $apiKey,
        ?LoggerInterface $logger = null,
    ) {
        $this->client = new ContentPulseClient($apiUrl, $apiKey);
        $this->payloadBuilder = new PublishPayloadBuilder(new HtmlRenderer);
        $this->logger = $logger ?? new NullLogger;
    }

    /**
     * Sync all published content from ContentPulse to a Shopify store.
     *
     * @return array{synced: int, errors: array<string>}
     */
    public function syncAllContent(string $shopDomain): array
    {
        $synced = 0;
        $errors = [];
        $page = 1;

        do {
            $feed = $this->client->getContentFeed(new ContentFilters(
                page: $page,
                perPage: 20,
                status: 'published',
            ));

            foreach ($feed->items as $item) {
                try {
                    $this->publishToShopify($shopDomain, $item);
                    $synced++;
                } catch (\Throwable $e) {
                    $errors[] = "Content #{$item->id}: {$e->getMessage()}";
                    $this->logger->error('ContentPulse Shopify sync failed for item', [
                        'content_id' => $item->id,
                        'shop' => $shopDomain,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $page++;
        } while ($feed->hasMorePages());

        return ['synced' => $synced, 'errors' => $errors];
    }

    /**
     * Sync a single content item by ID.
     *
     * @return array{synced: int, errors: array<string>}
     */
    public function syncSingleContent(string $shopDomain, int $contentId): array
    {
        try {
            $item = $this->client->getContentById($contentId);
            $this->publishToShopify($shopDomain, $item);

            return ['synced' => 1, 'errors' => []];
        } catch (\Throwable $e) {
            return ['synced' => 0, 'errors' => [$e->getMessage()]];
        }
    }

    /**
     * Clean up when a shop uninstalls the app.
     */
    public function handleShopUninstall(string $shopDomain): void
    {
        $this->logger->info('Cleaning up ContentPulse sync data for uninstalled shop', [
            'shop' => $shopDomain,
        ]);
    }

    private function publishToShopify(string $shopDomain, ContentItem $item): void
    {
        $payload = $this->payloadBuilder->buildForShopify($item);

        $this->articleAdapter->upsertArticle($shopDomain, $payload);
    }
}
