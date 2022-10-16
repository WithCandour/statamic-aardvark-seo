<?php

namespace WithCandour\AardvarkSeo;

use Illuminate\Support\AggregateServiceProvider;
use WithCandour\AardvarkSeo\Providers\AddonServiceProvider;
use WithCandour\AardvarkSeo\Providers\GlobalsServiceProvider;
use WithCandour\AardvarkSeo\Providers\StacheServiceProvider;

class ServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        AddonServiceProvider::class,
        GlobalsServiceProvider::class,
        StacheServiceProvider::class,
    ];
}
