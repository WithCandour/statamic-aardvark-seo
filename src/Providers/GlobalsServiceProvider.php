<?php

namespace WithCandour\AardvarkSeo\Providers;

use Illuminate\Support\ServiceProvider;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet as GlobalSetContract;
use WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository as GlobalsRepositoryContract;
use WithCandour\AardvarkSeo\Globals\GlobalSet;
use WithCandour\AardvarkSeo\Stache\Repositories\GlobalsRepository;

class GlobalsServiceProvider extends ServiceProvider
{
    public $bindings = [
        GlobalSetContract::class => GlobalSet::class
    ];

    public $singletons = [
        GlobalsRepositoryContract::class => GlobalsRepository::class
    ];
}
