<?php

return [

    'singular' => 'Social',
    'plural' => 'Socials',

    // CP
    'fields' => [
        'social_section' => [
            'display' => 'Social Media Settings',
            'instruct' => 'Put any related social media links in the table below.'
        ],
        'social_links' => [
            'display' => 'Social Media Links',
            'add_new' => 'Add a new social icon',
            'icon' => 'Social Icon',
            'url' => 'URL'
        ],
        'opengraph' => [
            'display' => 'OpenGraph',
        ],
        'og_image_site' => [
            'display' => 'Default OpenGraph share image',
            'instruct' => 'Upload a default image for when the site is shared on social media platforms which support OpenGraph. The recommended size is 1200px x 630px.'
        ],
        'twitter' => [
            'display' => 'Twitter',
        ],
        'twitter_username' => [
            'display' => 'Twitter Username',
            'instruct' => 'Your twitter username (including the @ symbol)'
        ],
        'twitter_meta_section_site' => [
            'display' => 'Twitter Share Data',
            'instruct' => 'This is the default data that will be used when this site is shared on Twitter. This data may be overridden at collection and/or page level.'
        ],
        'twitter_card_type_site' => [
            'display' => 'Card Type',
            'instruct' => 'Select which type of twitter card should be used throughout the site.'
        ],
        'twitter_default_image_section' => [
            'display' => 'Default Twitter Images',
            'instruct' => 'Upload default images to use when sharing pages on this site on Twitter.'
        ],
        'twitter_summary_image_site' => [
            'display' => 'Default Summary Image',
            'instruct' => 'Upload a default image to show on twitter when this page is shared. The recommended size is 240px x 240px.'
        ],
        'twitter_summary_large_image_site' => [
            'display' => 'Default Large Summary Image',
            'instruct' => 'Upload a default image to show on twitter when this page is shared using a large card. The recommended size is 876px x 438px.'
        ],
    ]

];
