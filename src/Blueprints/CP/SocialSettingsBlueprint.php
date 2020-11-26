<?php

namespace WithCandour\AardvarkSeo\Blueprints\CP;

use WithCandour\AardvarkSeo\Blueprints\Blueprint as AardvarkBlueprint;
use Statamic\Facades\Blueprint as StatamicBlueprint;

class SocialSettingsBlueprint implements AardvarkBlueprint
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
                            'handle' => 'social_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Social Media Settings',
                                'instructions' => 'Put any related social media links in the table below.',
                                'listable' => 'hidden',
                            ],
                        ],
                        [
                            'handle' => 'social_links',
                            'field' => [
                                'type' => 'grid',
                                'display' => 'Social Media Links',
                                'add_row' => 'Add a new social icon',
                                'fields' => [
                                    [
                                        'handle' => 'social_icon',
                                        'field' => [
                                            'type' => 'select',
                                            'display' => 'Social Icon',
                                            'options' => self::getSocialOptions(),
                                        ],
                                    ],
                                    [
                                        'handle' => 'url',
                                        'field' => [
                                            'type' => 'text',
                                            'display' => 'URL',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'opengraph' => [
                    'display' => 'OpenGraph',
                    'fields' => [
                        [
                            'handle' => 'og_image_site',
                            'field' => [
                                'type' => 'assets',
                                'display' => 'Default OpenGraph share image',
                                'instructions' => 'Upload a default image for when the site is shared on social media platforms which support OpenGraph. The recommended size is 1200px x 630px.',
                                'max_files' => 1,
                                'restrict' => false,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                            ],
                        ],
                    ],
                ],
                'twitter' => [
                    'display' => 'Twitter',
                    'fields' => [
                        [
                            'handle' => 'twitter_username',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Twitter Username',
                                'instructions' => 'Your twitter username (including the @ symbol)',
                            ],
                        ],
                        [
                            'handle' => 'twitter_meta_section_site',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Twitter Share Data',
                                'instructions' => 'This is the default data that will be used when this site is shared on Twitter. This data may be overridden at collection and/or page level.',
                                'listable' => 'hidden',
                            ],
                        ],
                        [
                            'handle' => 'twitter_card_type_site',
                            'field' => [
                                'type' => 'select',
                                'display' => 'Card Type',
                                'instructions' => 'Select which type of twitter card should be used throughout the site.',
                                'width' => 50,
                                'default' => 'summary',
                                'options' => [
                                    'summary' => 'Summary Card',
                                    'summary_large_image' => 'Summary card with Large Image',
                                ],
                            ],
                        ],
                        [
                            'handle' => 'twitter_default_image_section',
                            'field' => [
                                'type' => 'section',
                                'display' => 'Default Twitter Images',
                                'instructions' => 'Upload default images to use when sharing pages on this site on Twitter.',
                                'listable' => 'hidden',
                            ],
                        ],
                        [
                            'handle' => 'twitter_summary_image_site',
                            'field' => [
                                'type' => 'assets',
                                'display' => 'Default Summary Image',
                                'instructions' => 'Upload a default image to show on twitter when this page is shared. The recommended size is 240px x 240px.',
                                'max_files' => 1,
                                'restrict' => false,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                                'width' => 50,
                            ],
                        ],
                        [
                            'handle' => 'twitter_summary_large_image_site',
                            'field' => [
                                'type' => 'assets',
                                'display' => 'Default Large Summary Image',
                                'instructions' => 'Upload a default image to show on twitter when this page is shared using a large card. The recommended size is 876px x 438px.',
                                'max_files' => 1,
                                'restrict' => false,
                                'container' => config('aardvark-seo.asset_container'),
                                'folder' => config('aardvark-seo.asset_folder'),
                                'width' => 50,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private static function getSocialOptions()
    {
        $defaults = collect([
            'amazon' => 'Amazon',
            'android' => 'Android',
            'apple' => 'Apple',
            'behance' => 'Behance',
            'bitbucket' => 'Bitbucket',
            'codepen' => 'Codepen',
            'dribbble' => 'Dribbble',
            'facebook' => 'Facebook',
            'flickr' => 'Flickr',
            'foursquare' => 'Foursquare',
            'github' => 'Github',
            'google-plus' => 'Google Plus',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'medium' => 'Medium',
            'meetup' => 'Meetup',
            'pinterest' => 'Pinterest',
            'reddit' => 'Reddit',
            'skype' => 'Skype',
            'slack' => 'Slack',
            'soundcloud' => 'Soundcloud',
            'spotify' => 'Spotify',
            'twitch' => 'Twitch',
            'twitter' => 'Twitter',
            'tumblr' => 'Tumblr',
            'whatsapp' => 'WhatsApp',
            'yelp' => 'Yelp',
            'youtube' => 'YouTube',
        ]);

        $config_options = collect(config('aardvark-seo.custom_socials'));

        return $config_options->merge($defaults)->sort()->toArray();
    }
}
