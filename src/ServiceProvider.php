<?php

namespace WithCandour\AardvarkSeo;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Illuminate\Support\Facades\Route;
use Statamic\Events\Data\PublishBlueprintFound;
use Statamic\Events\Data\BlueprintFoundOnFile;
use WithCandour\AardvarkSeo\Listeners\AppendSeoFieldsListener;
use WithCandour\AardvarkSeo\Console\Commands\BlueprintsUpdate;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Controller as AardvarkSettingsController;
use WithCandour\AardvarkSeo\Policies\AardvarkSettingsPolicy;

class ServiceProvider extends AddonServiceProvider
{

    protected $commands = [
        BlueprintsUpdate::class
    ];

    protected $tags = [
        \WithCandour\AardvarkSeo\Tags\AardvarkSeoTags::class
    ];

    protected $routes = [
        'cp'  => __DIR__ . '/../routes/cp.php',
        'web' => __DIR__ . '/../routes/web.php'
    ];

    // TODO: Make this work with the controller methods
    // protected $policies = [
    //     AardvarkSettingsController::class => AardvarkSettingsPolicy::class
    // ];

    /**
     * We're manually adding the fields to blueprints at the mo' as there's no way
     * to save the custom data against the entry
     *
     * https://github.com/statamic/cms/pull/1990/files
     */
    // protected $listen = [
    //     PublishBlueprintFound::class => [
    //         AppendSeoFieldsListener::class
    //     ]
    // ];

    public function boot()
    {
        parent::boot();

        // Set up views path
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'aardvark-seo');

        // Set up translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'aardvark-seo');

        // Load in custom addon config
        $this->mergeConfigFrom(__DIR__ . '/../config/aardvark-seo.php', 'aardvark-seo');

        // Set up permissions
        $this->bootPermissions();

        // Set up navigation
        $this->bootNav();
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
        Nav::extend(function($nav) {

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
                    $nav->item(__('aardvark-seo::sitemap.singular'))
                        ->route('aardvark-seo.sitemap.index')
                        ->can('view aardvark sitemap settings'),
                    $nav->item(__('aardvark-seo::blueprint.plural'))
                        ->route('aardvark-seo.blueprints.index')
                        ->can('view aardvark blueprints settings'),
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
                'label' => 'General'
            ],
            [
                'value' => 'marketing',
                'label' => 'Marketing'
            ],
            [
                'value' => 'sitemap',
                'label' => 'Sitemap'
            ],
            [
                'value' => 'defaults',
                'label' => 'Defaults'
            ],
            [
                'value' => 'blueprints',
                'label' => 'Blueprints'
            ],
        ];

        Permission::group('aardvark-seo', 'Aardvark SEO', function () use ($settings_groups) {
            Permission::register('configure aardvark settings', function ($permission) use ($settings_groups) {
                $permission->children([
                        Permission::make('view aardvark {settings_group} settings')
                            ->replacements('settings_group', function() use ($settings_groups) {
                                return collect($settings_groups)->map(function ($group) {
                                    return [
                                        'value' => $group['value'],
                                        'label' => $group['label']
                                    ];
                                });
                            })
                            ->label('View :settings_group Settings')
                            ->children([
                                Permission::make('update aardvark {settings_group} settings')
                                    ->label('Update :settings_group Settings')
                            ]),

                ]);
            })->label('Configure Aardvark Settings');
        });
    }
}
