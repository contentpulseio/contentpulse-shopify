<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Tests\Unit\Bridge;

use ContentPulse\Core\DTO\ContentItem;
use ContentPulse\Publishing\PublishPayloadBuilder;
use ContentPulse\Rendering\HtmlRenderer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PayloadMappingTest extends TestCase
{
    #[Test]
    public function it_maps_contentpulse_item_to_shopify_payload(): void
    {
        $builder = new PublishPayloadBuilder(new HtmlRenderer);

        $item = ContentItem::fromApiResponse([
            'id' => 42,
            'slug' => 'test-shopify-article',
            'title' => 'Test Shopify Article',
            'body' => [
                ['type' => 'heading', 'content' => 'Introduction', 'attributes' => ['level' => 2]],
                ['type' => 'paragraph', 'content' => 'Welcome to our guide.'],
            ],
            'excerpt' => 'A quick guide.',
            'featured_image' => 'https://example.com/image.jpg',
            'status' => 'published',
            'tags' => [['name' => 'guide'], ['name' => 'shopify']],
        ]);

        $payload = $builder->buildForShopify($item);

        $this->assertSame(42, $payload['contentpulse_id']);
        $this->assertSame('Test Shopify Article', $payload['title']);
        $this->assertSame('test-shopify-article', $payload['slug']);
        $this->assertTrue($payload['published']);
        $this->assertStringContainsString('<h2', $payload['body_html']);
        $this->assertStringContainsString('Welcome to our guide.', $payload['body_html']);
    }

    #[Test]
    public function it_sets_published_false_for_draft_content(): void
    {
        $builder = new PublishPayloadBuilder(new HtmlRenderer);

        $item = ContentItem::fromApiResponse([
            'id' => 1,
            'slug' => 'draft',
            'title' => 'Draft',
            'body' => [],
            'status' => 'draft',
        ]);

        $payload = $builder->buildForShopify($item);

        $this->assertFalse($payload['published']);
    }
}
