<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP;

use Statamic\CP\Breadcrumbs;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Interfaces\Publishable;
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
            ['text' => 'Aardvark SEO', 'url' => '/cp/aardvark-seo/settings'],
            ['text' => 'Blueprint Settings', 'url' => '/blueprints'],
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
        return AardvarkStorage::getYaml('blueprints');
    }

    /**
     * @inheritdoc
     */
    public function putData($data)
    {
        return AardvarkStorage::putYaml('blueprints', $data);
    }
}
