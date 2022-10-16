<?php

namespace WithCandour\AardvarkSeo\Providers;

use Illuminate\Support\ServiceProvider;
use Statamic\Facades\Site;
use Statamic\Stache\Stache;

class StacheServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this
            ->bootStores();
    }

    /**
     * Register our custom stache stores.
     *
     * @return self
     */
    public function bootStores(): self
    {
        $stache = $this->app->make(Stache::class);
        $stache->sites(Site::all()->keys()->all());

        $stores = \collect(config('aardvark-seo.stache.stores', []))
            ->map(function ($entry) {
                return app($entry['class'])->directory($entry['directory'] ?? null);
            });

        $stache->registerStores($stores->all());

        return $this;
    }

}
