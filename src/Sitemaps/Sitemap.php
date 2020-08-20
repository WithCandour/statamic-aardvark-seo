<?php

namespace WithCandour\AardvarkSeo\Sitemaps;

use Statamic\Facades\Collection;
use Statamic\Facades\Site;
use Statamic\Facades\Taxonomy;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class Sitemap
{
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
                    'handle' => $collection->path(),
                    'indexable' => $indexable,
                ];
            });
        $taxonomies = collect(Taxonomy::all())
            ->map(function ($taxonomy) {
                $indexable = self::getIndexStatusForContent('taxonomies', $taxonomy->path(), Site::current());
                return [
                    'type' => 'taxonomy',
                    'handle' => $taxonomy->path(),
                    'indexable' => $indexable,
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
        return $site_settings->get('no_index_page', 0);
    }
}
