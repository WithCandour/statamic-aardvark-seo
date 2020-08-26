<?php

use Statamic\Facades\Site;

Route::namespace('\WithCandour\AardvarkSeo\Http\Controllers\Web')
    ->name('aardvark-xml-sitemap')
    ->group(function() {
        Site::all()->each(function($site) {
            $path = $site->relativePath($site->url());
            Route::get("{$path}/sitemap.xml", 'SitemapController@index');
            Route::get("{$path}/sitemap_{handle}.xml", 'SitemapController@single');
            Route::get("{$path}/aardvark-sitemap.xsl", 'SitemapController@xsl');
        });
    });
