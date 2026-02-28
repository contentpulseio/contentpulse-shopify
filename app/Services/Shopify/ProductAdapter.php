<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Services\Shopify;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Adapts ContentPulse content for Shopify product descriptions.
 */
class ProductAdapter
{
    private LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger;
    }

    /**
     * Update a Shopify product's description body with ContentPulse content.
     *
     * @param  array<string, mixed>  $payload  Output from PublishPayloadBuilder::buildForShopify()
     */
    public function updateProductDescription(string $shopDomain, int $productId, array $payload): bool
    {
        $this->logger->info('Updating Shopify product description', [
            'shop' => $shopDomain,
            'product_id' => $productId,
            'contentpulse_id' => $payload['contentpulse_id'] ?? null,
        ]);

        // Placeholder: Use Shopify API to update product body_html
        return true;
    }

    /**
     * Update Shopify product SEO meta fields.
     *
     * @param  array<string, mixed>  $seo
     */
    public function updateProductSeo(string $shopDomain, int $productId, array $seo): bool
    {
        $this->logger->info('Updating Shopify product SEO', [
            'shop' => $shopDomain,
            'product_id' => $productId,
        ]);

        // Placeholder: Use Shopify API to update product SEO metafields
        return true;
    }
}
