<?php

namespace WithCandour\AardvarkSeo\Listeners;

use Illuminate\Support\Facades\Cache;

class DefaultsSitemapCacheInvalidationListener
{
    public function handle(\WithCandour\AardvarkSeo\Events\AardvarkContentDefaultsSaved $event)
    {
        $defaults = $event->defaults;
        $site = $defaults->site->handle();
        $handle = $defaults->handle;

        Cache::forget("aardvark-seo.sitemap-index.{$site}");
        Cache::forget("aardvark-seo.sitemap-{$handle}.{$site}");
    }
}
