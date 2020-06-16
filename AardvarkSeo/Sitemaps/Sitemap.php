<?php

namespace Statamic\Addons\AardvarkSeo\Sitemaps;

use Statamic\Addons\AardvarkSeo\Sitemaps\SitemapItem;
use Statamic\Addons\AardvarkSeo\Controllers\SitemapController;
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
     * Return the storage data for the sitemap module.
     *
     * @return Illuminate\Support\Collection
     */
    private function getStore()
    {
        return collect($this->storage->getYAML(SitemapController::STORAGE_KEY));
    }

    /**
     * Get pages that are allowed in the main sitemap
     */
    private function getPageObjects()
    {
        $excludedPages = $this->getStore()->get('exclude_pages');
        return Page::all()->filter(function ($page) use ($excludedPages) {
            if (in_array($page->id(), $excludedPages)) {
                return false;
            }

            $excluded = false;
            $segments = explode('/', $page->url());

            while (array_pop($segments) && !$excluded) {
                $toCheck = Page::whereUri(implode('/', $segments));
                if ($toCheck && in_array($toCheck->id(), $excludedPages)) {
                    $excluded = true;
                }
            }

            return !$excluded;
        });
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
                $items = $this->getPageObjects();
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
        $this->url = Config::getSiteUrl(site_locale()) . $this->route;
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
                $aardvark_data = $collection->get('aardvark_' . site_locale(), null);
                return [
                    'type' => 'collection',
                    'handle' => $collection->path(),
                    'indexable' => $aardvark_data ? !collect($aardvark_data)->get('page_no_index', false) : true,
                ];
            });
        $taxonomies = collect(Taxonomy::all())
            ->filter(function ($taxonomy) {
                return !is_null($taxonomy->route());
            })
            ->map(function ($taxonomy) {
                $aardvark_data = $taxonomy->get('aardvark_' . site_locale(), null);
                return [
                    'type' => 'taxonomy',
                    'handle' => $taxonomy->path(),
                    'indexable' => $aardvark_data ? !collect($aardvark_data)->get('page_no_index', false) : true,
                ];
            });
        $pages = [['type' => 'pages', 'handle' => 'pages', 'indexable' => true]];

        $sitemaps = collect([$collections, $taxonomies, $pages])->collapse();

        $filtered_sitemaps = $sitemaps->filter(function ($content_type) {
            return $content_type['indexable'];
        });

        $sitemap_objects = $filtered_sitemaps->map(function ($sitemap) {
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
