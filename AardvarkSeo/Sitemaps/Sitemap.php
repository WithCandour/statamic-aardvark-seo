<?php

namespace Statamic\Addons\AardvarkSeo\Sitemaps;

use Statamic\Addons\AardvarkSeo\Sitemaps\SitemapItem;
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

        $items = $items->filter(function ($item) {
            return $item->published() && !$item->get('page_no_index');
        });

        $sitemap_items = collect($items)->map(function ($item) {
            return new SitemapItem($item);
        });

        $sitemapData = $sitemap_items
            ->unique(function ($item) {
                return $item->getUrl();
            })
            ->map(function ($item) {
                $data = [
                    'url' => $item->getUrl(),
                    'changefreq' => $item->getChangeFreq(),
                    'priority' => $item->getPriority(),
                    'lastmod' => $item->getFormattedLastMod(),
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
        $collections = collect(Collection::all())
            ->filter(function ($collection) {
                return !is_null($collection->route());
            })
            ->map(function ($collection) {
                return [
                    'type' => 'collection',
                    'handle' => $collection->path(),
                ];
            });
        $taxonomies = collect(Taxonomy::all())
            ->filter(function ($taxonomy) {
                return !is_null($taxonomy->route());
            })
            ->map(function ($taxonomy) {
                return [
                    'type' => 'taxonomy',
                    'handle' => $taxonomy->path(),
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
     * @return Statamic\Addons\AardvarkSeo\Sitemaps\Sitemap
     */
    public static function whereHandle($handle = '')
    {
        return collect(self::all())->filter(function ($sitemap) use ($handle) {
            return $sitemap->handle === $handle;
        })->first();
    }
}
