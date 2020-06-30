<?php

use Statamic\Facades\Site;

Route::namespace('\WithCandour\AardvarkSeo\Http\Controllers\Web')
    ->name('aardvark-xml-sitemap')
    ->group(function() {
        Site::all()->each(function($site) {
            Route::get("{$site->url()}/sitemap.xml", 'SitemapController@index');
            Route::get("{$site->url()}/sitemap_type.xml", 'SitemapController@single');
            Route::get("{$site->url()}/aardvark-sitemap.xsl", 'SitemapController@xsl');
        });
    });
