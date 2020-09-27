<?php

namespace Statamic\Addons\AardvarkSeo;

use Stataimc\Events\Event;
use Statamic\Addons\AardvarkSeo\Controllers\Controller as AardvarkController;
use Statamic\Addons\AardvarkSeo\Controllers\RedirectsController;
use Statamic\Addons\AardvarkSeo\Controllers\SitemapController;
use Statamic\Addons\AardvarkSeo\Controllers\DefaultsController;
use Statamic\Addons\AardvarkSeo\Sitemaps\Sitemap;
use Statamic\Addons\AardvarkSeo\Traits\TransformsAssetsFieldtypes;
use Statamic\API\File;
use Statamic\API\Nav;
use Statamic\API\YAML;
use Statamic\API\Config;
use Statamic\API\Role;
use Statamic\API\User;
use Statamic\Extend\Listener;

/**
 * Listener to add our custom data to the cp.
 */
class AardvarkSeoListener extends Listener
{
    use TransformsAssetsFieldtypes;

    public $events = [
        'cp.nav.created' => 'addSeoNavItems',
        'cp.add_to_head' => 'injectAardvarkSeoStyles',
        'Statamic\Events\Data\PublishFieldsetFound' => 'appendOnPageSeoFields',
        'Statamic\Events\RoutesMapping' => 'addSitemapRoutes',
        'Statamic\Events\Data\PageMoved' => 'clearPageSitemapCaches',
        'Statamic\Events\Data\ContentSaved' => 'clearSitemapCaches',
        'Statamic\Events\Data\PageMoved' => 'handlePageMovedRedirect',
        'Statamic\Events\Data\PageSaved' => 'handlePageSavedRedirect',
        'Statamic\Events\Data\EntrySaved' => 'handleDataSavedRedirect',
        'Statamic\Events\Data\TermSaved' => 'handleDataSavedRedirect',
    ];

    /**
     * Add our SEO 'globals' to the left nav.
     *
     * @param Statamic\CP\Navigation\Nav $nav The navigation object
     *
     * @return void;
     */
    public function addSeoNavItems($nav)
    {
        // Only super-users and users with the role seo can access this
        $user = User::getCurrent();
		$role = Role::whereHandle('seo');

        if ($user->hasRole($role) || $user->isSuper()) {
            $seo_section = Nav::item('aardvark-seo')->title('SEO')->route('aardvark-seo')->icon('line-graph');

            $errors = AardvarkController::getErrors();
            if (count($errors)) {
                $seo_section->badge(count($errors));
            }

            $seo_section->add(function ($item) {
                $item->add(Nav::item('General')->route('aardvark-seo.general'));
                $item->add(Nav::item('Content defaults')->route('aardvark-seo.defaults'));
                $item->add(Nav::item('Marketing')->route('aardvark-seo.marketing'));
                $item->add(Nav::item('Social')->route('aardvark-seo.social'));
                $item->add(Nav::item('Redirects')->route('aardvark-seo.redirects')->badge('BETA'));
                $item->add(Nav::item('Sitemap')->route('aardvark-seo.sitemap'));
            });

            $nav->addTo('tools', $seo_section);
        }
    }

    /**
     * Append SEO fields to pages, entries and taxonomies.
     *
     * @param Stataimc\Events\Event $event The event that fired
     */
    public function appendOnPageSeoFields($event)
    {
        if (!in_array($event->type, ['entry', 'page', 'term'])) {
            return;
        }

        $fieldset = $event->fieldset;
        $hidden = collect($fieldset->toArray())->get('hide_aardvark_seo');
        if ($hidden) {
            return;
        }

        $seoFieldsetContents = File::get($this->getDirectory() . '/fieldsets/onpage-seo.yaml');
        $fields = YAML::parse($seoFieldsetContents)['fields'];
        $assetContainer = $this->getConfig('asset_container') ?: 'main';
        $processedFields = $this->transformAssetsFields($fields, $assetContainer);

        $ctx = [
            'page_object' => $event->data,
        ];

        if ($event->data) {
            switch ($event->type) {
                case 'entry':
                    $collection = $event->data->collection();
                    $ctx['collection'] = $collection->path();
                    break;
                case 'term':
                    $taxonomy = $event->data->taxonomy();
                    $ctx['taxonomy'] = $taxonomy->path();
                    break;
            }
        }

        $defaults = collect($this->getDefaults(collect($ctx), Config::getDefaultLocale()));
        $localisedDefaults = $defaults->merge($this->getDefaults(collect($ctx), site_locale()));

        $processedFields = collect($processedFields)->map(function ($field, $name) use ($localisedDefaults) {
            $field['placeholder'] = $localisedDefaults->get($name, '');
            return $field;
        });

        $fieldsetSections = $fieldset->sections();

        $fieldsetSections['SEO'] = [
            'fields' => $processedFields,
        ];

        $fieldsetContents = $fieldset->contents();
        $fieldsetContents['sections'] = $fieldsetSections;

        $fieldset->contents($fieldsetContents);
    }

    /**
     * Inject the AardvarkSeo stylesheet.
     */
    public function injectAardvarkSeoStyles()
    {
        $stylesheet = $this->css->url('aardvark-seo.css');
        $tag = '<link rel="stylesheet" type="text/css" href="' . $stylesheet . '">';
        return $tag;
    }

    /**
     * Determine whether sitemaps have been enabled for this site, this ensures
     * we aren't relying on the settings being saved by the site author
     *
     * @param Illuminate\Support\Collection
     *
     * @return boolean
     */
    private function testSitemapsAreEnabled($store)
    {
        $storage_key = 'enable_sitemap';
        if ($store->contains($storage_key)) {
            return $store->get($storage_key);
        }

        return true;
    }

    /**
     * Add the dynamic route for the sitemap and point it to
     * the controller method.
     *
     * @param Stataimc\Events\RoutesMapping $event The event that fired
     */
    public function addSitemapRoutes($event)
    {
        $store = collect($this->storage->getYAML(SitemapController::STORAGE_KEY));

        $sitemaps_enabled = $this->testSitemapsAreEnabled($store);

        if ($sitemaps_enabled) {
            $url = $store->get('sitemap_url') ?: 'sitemap.xml';

            $event->router->get($url, 'Statamic\Addons\AardvarkSeo\Controllers\SitemapController@renderSitemapIndex');

            // Add a catch-all for our sitemap single routes
            $event->router->get(SitemapController::SINGLE_ROUTE, 'Statamic\Addons\AardvarkSeo\Controllers\SitemapController@renderSingleSitemap');

            // Handle the asset route to load our custom xsl
            $event->router->get('seo-sitemap.xsl', 'Statamic\Addons\AardvarkSeo\Controllers\SitemapController@getSitemapStyles');
        }
    }

    /**
     * Clear the sitemap caches when content is saved.
     *
     * @param Statamic\Events\Data\ContentSaved $event
     */
    public function clearSitemapCaches($event)
    {
        return SitemapController::clearCacheBasedOnDataObject($event->data);
    }

    /**
     * Clear the pages sitemap cache.
     *
     * @param Statamic\Events\Data\PageMoved $event
     */
    public function clearPageSitemapCaches($event)
    {
        return $this->clearSitemapCacheByHandle('pages');
    }

    /**
     * Clear an individual sitemap cache.
     *
     * @param string $handle
     */
    private function clearSitemapCacheByHandle($handle)
    {
        return SitemapController::clearCacheByHandle($handle);
    }

    /**
     * Check whether the site admin has opted-in for automatic redirects.
     *
     * @return bool
     */
    private function autoRedirectsEnabled()
    {
        $requiredAttr = 'create_auto_redirects';
        $storage = $this->storage->getYAML(RedirectsController::STORAGE_KEY);
        $isEnabled = collect($storage)->get($requiredAttr);
        return $isEnabled;
    }

    /**
     * Forward a call to the defaults controller getDefaults method
     *
     * @param array $ctx
     * @param string $locale
     *
     * @return array
     */
    private function getDefaults($ctx, $locale)
    {
        return DefaultsController::getDefaults($ctx, $locale);
    }

    /**
     * Handler for PageMoved events.
     *
     * @param Statamic\Events\Data\PageMoved $event
     */
    public function handlePageMovedRedirect($event)
    {
        return $this->autoRedirectsEnabled() ? RedirectsController::createRedirectFromPageMoved($event) : null;
    }

    /**
     * Handler for PageSaved events.
     *
     * @param Statamic\Events\Data\PageSaved
     */
    public function handlePageSavedRedirect($event)
    {
        return $this->autoRedirectsEnabled() ? RedirectsController::createRedirectFromPageSaved($event) : null;
    }

    /**
     * Handle events emitted when Entries or Terms are saved.
     *
     * @param Statamic\Events\Data\ContentSaved
     */
    public function handleDataSavedRedirect($event)
    {
        return $this->autoRedirectsEnabled() ? RedirectsController::createRedirectFromDataSaved($event) : null;
    }
}
