<?php

return [
    'fields' => [
        'meta_section' => [
            'display' => 'Meta Data',
            'instruct' => 'Edit the meta data for this specific page.',
        ],
        'meta_title' => [
            'display' => 'Meta Title',
        ],
        'meta_description' => [
            'display' => 'Meta Description',
        ],
        'use_meta_keywords' => [
            'display' => 'Use Meta Keywords',
            'instruct' => 'You may toggle this field to use meta keywords on the page, however you should focus on creating quality content rather than using keywords.'
        ],
        'meta_keywords' => [
            'display' => 'Meta Keywords',
            'instruct' => 'Press enter to add a new keyword.'
        ],
        'google_preview' => [
            'display' => 'Google search preview',
        ],
        'urls_section' => [
            'display' => 'URL Options',
        ],
        'canonical_url' => [
            'display' => 'Canonical URL',
        ],
        'localized_urls' => [
            'display' => 'Alternate URLs',
            'instruct' => 'Create a list of urls for alternate locales.',
            'add_row' => 'Add a new locale',
            'fields' => [
                'locale' => [
                    'display' => 'Locale',
                ],
                'url' => [
                    'display' => 'URL'
                ]
            ]
        ],
        'indexing_section' => [
            'display' => 'Indexing and Sitemaps',
        ],
        'no_index_page' => [
            'display' => 'No Index',
            'instruct' => 'Prevent this page from being indexed by search engines.',
        ],
        'no_follow_links' => [
            'display' => 'Nofollow Links',
            'instruct' => 'Enabling this will prevent site crawlers from following on-page links.',
        ],
        'sitemap_priority' => [
            'display' => 'Sitemap Priority',
            'instruct' => 'Set the priorty of this page in the sitemap (1.0 being the most important).',
        ],
        'sitemap_changefreq' => [
            'display' => 'Change frequency',
            'instruct' => 'Set how often this page will change for the sitemap.',
        ],
        'share_section_og' => [
            'display' => 'OpenGraph Sharing Data',
            'instruct' => 'Control how this page looks when shared on websites which interpret OpenGraph data (Facebook, LinkedIn etc).',
        ],
        'og_title' => [
            'display' => 'OpenGraph Title',
        ],
        'og_description' => [
            'display' => 'OpenGraph Description',
        ],
        'og_image' => [
            'display' => 'OpenGraph Image',
        ],
        'share_section_twitter' => [
            'display' => 'Twitter Sharing Data',
            'instruct' => 'Control how this page looks when shared on Twitter, this data will automatically get inherited from the OG data but you may use the following fields to override for Twitter.',
        ],
        'twitter_title' => [
            'display' => 'Twitter Title',
        ],
        'twitter_description' => [
            'display' => 'Twitter Description',
        ],
        'twitter_card_type_page' => [
            'display' => 'Twitter Card Type',
            'instruct' => 'Select which type of twitter card should show when this page is shared.',
        ],
        'twitter_summary_image' => [
            'display' => 'Twitter Summary Card Image',
            'instruct' => 'Upload an image to show on twitter when this page is shared. The recommended size is 240px x 240px.',
        ],
        'twitter_summary_large_image' => [
            'display' => 'Twitter Summary Card Large Image',
            'instruct' => 'Upload an image to show on twitter when this page is shared. The recommended size is 876px x 438 px.',
        ],
        'scripts_section' => [
            'display' => 'Custom Scripts',
            'instruct' => 'Place any custom scripts in the following boxes to add them to this entry.'
        ],
        'head_snippets' => [
            'display' => 'Head Snippets',
            'instruct' => 'Custom snippets to get placed in the head, remember to wrap your scripts with `<script>` tags.',
        ],
        'footer_snippets' => [
            'display' => 'Footer Snippets',
            'instruct' => 'Custom snippets to get placed in the footer, remember to wrap your scripts with `<script>` tags.',
        ],
        'schema_objects' => [
            'display' => 'JSON-LD Schema',
            'instruct' => 'Paste your custom schema objects here (Recipe, Event etc...) - objects will need to be wrapped in `<script type="application/ld+json">` tags.',
        ],
    ]
];
