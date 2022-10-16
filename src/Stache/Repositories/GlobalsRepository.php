<?php

namespace WithCandour\AardvarkSeo\Stache\Repositories;

use Illuminate\Support\Collection;
use Statamic\Stache\Stache;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet;
use WithCandour\AardvarkSeo\Stache\Stores\GlobalsStore;
use WithCandour\AardvarkSeo\Stache\Repositories\GlobalsRepository as Contract;

class GlobalsRepository implements Contract
{
    /**
     * @var \Statamic\Stache\Stache
     */
    protected Stache $stache;

    /**
     * @var \WithCandour\AardvarkSeo\Stache\Stores\GlobalsStore
     */
    protected GlobalsStore $store;

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
    public function all(): Collection
    {
        $keys = $this->store->paths()->keys();
        return \collect($this->store->getItems($keys));
    }

    /**
     * @inheritDoc
     */
    public function find($id): ?GlobalSet
    {
        return $this->store->getItem($id);
    }

    /**
     * @inheritDoc
     */
    public function findByHandle(string $handle): ?GlobalSet
    {
        $key = $this->store->index('handle')->items()->flip()->get($handle);
        return $this->find($key);
    }

    /**
     * @inheritDoc
     */
    public function save(GlobalSet $set): void
    {
        $this->store->save($set);
    }
}
