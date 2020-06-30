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

        Route::resource('blueprints', 'BlueprintsController')->only([
            'index', 'store'
        ]);

        Route::resource('defaults', 'DefaultsController')->only([
            'index', 'edit', 'update'
        ]);

    });
});
