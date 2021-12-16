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
                                'display' => __('aardvark-seo::redirects.redirect.source_url'),
                            ],
                        ],
                        [
                            'handle' => 'target_url',
                            'field' => [
                                'type' => 'text',
                                'display' => __('aardvark-seo::redirects.redirect.target_url'),
                            ],
                        ],
                        [
                            'handle' => 'match_type',
                            'field' => [
                                'type' => 'select',
                                'display' => __('aardvark-seo::redirects.redirect.match_type'),
                                'instructions' => 'Details on RegEx matching can be found at [regexr.com](http://regexr.com/). Regex will be applied to source URL.',
                                'validate' => 'required|string',
                                'options' => [
                                    'Exact Match',
                                    'RegEx Match',
                                ],
                                'default' => 'Exact Match',
                            ],
                        ],
                        [
                            'handle' => 'status_code',
                            'field' => [
                                'type' => 'radio',
                                'inline' => true,
                                'options' => [
                                    '301',
                                    '302',
                                ],
                                'display' => __('aardvark-seo::redirects.redirect.status_code'),
                                'default' => '301',
                            ],
                        ],
                        [
                            'handle' => 'is_active',
                            'field' => [
                                'type' => 'toggle',
                                'display' => __('aardvark-seo::redirects.redirect.is_active'),
                                'default' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
