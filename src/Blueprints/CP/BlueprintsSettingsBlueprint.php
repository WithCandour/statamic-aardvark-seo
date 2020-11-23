<?php

namespace WithCandour\AardvarkSeo\Blueprints\CP;

use WithCandour\AardvarkSeo\Blueprints\Blueprint as AardvarkBlueprint;
use Statamic\Facades\Blueprint as StatamicBlueprint;

class BlueprintsSettingsBlueprint implements AardvarkBlueprint
{
    /**
     * @inheritDoc
     */
    public static function requestBlueprint()
    {
        // TODO: Thank you so much Statamic for removing the blueprints field, need to fix this
        return StatamicBlueprint::make()->setContents([
            'sections' => [
                'main' => [
                    'fields' => [
                        [
                            'handle' => 'exclude_blueprints',
                            'field' => [
                                'type' => 'select',
                                'display' => 'Exclude Blueprints',
                                'instructions' => 'Exclude the following blueprints from having the "SEO" section added',
                                'multiple' => true,
                                'taggable' => true,
                                'default' => [
                                    'user',
                                    'asset'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
