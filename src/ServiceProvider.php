<?php

namespace WithCandour\AardvarkSeo;

use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Events\EntryBlueprintFound;
use Statamic\Events\ResponseCreated;
use Statamic\Events\TermBlueprintFound;
use WithCandour\AardvarkSeo\Events\AardvarkContentDefaultsSaved;
use WithCandour\AardvarkSeo\Fieldtypes\AardvarkSeoMetaTitleFieldtype;
use WithCandour\AardvarkSeo\Fieldtypes\AardvarkSeoMetaDescriptionFieldtype;
use WithCandour\AardvarkSeo\Fieldtypes\AardvarkSeoGooglePreviewFieldtype;
use WithCandour\AardvarkSeo\Listeners\AppendEntrySeoFieldsListener;
use WithCandour\AardvarkSeo\Listeners\AppendTermSeoFieldsListener;
use WithCandour\AardvarkSeo\Listeners\DefaultsSitemapCacheInvalidationListener;
use WithCandour\AardvarkSeo\Listeners\Subscribers\SitemapCacheInvalidationSubscriber;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Controller as AardvarkSettingsController;
use WithCandour\AardvarkSeo\Http\Middleware\RedirectsMiddleware;
use WithCandour\AardvarkSeo\Modifiers\ParseLocaleModifier;
use WithCandour\AardvarkSeo\Tags\AardvarkSeoTags;
use Statamic\StaticSite\SSG;
use WithCandour\AardvarkSeo\Sitemaps\Sitemap;
use WithCandour\AardvarkSeo\Http\Controllers\Web\SitemapController as SitemapController;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        AardvarkSeoMetaTitleFieldtype::class,
        AardvarkSeoMetaDescriptionFieldtype::class,
        AardvarkSeoGooglePreviewFieldtype::class,
    ];

    protected $listen = [
        EntryBlueprintFound::class => [
            AppendEntrySeoFieldsListener::class,
        ],
        TermBlueprintFound::class => [
            AppendTermSeoFieldsListener::class,
        ],
        AardvarkContentDefaultsSaved::class => [
            DefaultsSitemapCacheInvalidationListener::class,
        ],
    ];

    protected $middlewareGroups = [
        'statamic.web' => [
            RedirectsMiddleware::class,
        ],
    ];

    protected $modifiers = [
        ParseLocaleModifier::class,
    ];

    protected $routes = [
        'cp' => __DIR__ . '/../routes/cp.php',
        'web' => __DIR__ . '/../routes/web.php',
    ];

    protected $scripts = [
        __DIR__ . '/../public/js/aardvark-seo.js',
    ];

    protected $stylesheets = [
        __DIR__ . '/../public/css/aardvark-seo.css',
    ];

    protected $subscribe = [
        SitemapCacheInvalidationSubscriber::class,
    ];

    protected $tags = [
        AardvarkSeoTags::class,
    ];

    public function boot()
    {
        parent::boot();

        // Set up views path
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'aardvark-seo');

        // Set up translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'aardvark-seo');

        // Load in custom addon config
        $this->mergeConfigFrom(__DIR__ . '/../config/aardvark-seo.php', 'aardvark-seo');
        $this->publishes([
            __DIR__ . '/../config/aardvark-seo.php' => config_path('aardvark-seo.php'),
        ], 'config');

        // Set up permissions
        $this->bootPermissions();

        // Set up navigation
        $this->bootNav();


        //set up ssg generation and generate sitemaps for static sites
        if (class_exists('Statamic\StaticSite\SSG')) {
            SSG::after(function () {
                info('hello');
                //call sitemap controller methods to generate sitemaps


                //check for settings set in ssg config for destination of static files
                $destination = config('statamic.ssg')['destination'];
                info($destination);

                $sitemaps = Sitemap::all();
                info($sitemaps);
                $response =  view('aardvark-seo::sitemaps.index_ssg', [
                    'xmlDefinition' => '<?xml version="1.0" encoding="utf-8"?>',
                    'sitemaps' => $sitemaps,
                    'version' => SitemapController::getAddonVersion(),
                ])->render();

                // info($response);
                //write results to file
                file_put_contents($destination . '/sitemap.xml', $response);

                foreach ($sitemaps as $sitemap) {
                    // (dump($sitemap));
                    $response = view('aardvark-seo::sitemaps.single_ssg', [
                        'xmlDefinition' => '<?xml version="1.0" encoding="utf-8"?>',
                        'data' => $sitemap->getSitemapItems(),
                        'version' => SitemapController::getAddonVersion(),
                    ])->render();
                    // info($response);
                    file_put_contents($destination . '/' . $sitemap->route, $response);
                }


                $path = __DIR__ . '/../resources/xsl/sitemap.xsl';
                // file_put_contents($destination . '
                file_put_contents($destination . '/aardvark-sitemap.xsl', file_get_contents($path));

                // Storage::putFile($destination, new File($path), 'sitemap.xsl');
            });
        }
    }

    /**
     * Add our custom navigation items to the CP nav
     *
     * @return void
     */
    public function bootNav()
    {
        $routeCollection = Route::getRoutes();

        // Add SEO item to nav
        Nav::extend(function ($nav) {
            // Top level SEO item
            $nav->create('SEO')
                ->can('configure aardvark settings')
                ->section('Tools')
                ->route('aardvark-seo.settings')
                ->icon('seo-search-graph')
                ->children([
                    // Settings categories
                    $nav->item(__('aardvark-seo::general.index'))
                        ->route('aardvark-seo.general.index')
                        ->can('view aardvark general settings'),
                    $nav->item(__('aardvark-seo::defaults.index'))
                        ->route('aardvark-seo.defaults.index')
                        ->can('view aardvark defaults settings'),
                    $nav->item(__('aardvark-seo::marketing.singular'))
                        ->route('aardvark-seo.marketing.index')
                        ->can('view aardvark marketing settings'),
                    $nav->item(__('aardvark-seo::social.singular'))
                        ->route('aardvark-seo.social.index')
                        ->can('view aardvark social settings'),
                    $nav->item(__('aardvark-seo::sitemap.singular'))
                        ->route('aardvark-seo.sitemap.index')
                        ->can('view aardvark sitemap settings'),
                ]);

            $nav->create(__('aardvark-seo::redirects.plural'))
                ->can('view aardvark redirects')
                ->section('Tools')
                ->route('aardvark-seo.redirects.index')
                ->icon('arrow-right')
                ->view('aardvark-seo::cp.nav.redirects')
                ->children([
                    $nav->item(__('aardvark-seo::redirects.manual.plural'))
                        ->can('view aardvark redirects')
                        ->route('aardvark-seo.redirects.manual-redirects.index'),
                ]);
        });
    }

    /**
     * Add permissions for AardvarkSEO settings
     *
     * @return void
     */
    public function bootPermissions()
    {
        $settings_groups = [
            [
                'value' => 'general',
                'label' => 'General',
            ],
            [
                'value' => 'marketing',
                'label' => 'Marketing',
            ],
            [
                'value' => 'social',
                'label' => 'Social',
            ],
            [
                'value' => 'sitemap',
                'label' => 'Sitemap',
            ],
            [
                'value' => 'defaults',
                'label' => 'Defaults',
            ],
        ];

        Permission::group('aardvark-seo', 'Aardvark SEO', function () use ($settings_groups) {
            Permission::register('configure aardvark settings', function ($permission) use ($settings_groups) {
                $permission->children([
                    Permission::make('view aardvark {settings_group} settings')
                        ->replacements('settings_group', function () use ($settings_groups) {
                            return collect($settings_groups)->map(function ($group) {
                                return [
                                    'value' => $group['value'],
                                    'label' => $group['label'],
                                ];
                            });
                        })
                        ->label('View :settings_group Settings')
                        ->children([
                            Permission::make('update aardvark {settings_group} settings')
                                ->label('Update :settings_group Settings'),
                        ]),
                    Permission::make('view aardvark redirects')
                        ->label(__('aardvark-seo::redirects.permissions.view'))
                        ->children([
                            Permission::make('edit aardvark redirects')
                                ->label(__('aardvark-seo::redirects.permissions.edit')),
                            Permission::make('create aardvark redirects')
                                ->label(__('aardvark-seo::redirects.permissions.create')),
                        ]),
                ]);
            })->label('Configure Aardvark Settings');
        });
    }
}
