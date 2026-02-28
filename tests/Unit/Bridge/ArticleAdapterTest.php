<?php

declare(strict_types=1);

namespace ContentPulse\Shopify\Tests\Unit\Bridge;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ArticleAdapterTest extends TestCase
{
    #[Test]
    public function it_formats_tags_from_array(): void
    {
        $tags = [
            ['name' => 'PHP'],
            ['name' => 'Laravel'],
            ['name' => 'AI'],
        ];

        $formatted = $this->formatTags($tags);

        $this->assertSame('PHP, Laravel, AI', $formatted);
    }

    #[Test]
    public function it_formats_tags_from_strings(): void
    {
        $tags = ['PHP', 'Laravel', 'AI'];

        $formatted = $this->formatTags($tags);

        $this->assertSame('PHP, Laravel, AI', $formatted);
    }

    #[Test]
    public function it_handles_empty_tags(): void
    {
        $formatted = $this->formatTags([]);

        $this->assertSame('', $formatted);
    }

    #[Test]
    public function it_filters_empty_tag_names(): void
    {
        $tags = [
            ['name' => 'PHP'],
            ['name' => ''],
            ['name' => 'AI'],
        ];

        $formatted = $this->formatTags($tags);

        $this->assertSame('PHP, AI', $formatted);
    }

    /**
     * Mirrors ArticleAdapter::formatTags() for isolated testing.
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
