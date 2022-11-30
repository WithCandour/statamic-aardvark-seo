<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP;

use WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Contracts\Publishable;

abstract class GlobalsController extends Controller implements Publishable
{
    /**
     * @var \WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository|null
     */
    protected ?GlobalsRepository $repository = null;

    /**
     * @param \WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository
     */
    public function __construct(GlobalsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the globals repository.
     *
     * @return \WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository
     */
    public function repository(): GlobalsRepository
    {
        return $this->repository;
    }
}
