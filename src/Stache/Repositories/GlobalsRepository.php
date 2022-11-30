<?php

namespace WithCandour\AardvarkSeo\Stache\Repositories;

use Statamic\Stache\Stache;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet;
use WithCandour\AardvarkSeo\Stache\Stores\GlobalSetStore;
use WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository as Contract;

class GlobalsRepository implements Contract
{
    /**
     * @var \Statamic\Stache\Stache
     */
    protected Stache $stache;

    /**
     * @var \WithCandour\AardvarkSeo\Stache\Stores\GlobalSetStore
     */
    protected GlobalSetStore $store;

    /**
     * @param \Statamic\Stache\Stache
     */
    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
        $this->store = $stache->store('aardvark-seo-globals');
    }

    /**
     * @inheritDoc
     */
    public function find(string $id): ?GlobalSet
    {
        return $this->store->store('globals')->getItem($id);
    }

    /**
     * @inheritDoc
     */
    public function save(GlobalSet $set): void
    {
        $this->store->store($set->handle())->save($set);
    }
}
