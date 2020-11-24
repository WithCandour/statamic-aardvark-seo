<?php

namespace WithCandour\AardvarkSeo\Actions\Redirects;

use Statamic\Actions\Action;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Redirects\Repositories\RedirectsRepository;

class DeleteManualRedirectsAction extends DeleteRedirectsAction
{
    public function run($items, $values)
    {
        $items->each(function($redirect) {
            $this->repository()->delete($redirect);
        });
    }

    private function repository()
    {
        return new RedirectsRepository('redirects/manual', Site::selected());
    }
}
