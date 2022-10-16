<?php

return [
    'asset_container' => 'assets',
    'asset_folder' => 'seo',
    'custom_socials' => [],
    'excluded_collections' => [],
    'excluded_taxonomies' => [],

    /*
    |--------------------------------------------------------------------------
    | Stache settings
    |--------------------------------------------------------------------------
    |
    | Control where the Aardvark SEO content files are stored.
    |
    */
    'stache' => [

        'stores' => [

            'aardvark-seo-globals' => [
                'class' => \WithCandour\AardvarkSeo\Stache\Stores\GlobalsStore::class,
                'directory' => base_path('content/aardvark-seo/globals')
            ],

        ]

    ]
];
