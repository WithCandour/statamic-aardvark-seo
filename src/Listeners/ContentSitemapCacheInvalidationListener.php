<?php

namespace WithCandour\AardvarkSeo\Listeners;

use Illuminate\Support\Facades\Cache;
use Statamic\Support\Str;
use Statamic\Facades\Site;

class ContentSitemapCacheInvalidationListener
{
    public function handle($event)
    {
        $blank_event = new \ReflectionClass($event);
        $content_type = Str::contains($blank_event->getShortName(), 'Term') ? 'term' : 'entry';

        if ($content_type === 'term') {
            $term = $event->term;
            $site = $term->site();
            $handle = $term->taxonomy()->handle();
        } else {
            $entry = $event->entry;
            $site = $entry->site();
            $handle = $entry->collection()->handle();
        }

        Cache::forget("aardvark-seo.sitemap-index.{$site->handle()}");
        Cache::forget("aardvark-seo.sitemap-{$handle}.{$site->handle()}");
    }
}
