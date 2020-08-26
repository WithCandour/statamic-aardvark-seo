<?php

namespace WithCandour\AardvarkSeo\Sitemaps;

use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\Site;
use Statamic\Facades\Taxonomy;
use Statamic\Facades\Term;
use Statamic\Support\Str;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class Sitemap
{
     /**
     * Create a new sitemap.
     *
     * @param string $type
     * @param string $handle
     */
    public function __construct($type, $handle = '', $site)
    {
        $this->type = $type;
        $this->handle = $handle;
        $this->site = $site;
        $this->generateSitemapURL();
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
                return !is_null($collection->route(Site::current()->handle()));
            })
            ->map(function ($collection) {
                $indexable = self::getIndexStatusForContent('collections', $collection->path(), Site::current());
                return [
                    'type' => 'collection',
                    'handle' => $collection->handle(),
                    'indexable' => $indexable,
                ];
            });
        $taxonomies = collect(Taxonomy::all())
            ->map(function ($taxonomy) {
                $indexable = self::getIndexStatusForContent('taxonomies', $taxonomy->path(), Site::current());
                return [
                    'type' => 'taxonomy',
                    'handle' => $taxonomy->handle(),
                    'indexable' => $indexable,
                ];
            });

        $sitemaps = collect([$collections, $taxonomies])->collapse();

        $filtered_sitemaps = $sitemaps->filter(function ($content_type) {
            return $content_type['indexable'];
        });

        $sitemap_objects = $filtered_sitemaps->map(function ($sitemap) {
            $data = collect($sitemap);
            return new Sitemap($data->get('type'), $data->get('handle'), Site::current());
        });

        return $sitemap_objects->all();
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
                $items = Entry::query()->where('collection', $this->handle)->get();
                break;
            case 'taxonomy':
                $items = Term::query()->where('taxonomy', $this->handle)->get();
                break;
            default:
                $items = Entry::query()->where('collection', 'pages')->get();
        }

        $items = $items->filter(function ($item) {
            return $item->published() && !$item->get('no_index_page');
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
     * Get the sitemap settings from global settings
     */
    public static function getSitemapSettings()
    {
        $settings = AardvarkStorage::getYaml('sitemap', Site::current());
        $blueprint = SitemapSettingsBlueprint::requestBlueprint();
        return $blueprint->fields()->addValues($settings)->augment()->values();
    }

    /**
     * Get the index status for a given content type on a site
     *
     * @param string $type
     * @param string $handle
     * @param Statamic\Sites\Site $site
     */
    public static function getIndexStatusForContent($type = 'collections', $handle = 'pages', $site)
    {
        $site_handle = $site->handle();
        $settings = AardvarkStorage::getYaml("defaults/{$type}_{$handle}.yaml", $site, true);
        $site_settings = collect($settings->get($site_handle));

        $no_indexed = $site_settings->get('no_index_page', 0);
        return !$no_indexed;
    }

    /**
     * Automatically generates some meta data about this sitemap.
     *
     * @return string
     */
    public function generateSitemapURL()
    {
        $handle = $this->handle ?: 'pages';
        $this->route = sprintf('sitemap_%s.xml', $handle);
        $this->url = Str::ensureRight($this->site->absoluteUrl(), '/') . $this->route;
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
     * Get a list of sitemaps matching a specific handle.
     *
     * @param string $handle
     *
     * @return WithCandour\AardvarkSeo\Sitemaps\Sitemap
     */
    public static function findByHandle($handle = '')
    {
        return collect(self::all())->filter(function ($sitemap) use ($handle) {
            return $sitemap->handle === $handle;
        })->first();
    }
}
