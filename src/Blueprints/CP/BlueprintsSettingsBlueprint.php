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
        return StatamicBlueprint::make()->setContents([
            'sections' => [
                'main' => [
                    'fields' => [
                        [
                            'handle' => 'exclude_blueprints',
                            'field' => [
                                'type' => 'blueprints',
                                'display' => 'Exclude Blueprints',
                                'instructions' => 'Exclude the following blueprints from having the "SEO" section added',
                                'mode' => 'tags',
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
