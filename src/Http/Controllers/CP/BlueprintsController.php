<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP;

use Statamic\CP\Breadcrumbs;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Contracts\Publishable;
use WithCandour\AardvarkSeo\Blueprints\CP\BlueprintsSettingsBlueprint;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class BlueprintsController extends Controller implements Publishable
{
    public function index()
    {
        $this->authorize('view aardvark blueprints settings');

        $data = $this->getData();

        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues($data)->preProcess();

        $crumbs = Breadcrumbs::make([
            ['text' => 'Aardvark SEO', 'url' => url(config('statamic.cp.route') . '/aardvark-seo/settings')],
            ['text' => 'Blueprints Settings', 'url' => url(config('statamic.cp.route') . '/aardvark-seo/settings/blueprints')],
        ]);

        return view('aardvark-seo::cp.settings.blueprints', [
            'blueprint' => $blueprint->toPublishArray(),
            'crumbs' => $crumbs,
            'meta' => $fields->meta(),
            'title' => 'Blueprint Settings | Aardvark SEO',
            'values' => $fields->values(),
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $this->authorize('update aardvark blueprints settings');

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
        return BlueprintsSettingsBlueprint::requestBlueprint();
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return AardvarkStorage::getYaml('blueprints', Site::selected());
    }

    /**
     * @inheritdoc
     */
    public function putData($data)
    {
        return AardvarkStorage::putYaml('blueprints', Site::selected(), $data);
    }
}
