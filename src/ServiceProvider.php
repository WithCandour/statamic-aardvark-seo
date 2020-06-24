<?php

namespace WithCandour\AardvarkSeo;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\CP\Nav;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        \WithCandour\AardvarkSeo\Tags\AardvarkSeoTags::class
    ];

    protected $routes = [
        'cp'  => __DIR__ . '/../routes/cp.php',
        'web' => __DIR__ . '/../routes/web.php'
    ];

    public function boot()
    {
        parent::boot();

        // Set up views path
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'aardvark-seo');

        // Set up translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'aardvark-seo');

        // Load in custom addon config
        $this->mergeConfigFrom(__DIR__ . '/../config/aardvark-seo.php', 'aardvark-seo');

        // Set up navigation
        $this->bootNav();
    }

    public function bootNav()
    {
        $routeCollection = Route::getRoutes();

        // Add SEO item to nav
        Nav::extend(function($nav) {

            // Top level SEO item
            $nav->create('SEO')
                ->section('Tools')
                ->route('aardvark-seo.settings')
                ->icon('seo-search-graph')
                ->children([
                    // Settings categories
                    $nav->item(__('aardvark-seo::general.index'))->route('aardvark-seo.general.index'),
                    $nav->item(__('aardvark-seo::sitemap.singular'))->route('aardvark-seo.sitemap.index')
                ]);

        });
    }
}
