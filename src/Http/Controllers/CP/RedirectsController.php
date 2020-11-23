<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP;

use Statamic\CP\Breadcrumbs;
use Statamic\CP\Columns;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;
use WithCandour\AardvarkSeo\Blueprints\CP\RedirectsSettingsBlueprint;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Contracts\Publishable;

class RedirectsController extends Controller
{
    public function index()
    {
        $this->authorize('view aardvark redirects settings');

        // $crumbs = Breadcrumbs::make([
        //     ['text' => 'Aardvark SEO', 'url' => url(config('statamic.cp.route') . '/aardvark-seo/settings')],
        //     ['text' => 'Redirects Settings', 'url' => url(config('statamic.cp.route') . '/aardvark-seo/settings/redirects')],
        // ]);

        return view('aardvark-seo::cp.redirects.index', [
            'title' => 'Redirects Settings | Aardvark SEO',
            'columns' => $columns,
            'redirects' => [],
        ]);
    }
}
