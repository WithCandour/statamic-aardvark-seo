<?php

namespace WithCandour\AardvarkSeo\Providers;

use Illuminate\Support\ServiceProvider;
use Statamic\Facades\Site;
use Statamic\Stache\Stache;
use WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository as GlobalsRepositoryContract;
use WithCandour\AardvarkSeo\Stache\Repositories\GlobalsRepository;

class StacheServiceProvider extends ServiceProvider
{
    public $singletons = [
        GlobalsRepositoryContract::class => GlobalsRepository::class
    ];

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
