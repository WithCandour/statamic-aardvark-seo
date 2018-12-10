<?php

namespace Statamic\Addons\SeoBox;

use Statamic\API\File;
use Statamic\API\Nav;
use Statamic\API\YAML;
use Statamic\Extend\Listener;
use Stataimc\Events\Event;
use Stataimc\Events\RoutesMapping;

use Statamic\Addons\SeoBox\Controllers\SeoBoxController;
use Statamic\Addons\SeoBox\Controllers\RedirectsController;
use Statamic\Addons\SeoBox\Controllers\SitemapController;
use Statamic\Addons\SeoBox\Traits\TransformsAssetsFieldtypes;
use Statamic\Addons\SeoBox\Traits\PopulatesDefaultFields;
use Statamic\Addons\SeoBox\Sitemaps\Sitemap;

/**
 * Listener to add our custom data to the cp
 */
class SeoBoxListener extends Listener
{

  use TransformsAssetsFieldtypes;
  use PopulatesDefaultFields;


  public $events = [
    'cp.nav.created' => 'addSeoNavItems',
    'cp.add_to_head' => "injectSEOBoxStyles",
    'Statamic\Events\Data\PublishFieldsetFound' => 'appendOnPageSeoFields',
    'Statamic\Events\RoutesMapping' => 'addSitemapRoutes',
    'Statamic\Events\Data\PageMoved' => 'clearPageSitemapCaches',
    'Statamic\Events\Data\ContentSaved' => 'clearSitemapCaches',
    'Statamic\Events\Data\PageMoved' => 'handlePageMovedRedirect',
    'Statamic\Events\Data\PageSaved' => 'handlePageSavedRedirect',
    'Statamic\Events\Data\EntrySaved' => 'handleDataSavedRedirect',
    'Statamic\Events\Data\TermSaved' => 'handleDataSavedRedirect'
  ];

  /**
   * Add our SEO 'globals' to the left nav
   *
   * @param Statamic\CP\Navigation\Nav $nav The navigation object
   * @return null;
   */
  public function addSeoNavItems($nav) {

    $seo_section = Nav::item('seobox')->title('SEO')->route('seo-box')->icon('line-graph');

    $seo_section->add(function ( $item ) {
      $item->add(Nav::item('General')->route('seo-box.general'));
      $item->add(Nav::item('Marketing')->route('seo-box.marketing'));
      $item->add(Nav::item('Social')->route('seo-box.social'));
      $item->add(Nav::item('Redirects')->route('seo-box.redirects')->badge('BETA'));
      $item->add(Nav::item('Sitemap')->route('seo-box.sitemap'));
    });

    $nav->addTo('tools', $seo_section);

  }

  /**
   * Append SEO fields to pages, entries and taxonomies
   *
   * @param Stataimc\Events\Event $event The event that fired
   * @return null
   */
  public function appendOnPageSeoFields($event)
  {

    if(!in_array($event->type, ['entry', 'page', 'term'])) {
      return;
    }

    $seoFieldsetContents = File::get($this->getDirectory() . "/fieldsets/onpage-seo.yaml");
    $fields = YAML::parse($seoFieldsetContents)['fields'];
    $assetContainer = $this->getConfig('asset_container') ?: 'main';

    $processedFields = $this->transformAssetsFields($fields, $assetContainer);
    $populatedFields = $this->populateDefaults($processedFields, $this->storage);

    $fieldset = $event->fieldset;
    $fieldsetSections = $fieldset->sections();

    $fieldsetSections['SEO'] = [
      'fields' => $populatedFields
    ];

    $fieldsetContents = $fieldset->contents();
    $fieldsetContents['sections'] = $fieldsetSections;

    $fieldset->contents($fieldsetContents);

  }

  /**
   * Inject the SeoBox stylesheet
   *
   * @return void
   */
  public function injectSEOBoxStyles()
  {
    $stylesheet = $this->css->url('seo-box.css');
    $tag = '<link rel="stylesheet" type="text/css" href="' . $stylesheet . '">';
    return $tag;
  }

  /**
   * Add the dynamic route for the sitemap and point it to
   * the controller method
   *
   * @param Stataimc\Events\RoutesMapping $event The event that fired
   * @return null
   */
  public function addSitemapRoutes($event)
  {
    $store = collect($this->storage->getYAML(SitemapController::STORAGE_KEY));

    if($store->get('enable_sitemap')) {
      $url = $store->get('sitemap_url');

      $event->router->get($url, 'Statamic\Addons\SeoBox\Controllers\SitemapController@renderSitemapIndex');

      // Add a catch-all for our sitemap single routes
      $event->router->get(SitemapController::SINGLE_ROUTE, 'Statamic\Addons\SeoBox\Controllers\SitemapController@renderSingleSitemap');

      // Handle the asset route to load our custom xsl
      $event->router->get('seo-sitemap.xsl', 'Statamic\Addons\SeoBox\Controllers\SitemapController@getSitemapStyles');
    }
  }

  /**
   * Clear the sitemap caches when content is saved
   * @param Statamic\Events\Data\ContentSaved $event
   */
  public function clearSitemapCaches($event)
  {
    return SitemapController::clearCacheBasedOnDataObject($event->data);
  }

  /**
   * Clear the pages sitemap cache
   * @param Statamic\Events\Data\PageMoved $event
   */
  public function clearPageSitemapCaches($event)
  {
    return $this->clearSitemapCacheByHandle('pages');
  }

  /**
   * Clear an individual sitemap cache
   * @param string $handle
   */
  private function clearSitemapCacheByHandle($handle)
  {
    return SitemapController::clearCacheByHandle($handle);
  }

  /**
   * Check whether the site admin has opted-in for automatic redirects
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
   * Handler for PageMoved events
   * @param Statamic\Events\Data\PageMoved $event
   */
  public function handlePageMovedRedirect($event)
  {
    return $this->autoRedirectsEnabled() ? RedirectsController::createRedirectFromPageMoved($event) : null;
  }

  /**
   * Handler for PageSaved events
   * @param Statamic\Events\Data\PageSaved
   */
  public function handlePageSavedRedirect($event)
  {
    return $this->autoRedirectsEnabled() ? RedirectsController::createRedirectFromPageSaved($event) : null;
  }

  /**
   * Handle events emitted when Entries or Terms are saved
   * @param Statamic\Events\Data\ContentSaved
   */
  public function handleDataSavedRedirect($event)
  {
    return $this->autoRedirectsEnabled() ? RedirectsController::createRedirectFromDataSaved($event) : null;
  }

}
