<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\Web;

use Illuminate\Routing\Controller as LaravelController;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Sitemaps\Sitemap;
use WithCandour\AardvarkSeo\Blueprints\CP\SitemapSettingsBlueprint;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class SitemapController extends LaravelController
{
    public function index()
    {
        $sitemaps = Sitemap::all();
        die(print_r($sitemaps));
        return 'Sitemap';
    }

    /**
     * Return the xsl file required for our sitemap views
     */
    public function xsl()
    {
        $path = __DIR__ . '/../../../../resources/xsl/sitemap.xsl';
        return response(file_get_contents($path))->header('Content-Type', 'text/xsl');
    }
}
