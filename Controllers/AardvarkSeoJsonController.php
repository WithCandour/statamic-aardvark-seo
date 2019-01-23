<?php

namespace Statamic\Addons\AardvarkSeo\Controllers;

use Statamic\Extend\Controller as BaseController;

class AardvarkSeoJsonController extends BaseController
{
    /**
     * Returns a JSON string of SEO Box settings in
     * for use in the control panel.
     *
     * @return array
     */
    public function index()
    {
        $data = collect($this->storage->getYAML(AardvarkSeoController::STORAGE_KEY));
        return [
            'title_separator' => $data->get('title_separator'),
            'site_name' => $data->get('site_name'),
        ];
    }
}
