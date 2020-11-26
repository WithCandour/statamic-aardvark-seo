<?php

return [

    'singular' => 'Sitemap',
    'plural' => 'Sitemaps',

    // CP
    'fields' => [
        'enable_sitemap' => [
            'display' => 'Enable Sitemap?',
        ],
        'sitemap_cache_expiration' => [
            'display' => 'Sitemap Cache Expiration',
            'instruct' => 'Set the amount of time before the sitemap should be regenerated.',
        ],
        'exclude_content_section' => [
            'display' => 'Exclude Content',
        ],
        'exclude_collections' => [
            'display' => 'Exclude Collections',
            'instruct' => 'Select collections which you would like to exclude from the sitemap.',
        ],
        'exclude_taxonomies' => [
            'display' => 'Exclude Taxonomies',
            'instruct' => 'Select taxonomies which you would like to exclude from the sitemap.',
        ],
    ]
];
