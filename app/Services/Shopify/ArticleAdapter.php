<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Services\Shopify;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Adapts ContentPulse publication payloads into Shopify Blog Article operations.
 *
 * Uses the Shopify REST Admin API to create/update blog articles.
 * In production, inject a configured Shopify API client.
 */
class ArticleAdapter
{
    private LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger;
    }

    /**
     * Create or update a Shopify blog article from a ContentPulse payload.
     *
     * @param  array<string, mixed>  $payload  Output from PublishPayloadBuilder::buildForShopify()
     * @return array{action: string, article_id: int|null}
     */
    public function upsertArticle(string $shopDomain, array $payload): array
    {
        $contentPulseId = $payload['contentpulse_id'] ?? null;
        $existingArticleId = $this->findArticleByContentPulseId($shopDomain, $contentPulseId);

        $articleData = [
            'title' => $payload['title'] ?? '',
            'body_html' => $payload['body_html'] ?? '',
            'handle' => $payload['slug'] ?? '',
            'summary_html' => $payload['excerpt'] ?? '',
            'published' => $payload['published'] ?? false,
            'tags' => $this->formatTags($payload['tags'] ?? []),
        ];

        if (! empty($payload['featured_image'])) {
            $articleData['image'] = [
                'src' => $payload['featured_image'],
                'alt' => $payload['title'] ?? '',
            ];
        }

        if (! empty($payload['published_at'])) {
            $articleData['published_at'] = $payload['published_at'];
        }

        if ($existingArticleId) {
            $this->updateArticle($shopDomain, $existingArticleId, $articleData);

            return ['action' => 'updated', 'article_id' => $existingArticleId];
        }

        $articleId = $this->createArticle($shopDomain, $articleData);

        if ($articleId && $contentPulseId) {
            $this->storeContentPulseMapping($shopDomain, $articleId, $contentPulseId);
        }

        return ['action' => 'created', 'article_id' => $articleId];
    }

    /**
     * Delete a Shopify article.
     */
    public function deleteArticle(string $shopDomain, int $articleId): bool
    {
        $this->logger->info('Deleting Shopify article', [
            'shop' => $shopDomain,
            'article_id' => $articleId,
        ]);

        // Placeholder: Use Shopify API to delete
        return true;
    }

    private function findArticleByContentPulseId(string $shopDomain, int|string|null $contentPulseId): ?int
    {
        if ($contentPulseId === null) {
            return null;
        }

        // Placeholder: Query Shopify metafields to find matching article
        return null;
    }

    private function createArticle(string $shopDomain, array $articleData): ?int
    {
        $this->logger->info('Creating Shopify article', [
            'shop' => $shopDomain,
            'title' => $articleData['title'],
        ]);

        // Placeholder: Use Shopify REST/GraphQL API to create article
        return null;
    }

    private function updateArticle(string $shopDomain, int $articleId, array $articleData): void
    {
        $this->logger->info('Updating Shopify article', [
            'shop' => $shopDomain,
            'article_id' => $articleId,
        ]);

        // Placeholder: Use Shopify REST/GraphQL API to update article
    }

    private function storeContentPulseMapping(string $shopDomain, int $articleId, int|string $contentPulseId): void
    {
        $this->logger->info('Storing ContentPulse mapping', [
            'shop' => $shopDomain,
            'article_id' => $articleId,
            'contentpulse_id' => $contentPulseId,
        ]);

        // Placeholder: Store mapping as Shopify metafield on the article
    }

    /**
     * @param  array<mixed>  $tags
     */
    private function formatTags(array $tags): string
    {
        $tagNames = array_map(
            fn ($tag) => is_array($tag) ? ($tag['name'] ?? '') : (string) $tag,
            $tags,
        );

        return implode(', ', array_filter($tagNames));
    }
}
