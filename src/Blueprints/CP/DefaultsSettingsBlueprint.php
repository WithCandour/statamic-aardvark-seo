<?php

namespace WithCandour\AardvarkSeo\Blueprints\CP;

use WithCandour\AardvarkSeo\Blueprints\Blueprint as AardvarkBlueprint;
use Statamic\Facades\Blueprint as StatamicBlueprint;

class DefaultsSettingsBlueprint implements AardvarkBlueprint
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
                            'handle' => 'meta_section',
                            'field' => [
                                'type' => 'section',
                                'listable' => 'hidden',
                                'display' => __('aardvark-seo::onpage.fields.meta_section.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.meta_section.instruct'),
                            ]
                        ],
                        [
                            'handle' => 'meta_title',
                            'field' => [
                                'type' => 'text',
                                'display' => __('aardvark-seo::onpage.fields.meta_title.display'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'meta_description',
                            'field' => [
                                'type' => 'textarea',
                                'display' => __('aardvark-seo::onpage.fields.meta_description.display'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'use_meta_keywords',
                            'field' => [
                                'type' => 'toggle',
                                'display' => __('aardvark-seo::onpage.fields.use_meta_keywords.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.use_meta_keywords.instruct'),
                                'localizable' => true,
                                'width' => 50
                            ]
                        ],
                        [
                            'handle' => 'meta_keywords',
                            'field' => [
                                'type' => 'tags',
                                'display' => __('aardvark-seo::onpage.fields.meta_keywords.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.meta_keywords.instruct'),
                                'localizable' => true,
                                'width' => 50,
                                'if' => [
                                    'use_meta_keywords' => 'equals true'
                                ]
                            ]
                        ],
                        [
                            'handle' => 'indexing_section',
                            'field' => [
                                'type' => 'section',
                                'display' => __('aardvark-seo::onpage.fields.indexing_section.display'),
                            ]
                        ],
                        [
                            'handle' => 'no_index_page',
                            'field' => [
                                'type' => 'toggle',
                                'display' => __('aardvark-seo::onpage.fields.no_index_page.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.no_index_page.instruct'),
                                'width' => 50,
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'no_follow_links',
                            'field' => [
                                'type' => 'toggle',
                                'display' => __('aardvark-seo::onpage.fields.no_follow_links.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.no_follow_links.instruct'),
                                'width' => 50,
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'sitemap_priority',
                            'field' => [
                                'type' => 'select',
                                'display' => __('aardvark-seo::onpage.fields.sitemap_priority.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.sitemap_priority.instruct'),
                                'default' => '0.5',
                                'width' => 50,
                                'localizable' => true,
                                'options' => [
                                    '0.0',
                                    '0.1',
                                    '0.2',
                                    '0.3',
                                    '0.4',
                                    '0.5',
                                    '0.6',
                                    '0.7',
                                    '0.8',
                                    '0.9',
                                    '1.0'
                                ]
                            ]
                        ],
                        [
                            'handle' => 'sitemap_changefreq',
                            'field' => [
                                'type' => 'select',
                                'display' => __('aardvark-seo::onpage.fields.sitemap_changefreq.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.sitemap_changefreq.instruct'),
                                'default' => 'daily',
                                'width' => 50,
                                'localizable' => true,
                                'options' => [
                                    'always',
                                    'hourly',
                                    'daily',
                                    'weekly',
                                    'monthly',
                                    'yearly',
                                    'never'
                                ]
                            ]
                        ],
                        [
                            'handle' => 'share_section_og',
                            'field' => [
                                'type' => 'section',
                                'display' => __('aardvark-seo::onpage.fields.share_section_og.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.share_section_og.instruct'),
                            ]
                        ],
                        [
                            'handle' => 'og_title',
                            'field' => [
                                'type' => 'text',
                                'display' => __('aardvark-seo::onpage.fields.og_title.display'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'og_description',
                            'field' => [
                                'type' => 'textarea',
                                'display' => __('aardvark-seo::onpage.fields.og_description.display'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'og_image',
                            'field' => [
                                'type' => 'assets',
                                'display' => __('aardvark-seo::onpage.fields.og_image.display'),
                                'max_files' => 1,
                                'restrict' => false,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                                'localizable' => true,
                            ]
                        ],
                        [
                            'handle' => 'share_section_twitter',
                            'field' => [
                                'type' => 'section',
                                'display' => __('aardvark-seo::onpage.fields.share_section_twitter.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.share_section_twitter.instruct'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'twitter_title',
                            'field' => [
                                'type' => 'text',
                                'display' => __('aardvark-seo::onpage.fields.twitter_title.display'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'twitter_description',
                            'field' => [
                                'type' => 'textarea',
                                'display' => __('aardvark-seo::onpage.fields.twitter_description.display'),
                            ]
                        ],
                        [
                            'handle' => 'twitter_card_type_page',
                            'field' => [
                                'type' => 'select',
                                'display' => __('aardvark-seo::onpage.fields.twitter_card_type_page.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.twitter_card_type_page.instruct'),
                                'localizable' => true,
                                'default' => 'summary',
                                'width' => 50,
                                'options' => [
                                    'summary' => 'Summary Card',
                                    'summary_large_image' => 'Summary Card with Large Image'
                                ]
                            ]
                        ],
                        [
                            'handle' => 'twitter_summary_image',
                            'field' => [
                                'type' => 'assets',
                                'display' => __('aardvark-seo::onpage.fields.twitter_summary_image.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.twitter_summary_image.instruct'),
                                'max_files' => 1,
                                'restrict' => false,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                                'localizable' => true,
                                'if' => [
                                    'twitter_card_type_page' => 'equals summary'
                                ]
                            ]
                        ],
                        [
                            'handle' => 'twitter_summary_large_image',
                            'field' => [
                                'type' => 'assets',
                                'display' => __('aardvark-seo::onpage.fields.twitter_summary_large_image.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.twitter_summary_large_image.instruct'),
                                'max_files' => 1,
                                'restrict' => false,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                                'localizable' => true,
                                'if' => [
                                    'twitter_card_type_page' => 'equals summary_large_image'
                                ]
                            ]
                        ],
                        [
                            'handle' => 'scripts_section',
                            'field' => [
                                'type' => 'section',
                                'display' => __('aardvark-seo::onpage.fields.scripts_section.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.scripts_section.instruct'),
                            ]
                        ],
                        [
                            'handle' => 'head_snippets',
                            'field' => [
                                'type' => 'textarea',
                                'display' => __('aardvark-seo::onpage.fields.head_snippets.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.head_snippets.instruct'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'footer_snippets',
                            'field' => [
                                'type' => 'textarea',
                                'display' => __('aardvark-seo::onpage.fields.footer_snippets.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.footer_snippets.instruct'),
                                'localizable' => true
                            ]
                        ],
                        [
                            'handle' => 'schema_objects',
                            'field' => [
                                'type' => 'textarea',
                                'display' => __('aardvark-seo::onpage.fields.schema_objects.display'),
                                'instructions' => __('aardvark-seo::onpage.fields.schema_objects.instruct'),
                                'rows' => 10,
                                'localizable' => true
                            ]
                        ],
                    ]
                ]
            ]
        ]);
    }
}
