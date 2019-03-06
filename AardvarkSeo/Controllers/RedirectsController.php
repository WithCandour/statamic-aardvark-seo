<?php

namespace Statamic\Addons\AardvarkSeo\Controllers;

use Illuminate\Http\Request;
use Statamic\Addons\AardvarkSeo\Traits\Redirects\GeneratesDataUris;
use Statamic\API\File;
use Statamic\API\Page;
use Statamic\API\URL;
use Statamic\API\YAML;
use Statamic\CP\Publish\ProcessesFields;
use Statamic\Events\Data\EntrySaved;
use Statamic\Events\Data\TermSaved;

class RedirectsController extends Controller
{
    use ProcessesFields;
    use GeneratesDataUris;

    const STORAGE_KEY = 'seo-redirects';

    const ROUTES_FILE = 'routes.yaml';

    /**
     * Return the control panel redirects grid.
     */
    public function index()
    {
        $fieldset = $this->createAddonFieldset('redirects');

        $data = $this->storage->getYAML(self::STORAGE_KEY);
        $data['redirects'] = $this->extractGridDataFromFile();

        $processedData = $this->preProcessWithBlankFields(
            $fieldset,
            $data
        );

        return $this->view('cp', [
            'id' => null,
            'data' => $processedData,
            'title' => 'Redirection',
            'fieldset' => $fieldset->toPublishArray(),
            'submitUrl' => route('aardvark-seo.update-redirects'),
        ]);
    }

    /**
     * Update the redirects data.
     *
     * @param Illuminate\Http\Request $request
     */
    public function cpUpdate(Request $request)
    {
        $this->updateSiteRoutesFromCP($request);
        return $this->updateStorage($request, self::STORAGE_KEY, 'aardvark-seo.redirects'); // Is this necessary if we're reading only from the routes.yaml file?
    }

    /**
     * Write the routes generated in the CP to the routes.yaml file.
     *
     * @param Illuminate\Http\Request $request
     */
    public function updateSiteRoutesFromCP($request)
    {
        $grid = $request->fields['redirects'];
        $routes = collect($this->collectRoutesFromData($grid));
        $existingRedirects = collect($this->readFromRoutesFile());
        return $this->writeToRoutesFile($existingRedirects->merge($routes)->all());
    }

    /**
     * Convert the data returned from the CP grid into yaml
     * that can be written to `routes.yaml`.
     *
     * @param array $data Output from the grid
     *
     * @return array
     */
    public function collectRoutesFromData($data)
    {
        $routes = ['redirect' => [], 'vanity' => []];
        foreach ($data as $key => $redirect) {
            $type = $redirect['status_code'] === '301' ? 'redirect' : 'vanity';
            $routes[$type][$redirect['source']] = $redirect['target'];
        }
        return $routes;
    }

    /**
     * Convert the site `routes.yaml` file to an array that can be displayed in the CP.
     *
     * @return array
     */
    private function extractGridDataFromFile()
    {
        $redirects = $this->readFromRoutesFile();
        $data = [];

        $redirect = \array_key_exists('redirect', $redirects) ? $redirects['redirect'] : [];
        $vanity = \array_key_exists('vanity', $redirects) ? $redirects['vanity'] : [];

        foreach ($redirect as $from => $to) {
            $data[] = ['source' => $from, 'target' => $to, 'status_code' => '301'];
        }

        foreach ($vanity as $from => $to) {
            $data[] = ['source' => $from, 'target' => $to, 'status_code' => '302'];
        }

        return $data;
    }

    /**
     * Parse the site's `routes.yaml` file to an array.
     *
     * @return array
     */
    private static function readFromRoutesFile()
    {
        $redirectsFile = File::get(settings_path(self::ROUTES_FILE));
        return YAML::parse($redirectsFile);
    }

    /**
     * Write an array of processed data to the site's `routes.yaml` file.
     *
     * @param array $data The data to be written
     */
    private static function writeToRoutesFile($data)
    {
        $yaml = YAML::dump($data);
        return File::put(settings_path(self::ROUTES_FILE), $yaml);
    }

    /**
     * Abstract URL transformation.
     *
     * @param string $path The path to transform
     *
     * @return string The transformed url
     */
    private static function getRouteFromPath($path)
    {
        return URL::buildFromPath($path);
    }

    /**
     * Top level sanitization for adding new
     * routes to the yaml file.
     *
     * @param string $from
     * @param string $to
     * @param array  $data
     *
     * @return array
     */
    private static function sanitizeRoutes($from, $to, $data)
    {
        $data = self::removePotentialInfiniteRedirects($to, $data);
        $data = self::removeChainingRedirects($from, $to, $data);
        return $data;
    }

    /**
     * Will remove any redirects that will redirect infinitely
     * by taking out existing redirects in the $data that redirect
     * from the route you are redirecting $to.
     *
     * @param string $to
     * @param array  $data
     *
     * @return array
     */
    public static function removePotentialInfiniteRedirects($to, $data)
    {
        if (\array_key_exists($to, $data)) {
            unset($data[$to]);
        }
        return $data;
    }

    /**
     * Prevent chaining redirects by updating any previous
     * redirects to point to the new target.
     *
     * @param string $from
     * @param string $to
     * @param array  $data
     *
     * @return array
     */
    public static function removeChainingRedirects($from, $to, $data)
    {
        foreach ($data as $existingFrom => $existingTo) {
            if ($from === $existingTo) {
                $data[$existingFrom] = $to;
            }
        }
        return $data;
    }

    /**
     * API endpoint for creating a new redirect.
     *
     * @param string $from        The source route
     * @param string $to          The target url
     * @param bool   $isPermenant Should the redirect be 301?... or 302?
     */
    public static function create_redirect($from, $to, $isPermenant = true)
    {
        $category = $isPermenant ? 'redirect' : 'vanity';
        $existingRoutes = self::readFromRoutesFile();
        $existingRoutes[$category][$from] = $to;
        $existingRoutes[$category] = self::sanitizeRoutes($from, $to, $existingRoutes[$category]);
        return self::writeToRoutesFile($existingRoutes);
    }

    /**
     * Create a redirect when a page object is 'moved' in the sitetree.
     *
     * @param Statamic\Events\Data\PageMoved
     */
    public static function createRedirectFromPageMoved($event)
    {
        if ($event->newPath === $event->oldPath) {
            return;
        }
        $oldPath = self::getRouteFromPath($event->oldPath);
        $newPath = self::getRouteFromPath($event->newPath);
        return self::create_redirect($oldPath, $newPath);
    }

    /**
     * Create a redirect when a page is saved (check for the slug change).
     *
     * @param Statamic\Events\Data\PageSaved
     */
    public static function createRedirectFromPageSaved($event)
    {
        if ($event->data->path() === $event->original['attributes']['path']) {
            return;
        }
        $oldPath = self::getRouteFromPath($event->original['attributes']['path']);
        $newPath = self::getRouteFromPath($event->data->path());
        self::create_redirect($oldPath, $newPath);
        self::redirectChildPages($event->data->id(), $oldPath, $newPath);
    }

    /**
     * Redirect children and all further child pages.
     *
     * @param string $id
     * @param string $oldPath
     * @param string $newPath
     */
    public static function redirectChildPages($id, $oldPath, $newPath)
    {
        $page = Page::find($id);
        $childPages = $page->children(1);
        if (!empty($childPages) && $childPages->count() > 0) {
            $childPages->each(function ($childPage) use ($oldPath, $newPath) {
                $oldChildPath = \sprintf('%s/%s', $oldPath, $childPage->slug());
                $newChildPath = \sprintf('%s/%s', $newPath, $childPage->slug());
                self::create_redirect($oldChildPath, $newChildPath);
                self::redirectChildPages($childPage->id(), $oldChildPath, $newChildPath);
            });
        }
    }

    /**
     * Extract the new/old routes from a data saved event.
     *
     * @param Statamic\Events\Data\ContentSaved $event
     *
     * @return array
     */
    private static function extractChangedRoutesFromDataEvent($event)
    {
        $attrs = $event->original['attributes'];

        switch (true) {
        case $event instanceof EntrySaved:
            $oldRoute = self::entry_uri($attrs['slug'], $attrs['collection']);
            break;
        case $event instanceof TermSaved:
            $oldRoute = self::term_uri($attrs['slug'], $attrs['taxonomy']);
            break;
        default:
            $oldRoute = null;
        }

        return [
            'new' => $newRoute = $event->data->url(),
            'old' => $oldRoute,
        ];
    }

    /**
     * Create a redirect when a collection entry is saved.
     *
     * @param Statamic\Events\Data\EntrySaved
     */
    public static function createRedirectFromDataSaved($event)
    {
        $attrs = $event->original['attributes'];
        if ($event->data->slug() === $attrs['slug']) {
            return;
        }
        $routes = self::extractChangedRoutesFromDataEvent($event);
        return self::create_redirect($routes['old'], $routes['new']);
    }
}
