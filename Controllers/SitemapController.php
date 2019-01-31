<?php

namespace Statamic\Addons\AardvarkSeo\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Statamic\Addons\AardvarkSeo\Sitemaps\Sitemap;
use Statamic\API\Content;
use Statamic\API\File;
use Statamic\CP\Publish\ProcessesFields;

class SitemapController extends Controller
{
    use ProcessesFields;

    const STORAGE_KEY = 'seo-sitemap';

    const SINGLE_ROUTE = Sitemap::FILENAME_PREFIX . '_{handle}.xml';

    /**
     * Render the sitemap form in the cp.
     */
    public function index()
    {
        return $this->renderCPForm('sitemap', [
            'title' => 'Sitemap Settings',
            'submitRoute' => 'aardvark-seo.update-sitemap',
        ], self::STORAGE_KEY);
    }

    /**
     * Update the full seo settings data.
     *
     * @param Illuminate\Http\Request $request
     */
    public function cpUpdate(Request $request)
    {
        return $this->updateStorage($request, self::STORAGE_KEY, 'aardvark-seo.sitemap');
    }

    /**
     * Render the sitemap using the template view.
     *
     * @return Illuminate\Http\Response
     */
    public function renderSitemapIndex()
    {
        $view = Cache::remember('sitemap.index', $this->getCacheExpiration(), function () {
            return $this->view('sitemap_index', [
                'xmlDefinition' => '<?xml version="1.0" encoding="utf-8"?>',
                'xslLink' => '<?xml-stylesheet type="text/xsl" href="/seo-sitemap.xsl"?>',
                'sitemaps' => Sitemap::all(),
            ])->render();
        });

        return response($view)->header('Content-Type', 'text/xml');
    }

    /**
     * Render a single sitemap based on a handle.
     *
     * @param string $handle
     *
     * @return Illuminate\Http\Response
     */
    public function renderSingleSitemap($handle)
    {
        $sitemap = Sitemap::whereHandle($handle);

        if (!$sitemap) {
            abort(404);
        }

        $view = Cache::remember("sitemap.{$handle}", $this->getCacheExpiration(), function () use ($sitemap) {
            return $this->view('sitemap_single', [
                'xmlDefinition' => '<?xml version="1.0" encoding="utf-8"?>',
                'xslLink' => '<?xml-stylesheet type="text/xsl" href="/seo-sitemap.xsl"?>',
                'data' => $sitemap->getSitemapItems(),
            ])->render();
        });

        return response($view)->header('Content-Type', 'text/xml');
    }

    /**
     * Return a http response containing the xsl content for the sitemap.
     *
     * @return
     */
    public function getSitemapStyles()
    {
        $filepath = $this->getDirectory() . '/resources/xsl/sitemap.xsl';
        $contents = File::get($filepath);
        return response($contents)->header('Content-Type', 'text/xsl');
    }

    /**
     * Return the storage data for the sitemap module.
     *
     * @return Illuminate\Support\Collection
     */
    private function getStore()
    {
        return collect($this->storage->getYAML(self::STORAGE_KEY));
    }

    /**
     * Return the user-set value for the sitemap cache expiration.
     *
     * @return int
     */
    private function getCacheExpiration()
    {
        return $this->getStore()->get('sitemap_cache_expiration') ?: 180;
    }

    /**
     * Clear the sitemap index cache - this should be
     * called in parallel to clearing any of the 'sub-sitemaps'.
     */
    public static function clearIndexCache()
    {
        return Cache::forget('sitemap.index');
    }

    /**
     * Clear an individual cached sitemap.
     *
     * @param string $handle
     */
    public static function clearCacheByHandle($handle)
    {
        self::clearIndexCache();
        return Cache::forget("sitemap.{$handle}");
    }

    /**
     * Clear extract the data from an object and clear it's sitemap cache.
     *
     * @param Statamic\Data\Content\Content $content
     */
    public static function clearCacheBasedOnDataObject($content)
    {
        switch ($content->contentType()) {
        case 'page':
            $handle = 'pages';
            break;
        case 'entry':
            $handle = $content->collectionName();
            break;
        case 'term':
            $handle = $content->taxonomyName();
            break;
        default:
            $handle = 'pages';
        }

        return self::clearCacheByHandle($handle);
    }
}
