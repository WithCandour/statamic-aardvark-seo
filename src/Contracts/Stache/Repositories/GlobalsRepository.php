<?php

namespace WithCandour\AardvarkSeo\Contracts\Stache\Repositories;

use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet;

interface GlobalsRepository
{
    /**
     * Find a global set by it's type and ID.
     *
     * @param string $id
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet|null
     */
    public function find(string $id): ?GlobalSet;

    /**
     * Save a global set.
     *
     * @return void
     */
    public function save(GlobalSet $set): void;
}
