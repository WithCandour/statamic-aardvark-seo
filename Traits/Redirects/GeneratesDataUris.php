<?php

namespace Statamic\Addons\AardvarkSeo\Traits\Redirects;

use Statamic\API\Collection;
use Statamic\API\Taxonomy;
use Statamic\Data\Entries\Entry;
use Statamic\Data\Taxonomies\Term;

trait GeneratesDataUris
{
    /**
     * Generate a term uri for a specific taxonomy based on the slug.
     *
     * @param string $slug
     * @param string $taxonomyName
     *
     * @return string
     */
    public static function term_uri($slug, $taxonomyName)
    {
        $term = new Term();
        $term->taxonomy(Taxonomy::whereHandle($taxonomyName));
        $term->slug($slug);
        return $term->uri();
    }

    /**
     * Generate an entry uri for a specific collection based on the slug.
     *
     * @param string $slug
     * @param string $collectionName
     *
     * @return string
     */
    public static function entry_uri($slug, $collectionName)
    {
        $entry = new Entry();
        $entry->collection(Collection::whereHandle($collectionName));
        $entry->slug($slug);
        return $entry->uri();
    }
}
