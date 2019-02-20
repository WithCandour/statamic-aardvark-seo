<?php

namespace Statamic\Addons\AardvarkSeo;

use Statamic\Addons\AardvarkSeo\Controllers\AardvarkSeoController;
use Statamic\API\File;
use Statamic\API\Parse;
use Statamic\Extend\Tags;

class AardvarkSeoTags extends Tags
{
    /**
     * Return the meta template string.
     *
     * @return string
     */
    public function head()
    {
        $template_file = $this->getTemplateFile('aardvark-seo-head');
        return Parse::template($template_file, $this->getData());
    }

    /**
     * Return the body scripts template string.
     *
     * @return string
     */
    public function body()
    {
        $template_file = $this->getTemplateFile('aardvark-seo-body');
        return Parse::template($template_file, $this->getData());
    }

    /**
     * Return the list of social icons created in the 'Social' menu
     *
     * @return string
     */
    public function socials()
    {
        $data = collect($this->getData());
        $socials = $data->get('social_links');
        if (isset($socials)) {
            return $this->parseLoop($socials);
        }
        return false;
    }

    /**
     * Return the footer scripts template string.
     *
     * @return string
     */
    public function footer()
    {
        $template_file = $this->getTemplateFile('aardvark-seo-footer');
        return Parse::template($template_file, $this->getData());
    }

    /**
     * Return a template file from this addon.
     *
     * @param string $name The name of the html view file
     *
     * @return Statamic\API\File
     */
    private function getTemplateFile($name)
    {
        return File::get($this->getDirectory() . "/resources/views/tags/{$name}.html");
    }

    /**
     * Return transformed data to render in the tag view.
     *
     * @return array
     */
    private function getData()
    {
        $ctx = collect($this->context);
        $combinedData = collect($this->storage->getYAML(AardvarkSeoController::STORAGE_KEY))->merge($ctx);
        $this->rawData = $combinedData;
        return $this->parseData()->all();
    }

    /**
     * Process the data that gets rendered in the tag view.
     *
     * @param array $rawData The data to be parsed
     *
     * @return array Processed data
     */
    private function parseData()
    {
        $calculatedValues = [
            'calculated_title' => $this->getCalculatedTitleValue(),
            'calculated_twitter_card_type' => $this->getInheritedValue([
                'twitter_card_type_page',
                'twitter_card_type',
            ]),
            'calculated_facebook_image' => $this->getInheritedValue([
                'facebook_image',
                'facebook_default_share_image',
            ]),
            'calculated_twitter_summary_image' => $this->getInheritedValue([
                'twitter_summary_image',
                'twitter_default_summary_image',
                'facebook_default_share_image',
            ]),
            'calculated_twitter_large_image' => $this->getInheritedValue([
                'twitter_summary_large_image',
                'twitter_default_large_summary_image',
                'twitter_default_summary_image',
                'facebook_default_share_image',
            ]),
        ];
        return $this->rawData->merge($calculatedValues);
    }

    /**
     * Takes an array of possible keys where a value could be found and returns the first
     * non-falsey value.
     *
     * @param array $keys An array of keys to search
     *
     * @return mixed The first relevant value
     */
    private function getInheritedValue($keys)
    {
        $data = $this->rawData;
        $foundValueAt = collect($keys)->filter(function ($key) use ($data) {
            return $data->get($key) !== null;
        });

        return $foundValueAt->count() > 0 ? $data->get($foundValueAt->first()) : false;
    }

    /**
     * Combine the page name, site name and title separator.
     *
     * @return string
     */
    private function getCalculatedTitleValue()
    {
        $data = $this->rawData;
        return \implode(' ', [
            $data->get('title'),
            $data->get('title_separator'),
            $data->get('site_name'),
        ]);
    }
}
