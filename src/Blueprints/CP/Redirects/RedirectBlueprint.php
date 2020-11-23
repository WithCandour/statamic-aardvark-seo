<?php

namespace WithCandour\AardvarkSeo\Blueprints\CP\Redirects;

use WithCandour\AardvarkSeo\Blueprints\Blueprint as AardvarkBlueprint;
use Statamic\Facades\Blueprint as StatamicBlueprint;

class RedirectBlueprint implements AardvarkBlueprint
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
                            'handle' => 'source_url',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Source URL'
                            ]
                        ],
                        [
                            'handle' => 'target_url',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Target URL'
                            ]
                        ],
                        [
                            'handle' => 'status_code',
                            'field' => [
                                'type' => 'radio',
                                'inline' => true,
                                'options' => [
                                    '301',
                                    '302'
                                ],
                                'display' => 'Status Code',
                                'default' => '301'
                            ],
                        ],
                        [
                            'handle' => 'is_active',
                            'field' => [
                                'type' => 'toggle',
                                'display' => 'Is Active?',
                                'default' => true
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
