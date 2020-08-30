<?php

namespace WithCandour\AardvarkSeo\Facades;

use WithCandour\AardvarkSeo\Content\ContentDefaultsGetter;
use Illuminate\Support\Facades\Facade;

class ContentDefaults extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return ContentDefaultsGetter::class;
    }
}
