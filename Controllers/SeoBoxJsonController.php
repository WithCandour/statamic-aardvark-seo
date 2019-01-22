<?php

namespace Statamic\Addons\SeoBox\Controllers;

use Statamic\Extend\Controller as BaseController;

class SeoBoxJsonController extends BaseController
{
    /**
     * Returns a JSON string of SEO Box settings in
     * for use in the control panel.
     *
     * @return array
     */
    public function index()
    {
        $data = collect($this->storage->getYAML(SeoBoxController::STORAGE_KEY));
        return [
            'title_separator' => $data->get('title_separator'),
            'site_name' => $data->get('site_name'),
        ];
    }
}
