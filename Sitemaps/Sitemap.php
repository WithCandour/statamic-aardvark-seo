<?php

namespace Statamic\Addons\SeoBox\Sitemaps;

use Statamic\API\Collection;
use Statamic\API\Config;
use Statamic\API\Page;
use Statamic\API\Taxonomy;
use Statamic\Extend\Extensible;

class Sitemap
{
    use Extensible;

    const FILENAME_PREFIX = 'sitemap';

    /**
     * Create a new sitemap.
     *
     * @param string $type
     * @param string $handle
     */
    public function __construct($type, $handle = '')
    {
        $this->type = $type;
        $this->handle = $handle;
        $this->generateSitemapURL();
    }

    /**
     * Return a list of entries for the sitemap to display.
     *
     * @return array
     */
    public function getSitemapItems()
    {
        switch ($this->type) {
        case 'collection':
            $items = Collection::whereHandle($this->handle)->entries();
            break;
        case 'taxonomy':
            $items = Taxonomy::whereHandle($this->handle)->terms();
            break;
        default:
            $items = Page::all();
        }

        $sitemapData = collect($items)->map(function ($item) {
            $data = collect($item->data());

            $data = [
                'url' => $item->absoluteURL(),
                'changefreq' => $data->get('sitemap_changefreq'),
                'priority' => $data->get('sitemap_priority'),
                'lastmod' => $item->lastModified()->format('Y-m-d\TH:i:sP'),
            ];

            return $data;
        });

        return $sitemapData->all();
    }

    /**
     * Automatically generates some meta data about this sitemap.
     *
     * @return string
     */
    public function generateSitemapURL()
    {
        $handle = $this->handle ?: 'pages';
        $this->route = sprintf('%s_%s.xml', self::FILENAME_PREFIX, $handle);
        $this->url = Config::getSiteUrl() . $this->route;
        return $this->url;
    }

    /**
     * Get the date of the most recently edited entry in the sitemap.
     *
     * @return string
     */
    public function getLastMod()
    {
        $items = collect($this->getSitemapItems())->sortByDesc('lastmod');
        return $items->first()['lastmod'];
    }

    /**
     * Generate a list of all possible sitemaps.
     *
     * @return array
     */
    public static function all()
    {
        $collections = collect(Collection::handles())->map(function ($handle) {
            return [
                'type' => 'collection',
                'handle' => $handle,
            ];
        });
        $taxonomies = collect(Taxonomy::handles())->map(function ($handle) {
            return [
                'type' => 'taxonomy',
                'handle' => $handle,
            ];
        });
        $pages = [['type' => 'pages', 'handle' => 'pages']];

        $sitemaps = collect([$collections, $taxonomies, $pages])->collapse();

        $sitemap_objects = $sitemaps->map(function ($sitemap) {
            $data = collect($sitemap);
            return new Sitemap($data->get('type'), $data->get('handle'));
        });

        return $sitemap_objects->all();
    }

    /**
     * Get a list of sitemaps matching a specific handle.
     *
     * @param string $handle
     *
     * @return Statamic\Addons\SeoBox\Sitemaps\Sitemap
     */
    public static function whereHandle($handle = '')
    {
        return collect(self::all())->filter(function ($sitemap) use ($handle) {
            return $sitemap->handle === $handle;
        })->first();
    }
}
