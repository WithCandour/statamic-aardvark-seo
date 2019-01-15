<?php

namespace Statamic\Addons\SeoBox\Controllers;

use Illuminate\Http\Request;
use Statamic\API\Fieldset;
use Statamic\CP\Publish\ProcessesFields;

class SeoBoxController extends Controller
{
    use ProcessesFields;

    const STORAGE_KEY = 'seo-globals';

    /**
     * @return Illuminate\Routing\Redirector
     */
    public function index()
    {
        return redirect()->route('seo-box.general');
    }

    /**
     * Render the 'general' fieldset within the CP.
     */
    public function getGeneralForm()
    {
        return $this->renderCPForm('general', [
            'title' => 'General SEO Settings',
            'submitRoute' => 'seo-box.update-globals',
        ], self::STORAGE_KEY);
    }

    /**
     * Render the 'marketing' fieldset within the CP.
     */
    public function getMarketingForm()
    {
        return $this->renderCPForm('marketing', [
            'title' => 'SEO Marketing Options',
            'submitRoute' => 'seo-box.update-globals',
        ], self::STORAGE_KEY);
    }

    /**
     * Render the 'social' fieldset within the CP.
     */
    public function getSocialForm()
    {
        return $this->renderCPForm('social', [
            'title' => 'Social Media Settings',
            'submitRoute' => 'seo-box.update-globals',
        ], self::STORAGE_KEY);
    }

    /**
     * Update the full seo settings data.
     *
     * @param Illuminate\Http\Request $request
     */
    public function cpUpdate(Request $request)
    {
        return $this->updateStorage($request, self::STORAGE_KEY, 'seo-box.general');
    }
}
