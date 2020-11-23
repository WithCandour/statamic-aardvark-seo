<?php

Route::namespace('\WithCandour\AardvarkSeo\Http\Controllers\CP')
    ->prefix('aardvark-seo')
    ->name('aardvark-seo.')
    ->group(function () {

    Route::redirect('settings', 'settings/general')
        ->name('settings');

    Route::prefix('/settings')->group(function() {
        Route::resource('general', 'GeneralController')->only([
            'index', 'store'
        ]);

        Route::resource('sitemap', 'SitemapController')->only([
            'index', 'store'
        ]);

        Route::resource('marketing', 'MarketingController')->only([
            'index', 'store'
        ]);

        Route::resource('social', 'SocialController')->only([
            'index', 'store'
        ]);

        Route::namespace('Redirects')
            ->name('redirects.')
            ->prefix('/redirects')
            ->group(function() {
                Route::redirect('/', 'redirects/manual-redirects')->name('index');
                Route::resource('manual-redirects', 'ManualRedirectsController')->only([
                    'index', 'create', 'show', 'edit', 'update', 'store'
                ]);
                Route::resource('auto', 'AutoRedirectsController')->only([
                    'index', 'show'
                ]);
            });

        Route::resource('blueprints', 'BlueprintsController')->only([
            'index', 'store'
        ]);

        Route::resource('defaults', 'DefaultsController')->only([
            'index', 'edit', 'update'
        ]);

    });
});
