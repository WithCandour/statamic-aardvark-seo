<?php

use Statamic\Facades\Site;
use Statamic\Facades\URL;

Route::namespace('\WithCandour\AardvarkSeo\Http\Controllers\Web')
    ->name('aardvark-xml-sitemap')
    ->group(function () {
        Route::get('sitemap.xml', 'SitemapController@index')
            ->name('index');
        Route::get('sitemap_{handle}.xml', 'SitemapController@single')
            ->name('single');
        Route::get('aardvark-sitemap.xsl', 'SitemapController@xsl')
            ->name('xsl');

        // Add sitemap routes for non-domain-level multisites
        $roots = Site::all()->map(function ($site) {
            return URL::makeRelative($site->url());
        })->filter(function ($root) {
            return $root !== '/';
        })->unique();

        $roots->each(function ($root) {
            Route::get("${root}/sitemap.xml", 'SitemapController@index')
                ->name("${root}.index");
            Route::get("${root}/sitemap_{handle}.xml", 'SitemapController@single')
                ->name("${root}.single");
            Route::get("${root}/aardvark-sitemap.xsl", 'SitemapController@xsl')
                ->name("${root}.xsl");
        });
    });
