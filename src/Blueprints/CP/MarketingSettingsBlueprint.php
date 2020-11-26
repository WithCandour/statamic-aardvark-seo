<?php

namespace WithCandour\AardvarkSeo\Blueprints\CP;

use WithCandour\AardvarkSeo\Blueprints\Blueprint as AardvarkBlueprint;
use Statamic\Facades\Blueprint as StatamicBlueprint;

class MarketingSettingsBlueprint implements AardvarkBlueprint
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
                            'handle' => 'gtm_section',
                            'field' => [
                                'type' => 'section',
                                'listable' => 'hidden',
                                'display' => 'Google Tag Manager',
                                'instructions' => 'Manage your Google tag manager settings here.',
                            ],
                        ],
                        [
                            'handle' => 'enable_gtm_script',
                            'field' => [
                                'type' => 'toggle',
                                'display' => 'Enable Google Tag Manager Script',
                                'instructions' => 'Toggle whether the GTM script is placed on the website.',
                                'width' => 33,
                            ],
                        ],
                        [
                            'handle' => 'gtm_identifier',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Google Tag Manager ID',
                                'instructions' => 'Copy your Google tag manager identifier here.',
                                'width' => 66,
                                'if' => [
                                    'enable_gtm_script' => 'equals true',
                                ],
                            ],
                        ],
                        [
                            'handle' => 'site_verification_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Site Verification',
                                'instructions' => 'Copy your Google tag manager identifier here.',
                                'listable' => 'hidden',
                            ],
                        ],
                        [
                            'handle' => 'google_verification_code',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Google Verification Code',
                                'width' => 50,
                            ],
                        ],
                        [
                            'handle' => 'bing_verification_code',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Bing Verification Code',
                                'width' => 50,
                            ],
                        ],
                        [
                            'handle' => 'yandex_verification_code',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Yandex Verification Code',
                                'width' => 50,
                            ],
                        ],
                        [
                            'handle' => 'baidu_verification_code',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Baidu Verification Code',
                                'width' => 50,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
