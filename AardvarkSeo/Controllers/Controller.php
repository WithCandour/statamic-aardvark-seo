<?php

namespace Statamic\Addons\AardvarkSeo\Controllers;

use Illuminate\Http\Request;
use Statamic\Addons\AardvarkSeo\Traits\TransformsAssetsFieldtypes;
use Statamic\API\Fieldset;
use Statamic\API\File;
use Statamic\API\Yaml;
use Statamic\Events\Data\SettingsSaved;
use Statamic\Extend\Controller as StatamicController;

class Controller extends StatamicController
{
    use TransformsAssetsFieldtypes;

    /**
     * Searches /fieldsets for custom yaml files for this addon.
     *
     * @param $name The fieldset name
     *
     * @return Fieldset
     */
    public function createAddonFieldset($name)
    {
        $contents = File::get($this->getDirectory() . "/fieldsets/{$name}.yaml");
        $yaml = Yaml::parse($contents);
        if (array_key_exists('fields', $yaml)) {
            $yaml['fields'] = $this->transformAssetsFields($yaml['fields'], $this->getConfig('asset_container'));
        } else {
            $yaml['sections'] = collect($yaml['sections'])->map(function ($section) {
                return ['fields' => $this->transformAssetsFields($section['fields'], $this->getConfig('asset_container'))];
            });
        }
        return Fieldset::create($name, $yaml);
    }

    /**
     * Prepare data and render section.
     *
     * @param string $fieldsetName The name of the fieldset file
     * @param array  $options      Additional options to pass to the form
     * @param string $storageKey   The file key where data for this form is stored
     */
    protected function renderCPForm($fieldsetName, $options, $storageKey)
    {
        $fieldset = $this->createAddonFieldset($fieldsetName);

        $data = $this->preProcessWithBlankFields(
            $fieldset,
            $this->storage->getYAML($storageKey)
        );

        return $this->view('cp', [
            'id' => null,
            'data' => $data,
            'title' => $options['title'],
            'fieldset' => $fieldset->toPublishArray(),
            'submitUrl' => route($options['submitRoute']),
        ]);
    }

    /**
     * Update the full seo settings data.
     *
     * @param Illuminate\Http\Request $request
     * @param string                  $storageKey The key for the yaml file used to store the data
     * @param string                  $route      The route to redirect back to
     */
    public function updateStorage(Request $request, $storageKey, $route)
    {
        $data = $this->processFields($this->createAddonFieldset($request->fieldset), $request->fields);
        $this->storage->putYAML($storageKey, $data);

        $file = site_storage_path('/addons/AardvarkSeo/' . $storageKey . '.yaml');
        event(new SettingsSaved($file, $data));

        return $this->successResponse($route);
    }

    /**
     * Return a success response when saving our data.
     *
     * @param string $message The message to put inside the success box
     * @param string $route   The route to redirect back to
     */
    private function successResponse($route)
    {
        $message = 'SEO Settings Updated!';

        if (!request()->continue || request()->new) {
            $this->success($message);
        }

        return [
            'success' => true,
            'redirect' => route($route),
            'message' => $message,
        ];
    }
}
