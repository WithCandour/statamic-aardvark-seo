<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP;

use Statamic\CP\Breadcrumbs;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Interfaces\Publishable;
use WithCandour\AardvarkSeo\Blueprints\CP\SitemapSettingsBlueprint;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class SitemapController extends Controller implements Publishable
{
    public function index()
    {
        $this->authorize('view aardvark sitemap settings');

        $data = $this->getData();

        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues($data)->preProcess();

        $crumbs = Breadcrumbs::make([
            ['text' => 'Aardvark SEO', 'url' => url(config('statamic.cp.route') . '/aardvark-seo/settings')],
            ['text' => 'Content Defaults', 'url' => url(config('statamic.cp.route') . '/aardvark-seo/settings/sitemap')],
        ]);

        return view('aardvark-seo::cp.settings.sitemap', [
            'blueprint' => $blueprint->toPublishArray(),
            'crumbs' => $crumbs,
            'meta' => $fields->meta(),
            'title' => 'Sitemap Settings | Aardvark SEO',
            'values' => $fields->values(),
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $this->authorize('update aardvark sitemap settings');

        $blueprint = $this->getBlueprint();

        $fields = $blueprint->fields()->addValues($request->all());
        $fields->validate();

        $this->putData($fields->process()->values()->toArray());
    }

    /**
     * @inheritdoc
     */
    public function getBlueprint()
    {
        return SitemapSettingsBlueprint::requestBlueprint();
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return AardvarkStorage::getYaml('sitemap', Site::selected());
    }

    /**
     * @inheritdoc
     */
    public function putData($data)
    {
        return AardvarkStorage::putYaml('sitemap', Site::selected(), $data);
    }
}
