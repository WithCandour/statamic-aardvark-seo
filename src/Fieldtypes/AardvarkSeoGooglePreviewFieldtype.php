<?php

namespace WithCandour\AardvarkSeo\Fieldtypes;

use Statamic\Facades\Site;
use Statamic\Fields\Fieldtype;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class AardvarkSeoGooglePreviewFieldtype extends Fieldtype
{
    protected $selectable = false;

    /**
     * Load the global seo settings from storage
     */
    public function preload()
    {
        $site = Site::selected();
        $data = AardvarkStorage::getYaml('general', $site, true);
        return [
            'site_name' => $data->get('site_name', ''),
            'site_url' => $site->absoluteUrl(),
            'title_separator' => $data->get('title_separator', '|'),
            'default_locale' => Site::default()->handle(),
        ];
    }
}
