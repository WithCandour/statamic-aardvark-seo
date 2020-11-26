<?php

namespace WithCandour\AardvarkSeo\Blueprints\CP;

use WithCandour\AardvarkSeo\Blueprints\Blueprint as AardvarkBlueprint;
use Statamic\Facades\Blueprint as StatamicBlueprint;

class GeneralSettingsBlueprint implements AardvarkBlueprint
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
                            'handle' => 'titles_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Titles',
                                'listable' => 'hidden',
                                'instructions' => 'Control how your site titles appear',
                            ],
                        ],
                        [
                            'handle' => 'title_separator',
                            'field' => [
                                'type' => 'select',
                                'display' => 'Title Separator',
                                'instructions' => 'Set the character to separate the site and page names in the meta title',
                                'default' => '|',
                                'options' => [
                                    '|',
                                    '-',
                                    '~',
                                    '•',
                                    '/',
                                    '//',
                                    '»',
                                    '«',
                                    '>',
                                    '<',
                                    '*',
                                    '+',
                                ],
                                'width' => 33,
                            ],
                        ],
                        [
                            'handle' => 'site_name',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Website Name',
                                'instructions' => 'Set the name for the website. This will be used in generated meta titles as well as the OpenGraph site name property',
                                'width' => 66,
                            ],
                        ],
                        [
                            'handle' => 'favicon_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Favicon',
                                'listable' => 'hidden',
                                'instructions' => 'Upload a favicon to show in search results and the browser. It is recommended that your favicon is:<ul><li>A multiple of 48px square in dimensions</li><li>A supported favicon file format, we recommend using `.png`</li></ul>',
                            ],
                        ],
                        [
                            'handle' => 'global_favicon',
                            'field' => [
                                'type' => 'assets',
                                'max_files' => 1,
                                'restrict' => false,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                            ],
                        ],
                        [
                            'handle' => 'knowledge_graph_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Base Knowledge Graph Data',
                                'listable' => 'hidden',
                            ],
                        ],
                        [
                            'handle' => 'company_or_person',
                            'field' => [
                                'type' => 'radio',
                                'display' => 'Company or Person?',
                                'instructions' => 'Select whether the content on this website represents a company or a person',
                                'default' => 'company',
                                'inline' => true,
                                'options' => [
                                    'company' => 'Company',
                                    'person' => 'Person',
                                ],
                            ],
                        ],
                        [
                            'handle' => 'target_name',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Target Name',
                                'width' => 50,
                                'instructions' => 'Enter the person/company name here',
                            ],
                        ],
                        [
                            'handle' => 'company_logo',
                            'field' => [
                                'type' => 'assets',
                                'max_files' => 1,
                                'restrict' => false,
                                'width' => 50,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                                'if' => [
                                    'company_or_person' => 'equals company',
                                ],
                            ],
                        ],
                        [
                            'handle' => 'breadcrumbs_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Breadcrumbs',
                                'instructions' => 'Enable breadcrumbs schema for pages (see [https://developers.google.com/search/docs/data-types/breadcrumb](https://developers.google.com/search/docs/data-types/breadcrumb)).',
                                'listable' => 'hidden',
                            ],
                        ],
                        [
                            'handle' => 'enable_breadcrumbs',
                            'field' => [
                                'type' => 'toggle',
                                'display' => 'Enable Breadcrumbs?',
                                'default' => true,
                            ],
                        ],
                        [
                            'handle' => 'no_index_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'No Index',
                                'instructions' => 'Set to `true` to exclude the **whole site** from search engine indexing - this can also be configured on a per-page basis.',
                            ],
                        ],
                        [
                            'handle' => 'no_index_site',
                            'field' => [
                                'type' => 'toggle',
                                'display' => 'No Index',
                                'instructions' => 'Prevent indexing across the entire site.',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
