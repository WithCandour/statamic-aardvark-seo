<?php

namespace WithCandour\AardvarkSeo\Storage;

use Statamic\Sites\Site;
use Statamic\Facades\File;
use Statamic\Facades\YAML;

class GlobalsStorage implements Storage
{
    const prefix = 'aardvark';

    /**
     * Retrieve YAML data from storage
     *
     * @param string $handle
     * @param Site $site
     * @param bool $returnCollection
     *
     * @return array
     */
    public static function getYaml(string $handle, Site $site, bool $returnCollection = false)
    {
        $path = storage_path(implode("/", [
            'statamic/addons/aardvark-seo',
            self::prefix . '_' . "{$handle}.yaml"
        ]));

        $data = YAML::parse(File::get($path));

        $site_data = collect($data)->get($site->handle());

        if ($returnCollection) {
            return collect($site_data);
        }

        return collect($site_data)->toArray() ?: [];
    }

    /**
     * Put YAML data into storage
     *
     * @param string $handle
     * @param Site $site
     * @param array $data
     *
     * @return void
     */
    public static function putYaml(string $handle, Site $site, array $data)
    {
        $path = storage_path(implode("/", [
            'statamic/addons/aardvark-seo',
            self::prefix . '_' . "{$handle}.yaml"
        ]));

        $existing = collect(YAML::parse(File::get($path)));

        $combined_data = $existing->merge([
            "{$site->handle()}" => $data
        ]);

        File::put($path, YAML::dump($combined_data->toArray()));
    }
}
