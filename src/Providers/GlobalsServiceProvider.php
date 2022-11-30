<?php

namespace WithCandour\AardvarkSeo\Providers;

use Illuminate\Support\ServiceProvider;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet as GlobalSetContract;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables as GlobalVariablesContract;
use WithCandour\AardvarkSeo\Globals\GlobalSet;
use WithCandour\AardvarkSeo\Globals\GlobalVariables;

class GlobalsServiceProvider extends ServiceProvider
{
    public $bindings = [
        GlobalSetContract::class => GlobalSet::class,
        GlobalVariablesContract::class => GlobalVariables::class,
    ];
}
