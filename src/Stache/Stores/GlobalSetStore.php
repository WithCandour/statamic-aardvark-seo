<?php

namespace WithCandour\AardvarkSeo\Stache\Stores;

use Statamic\Stache\Stores\AggregateStore;

class GlobalSetStore extends AggregateStore
{
    /**
     * @var string
     */
    protected $childStore = GlobalVariablesStore::class;

    public function key()
    {
        return 'aardvark-seo-globals';
    }

    public function discoverStores()
    {
        return \collect([]);
    }
}
