<?php

use Statamic\Facades\Site;

Route::namespace('\WithCandour\AardvarkSeo\Http\Controllers\Web')
    ->name('aardvark-xml-sitemap')
    ->group(function() {
        Route::get("sitemap.xml", 'SitemapController@index');
        Route::get("sitemap_{handle}.xml", 'SitemapController@single');
        Route::get("aardvark-sitemap.xsl", 'SitemapController@xsl');
    });
