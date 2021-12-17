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
                                'instructions' => 'Enter the URL pattern that Redirect should match. This matches against the path only e.g.: Exact Match: /blogs/, or RegEx Match: /blogs/(.*)'
                            ],
                            
                        ],
                        [
                            'handle' => 'target_url',
                            'field' => [
                                'type' => 'text',
                                'display' => __('aardvark-seo::redirects.redirect.target_url'),
                                'instructions' => 'Enter the destination URL that should be redirected to. This can either be a fully qualified URL or a relative URL. e.g.: Exact Match: /our-blogs/ or RegEx Match: /our-blogs/$1'
                            ],
                        ],
                        [
                            'handle' => 'match_type',
                            'field' => [
                                'type' => 'select',
                                'display' => __('aardvark-seo::redirects.redirect.match_type'),
                                'instructions' => 'Details on RegEx matching can be found at [regexr.com](http://regexr.com/).',
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
