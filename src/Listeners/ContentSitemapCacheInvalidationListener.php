<?php

namespace WithCandour\AardvarkSeo\Listeners;

use Illuminate\Support\Facades\Cache;
use Statamic\Facades\Site;

class ContentSitemapCacheInvalidationListener
{
    public function handle($event)
    {
        $blank_event = new \ReflectionClass($event);
        $content_type = strpos($blank_event->getShortName(), 'Term') ? 'term' : 'entry';

        if($content_type === 'term') {
            $term = $event->term;
            $handle = $term->taxonomy()->handle();
        } else {
            $entry = $event->entry;
            $handle = $entry->collection()->handle();
        }

        $site = Site::current();

        Cache::forget("aardvark-seo.sitemap-index.{$site->handle()}");
        Cache::forget("aardvark-seo.sitemap-{$handle}.{$site->handle()}");
    }
}
