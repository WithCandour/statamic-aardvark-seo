<?php

namespace WithCandour\AardvarkSeo\Stache\Repositories;

use Illuminate\Support\Collection;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet;

interface GlobalsRepository
{
    /**
     * Return all global sets.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection;

    /**
     * Find a global set by it's ID.
     *
     * @param string $id
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet|null
     */
    public function find(string $id): ?GlobalSet;

    /**
     * Find a global set by it's handle.
     *
     * @param string $handle
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet|null
     */
    public function findByHandle(string $handle): ?GlobalSet;

    /**
     * Save a global set.
     *
     * @return void
     */
    public function save(GlobalSet $set): void;
}
