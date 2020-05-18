<?php

namespace Statamic\Addons\AardvarkSeo\Controllers;

use Illuminate\Http\Request;
use Statamic\Events\Data\ContentSaved;
use Statamic\API\Collection;
use Statamic\API\Entry;
use Statamic\API\Page;
use Statamic\API\PageFolder;
use Statamic\API\Taxonomy;
use Statamic\API\Term;
use Statamic\API\Config;
use Statamic\Events\Data\CollectionSaved;
use Statamic\Events\Data\TaxonomySaved;
use Statamic\Events\Data\SettingsSaved;
use Statamic\CP\Publish\ProcessesFields;
use Statamic\Addons\AardvarkSeo\Controllers\SitemapController;

class DefaultsController extends Controller
{
    use ProcessesFields;

    public function index()
    {
        return $this->view('defaults_index', [
            'title' => 'Content defaults',
            'contentTypes' => $this->getContentTypes(),
        ]);
    }

    public function single(Request $request, $type, $slug)
    {
        $object = $this->getDataObject($type, $slug);
        switch ($type) {
            case 'collections':
                $title = 'Collection: ' . $object->title();
                break;
            case 'taxonomies':
                $title = 'Taxonomy: ' . $object->title();
                break;
            case 'pages':
                $title = 'Pages';
                break;
            default:
                $title = 'None';
        }

        $fieldset = $this->createAddonFieldset('defaults');

        $stored_data = $object->get('aardvark_' . $request->locale) ?: $object->get('aardvark_' . Config::getDefaultLocale());

        $data = $this->preProcessWithBlankFields(
            $fieldset,
            $stored_data
        );

        return $this->view('defaults_single', [
            'data' => $data,
            'fieldset' => $fieldset->toPublishArray(),
            'locale' => $request->query('locale', site_locale()),
            'locales' => $this->getLocales(),
            'slug' => $slug,
            'submitUrl' => route('aardvark-seo.defaults.save', [$type, $slug]),
            'title' => $title,
            'type' => $type,
        ]);
    }

    /**
     * Save the current form into the aardvark defaults section
     * of the data object
     *
     * @param Request $request
     * @param string $type
     * @param string $slug
     */
    public function save(Request $request, $type, $slug)
    {
        $data = $this->processFields($this->createAddonFieldset($request->fieldset), $request->fields);
        $object = $this->getDataObject($type, $slug);
        $object->set('aardvark_' . $request->locale, $data);
        $object->save();

        switch ($type) {
            case 'collections':
                event(new CollectionSaved($object));
                break;
            case 'taxonomies':
                event(new TaxonomySaved($object));
                break;
            case 'pages':
                // In the absence of a PagesFolderSaved event use the settings saved event
                event(new SettingsSaved($object->path(), $object->data()));
                break;
        }

        // Clear the sitemap index cache
        SitemapController::clearIndexCache($request->locale);

        return $this->successResponse('aardvark-seo.defaults');
    }

    /**
     * Get the data object for which this request pertains to
     *
     * @param string $type
     * @param string $slug
     *
     * @return Statamic\Data\DataFolder
     */
    private function getDataObject($type, $slug)
    {
        switch ($type) {
            case 'collections':
                $object = Collection::whereHandle($slug);
                break;
            case 'taxonomies':
                $object = Taxonomy::whereHandle($slug);
                break;
            case 'pages':
                $object = PageFolder::whereHandle('/') ?: PageFolder::create();
                $object->path('/');
        }
        return $object;
    }

    /**
     * Return a list of all possible content types for which we can set the defaults
     *
     * @return array
     */
    private function getContentTypes()
    {
        $pages = [
            'title' => 'Pages',
            'items' => collect([
                [
                    'count' => Page::all()->count(),
                    'slug' => 'pages',
                    'title' => 'Pages',
                ],
            ]),
        ];

        $collections = [
            'title' => 'Collections',
            'items' => Collection::all()->map(function ($collection) {
                return [
                    'count' => $collection->count(),
                    'slug' => $collection->path(),
                    'title' => $collection->title(),
                ];
            }),
        ];

        $taxonomies = [
            'title' => 'Taxonomies',
            'items' => Taxonomy::all()->map(function ($taxonomy) {
                return [
                    'count' => $taxonomy->count(),
                    'slug' => $taxonomy->path(),
                    'title' => $taxonomy->title(),
                ];
            }),
        ];

        return compact('pages', 'collections', 'taxonomies');
    }

    /**
     * Get locales and their links
     *
     * @return array
     */
    protected function getLocales()
    {
        $locales = [];

        foreach (Config::getLocales() as $locale) {
            $url = app('request')->url();

            if ($locale !== Config::getDefaultLocale()) {
                $url .= '?locale=' . $locale;
            }

            $locales[] = [
                'name' => $locale,
                'label' => Config::getLocaleName($locale),
                'url' => $url,
                'is_active' => $locale === app('request')->query('locale', Config::getDefaultLocale()),
                'has_content' => true,
                'is_published' => true,
            ];
        }

        return $locales;
    }

    /**
     * Return the default SEO values for the data described
     * in the current context
     *
     * @param array $context
     * @param string $locale
     *
     * @return array
     */
    public static function getDefaults($ctx, $locale)
    {
        if (!$ctx->get('page_object')) {
            return [];
        }

        $class = (new \ReflectionClass($ctx->get('page_object')))->getShortName();
        switch ($class) {
            case 'Entry':
                $object = Collection::whereHandle($ctx->get('collection', ''));
                break;
            case 'Term':
                $object = Taxonomy::whereHandle($ctx->get('taxonomy', ''));
                break;
            case 'Page':
            case 'ExceptionRoute':
                $object = PageFolder::whereHandle('/') ?: PageFolder::create();
                $object->path('/');
                break;
        }

        if (empty($object)) {
            return [];
        }

        return $object->get('aardvark_' . $locale, []);
    }
}
