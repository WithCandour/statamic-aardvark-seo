<?php

namespace WithCandour\AardvarkSeo\Actions\Redirects;

use Statamic\Actions\Action;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Redirects\Repositories\RedirectsRepository;

class DeleteAutoRedirectsAction extends DeleteRedirectsAction
{
    public function run($items, $values)
    {
        $items->each(function($redirect) {
            $this->repository()->delete($redirect);
        });
    }

    private function repository()
    {
        return new RedirectsRepository('redirects/auto', Site::selected());
    }
}
