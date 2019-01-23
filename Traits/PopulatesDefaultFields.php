<?php

namespace Statamic\Addons\AardvarkSeo\Traits;

use Statamic\Addons\AardvarkSeo\Controllers\AardvarkSeoController;

trait PopulatesDefaultFields
{
    private $field_mapping = [
        'facebook_image' => 'facebook_default_share_image',
        'twitter_card_type_page' => 'twitter_card_type',
        'twitter_summary_image' => 'twitter_default_summary_image',
        'twitter_summary_large_image' => 'twitter_default_large_summary_image',
    ];

    /**
     * Return a list of fields with their default values populated from the 'globals'.
     *
     * @param array                                        $fields  The fields to search and populate
     * @param Statamic\Extend\Contextual\ContextualStorage $storage The storage object for this addon
     *
     * @return array
     */
    protected function populateDefaults($fields, $storage)
    {
        $defaults = $storage->getYAML(AardvarkSeoController::STORAGE_KEY);
        foreach ($this->field_mapping as $field => $value) {
            $fields[$field]['default'] = \array_key_exists($value, $defaults) ? $defaults[$value] : '';
        }
        return $fields;
    }
}
