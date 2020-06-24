<?php

namespace WithCandour\AardvarkSeo\Facades;

use WithCandour\AardvarkSeo\Storage\GlobalsStorage;
use Illuminate\Support\Facades\Facade;

class AardvarkStorage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GlobalsStorage::class;
    }
}
