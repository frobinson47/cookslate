<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PinterestResolverTest extends TestCase
{
    private \PinterestResolver $resolver;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/PinterestResolver.php';
        $this->resolver = new \PinterestResolver();
    }

    // isPinterestUrl

    public function testIsPinterestUrlTrueForPinterestComPin(): void
    {
        $this->assertTrue($this->resolver->isPinterestUrl('https://pinterest.com/pin/123456789/'));
    }

    public function testIsPinterestUrlTrueForWwwPinterestComPin(): void
    {
        $this->assertTrue($this->resolver->isPinterestUrl('https://www.pinterest.com/pin/123456789/'));
    }

    public function testIsPinterestUrlTrueForRegionalTld(): void
    {
        $this->assertTrue($this->resolver->isPinterestUrl('https://www.pinterest.co.uk/pin/123456789/'));
        $this->assertTrue($this->resolver->isPinterestUrl('https://pinterest.de/pin/123456789/'));
    }

    public function testIsPinterestUrlTrueForPinItShortLink(): void
    {
        $this->assertTrue($this->resolver->isPinterestUrl('https://pin.it/abc123'));
    }

    public function testIsPinterestUrlFalseForRandomBlog(): void
    {
        $this->assertFalse($this->resolver->isPinterestUrl('https://example-blog.com/recipe/chili'));
    }

    public function testIsPinterestUrlFalseForPinterestHomepage(): void
    {
        $this->assertFalse($this->resolver->isPinterestUrl('https://www.pinterest.com/'));
        $this->assertFalse($this->resolver->isPinterestUrl('https://www.pinterest.com/search/pins/?q=chili'));
    }

    // extractSourceLink

    public function testExtractSourceLinkFindsExternalLink(): void
    {
        $html = <<<HTML
<html><body>
<script id="__PWS_DATA__" type="application/json">
{"pin":{"id":"123","dominant_color":"#ffffff","link":"https:\/\/example-blog.com\/recipe\/chili","images":{"orig":{"url":"https:\/\/i.pinimg.com\/originals\/ab\/cd\/ef\/image.jpg"}}}}
</script>
</body></html>
HTML;

        $this->assertSame('https://example-blog.com/recipe/chili', $this->resolver->extractSourceLink($html));
    }

    public function testExtractSourceLinkSkipsSelfReferentialLinks(): void
    {
        // A self-referential "link" field appears first; a genuine external
        // "link" field appears second — the self-referential one must be skipped.
        $html = <<<HTML
<script type="application/json">
{"pin":{"link":"https:\/\/www.pinterest.com\/pin\/123\/"},"other":{"link":"https:\/\/example-blog.com\/recipe\/chili"}}
</script>
HTML;

        $this->assertSame('https://example-blog.com/recipe/chili', $this->resolver->extractSourceLink($html));
    }

    public function testExtractSourceLinkReturnsNullWhenNoLinkField(): void
    {
        // Simulates an Idea Pin / native upload with no outbound link.
        $html = <<<HTML
<script type="application/json">
{"pin":{"id":"123","dominant_color":"#ffffff","is_native_pin":true}}
</script>
HTML;

        $this->assertNull($this->resolver->extractSourceLink($html));
    }

    public function testExtractSourceLinkReturnsNullWhenOnlySelfReferentialLinks(): void
    {
        $html = <<<HTML
<script type="application/json">
{"pin":{"link":"https:\/\/www.pinterest.com\/pin\/123\/"}}
</script>
HTML;

        $this->assertNull($this->resolver->extractSourceLink($html));
    }

    public function testExtractSourceLinkReturnsNullForEmptyHtml(): void
    {
        $this->assertNull($this->resolver->extractSourceLink('<html><body>nothing here</body></html>'));
    }
}
