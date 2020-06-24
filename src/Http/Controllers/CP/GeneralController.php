<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP;

use Statamic\CP\Breadcrumbs;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Interfaces\Publishable;
use WithCandour\AardvarkSeo\Blueprints\CP\GeneralSettingsBlueprint;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class GeneralController extends Controller implements Publishable
{
    public function index()
    {
        $data = $this->getData();

        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues($data)->preProcess();

        $crumbs = Breadcrumbs::make([
            ['text' => 'Aardvark SEO', 'url' => '/cp/aardvark-seo/settings'],
            ['text' => 'General Settings', 'url' => '/general'],
        ]);

        return view('aardvark-seo::cp.settings.general', [
            'blueprint' => $blueprint->toPublishArray(),
            'crumbs' => $crumbs,
            'meta' => $fields->meta(),
            'title' => 'General Settings | Aardvark SEO',
            'values' => $fields->values(),
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
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
        return GeneralSettingsBlueprint::requestBlueprint();
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return AardvarkStorage::getYaml('general');
    }

    /**
     * @inheritdoc
     */
    public function putData($data)
    {
        return AardvarkStorage::putYaml('general', $data);
    }
}
