<?php

namespace Statamic\Addons\AardvarkSeo\Tests;

use Statamic\Addons\AardvarkSeo\Sitemaps\Sitemap;
use Statamic\Testing\TestCase;

class SitemapTest extends TestCase
{
    /**
     * @test
     */
    public function sitemap_prefix_is_present()
    {
        $prefix = Sitemap::FILENAME_PREFIX;
        return $this->assertEquals('sitemap', $prefix);
    }

    /**
     * @test
     * @depends sitemap_prefix_is_present
     */
    public function sitemap_generates_correct_urls()
    {
        $sitemap = new Sitemap('collection', 'test_collection');
        return $this->assertStringEndsWith('/sitemap_test_collection.xml', $sitemap->generateSitemapURL());
    }
}
