<?php

return [

    'singular' => 'General',
    'plural' => 'Generals',

    // Control Panel
    'index' => 'General Settings',

    'fields' => [
        'titles_section' => [
            'display' => 'Titles',
            'instruct' => 'Control how your site titles appear',
        ],
        'title_separator' => [
            'display' => 'Title Separator',
            'instruct' => 'Set the character to separate the site and page names in the meta title'
        ],
        'site_name' => [
            'display' => 'Website Name',
            'instruct' => 'Set the name for the website. This will be used in generated meta titles as well as the OpenGraph site name property'
        ],
        'favicon_section' => [
            'display' => 'Favicon',
            'instruct' => 'Upload a favicon to show in search results and the browser. It is recommended that your favicon is:<ul><li>A multiple of 48px square in dimensions</li><li>A supported favicon file format, we recommend using `.png`</li></ul>'
        ],
        'global_favicon' => [
            'display' => 'Global Favicon',
        ],
        'knowledge_graph_section' => [
            'display' => 'Base Knowledge Graph Data',
        ],
        'company_or_person' => [
            'display' => 'Company or Person?',
            'instruct' => 'Select whether the content on this website represents a company or a person'
        ],
        'target_name' => [
            'display' => 'Target Name',
            'instruct' => 'Enter the person/company name here'
        ],
        'company_logo' => [
            'display' => 'Company Logo',
        ],
        'breadcrumbs_section' => [
            'display' => 'Breadcrumbs',
            'instruct' => 'Enable breadcrumbs schema for pages (see [https://developers.google.com/search/docs/data-types/breadcrumb](https://developers.google.com/search/docs/data-types/breadcrumb)).',
        ],
        'enable_breadcrumbs' => [
            'display' => 'Enable Breadcrumbs?',
        ],
        'no_index_section' => [
            'display' => 'No Index',
            'instruct' => 'Set to `true` to exclude the **whole site** from search engine indexing - this can also be configured on a per-page basis.',
        ],
        'no_index_site' => [
            'display' => 'No Index',
            'instruct' => 'Prevent indexing across the entire site.',
        ],
    ]

];
