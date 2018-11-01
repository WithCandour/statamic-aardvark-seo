<?php

namespace Statamic\Addons\SeoBox\Controllers;

use Illuminate\Http\Request;
use Statamic\API\Fieldset;
use Statamic\API\File;
use Statamic\API\YAML;
use Statamic\CP\Publish\ProcessesFields;

use Statamic\Addons\SeoBox\Controllers\Controller;

class RedirectsController extends Controller
{

  use ProcessesFields;

  const STORAGE_KEY = 'seo-redirects';

  const ROUTES_FILE = 'site/settings/routes.yaml';

  /**
   * Return the control panel redirects grid
   */
  public function index()
  {
    $fieldset = $this->createAddonFieldset('redirects');

    $data = $this->preProcessWithBlankFields(
        $fieldset,
        $this->extractGridDataFromFile()
    );

    return $this->view('cp', [
      'id' => null,
      'data' => $data,
      'title' => 'Redirection',
      'fieldset'=> $fieldset->toPublishArray(),
      'submitUrl' => route('seo-box.update-redirects')
    ]);
  }

  /**
   * Update the redirects data
   * @param Illuminate\Http\Request $request
   */
  public function cpUpdate(Request $request)
  {
    $this->updateSiteRoutesFromCP($request);
    return $this->updateStorage($request, self::STORAGE_KEY, 'seo-box.redirects'); // Is this necessary if we're reading only from the routes.yaml file?
  }

  /**
   * Write the routes generated in the CP to the routes.yaml file
   * @param Illuminate\Http\Request $request
   * @return null
   */
  public function updateSiteRoutesFromCP($request)
  {
    $existingRedirects = $this->readFromRoutesFile();
    $grid = $request->fields['redirects'];
    $routes = $this->collectRoutesFromData($grid);
    return $this->writeToRoutesFile(array_merge($existingRedirects, $routes));
  }

  /**
   * Convert the data returned from the CP grid into yaml
   * that can be written to `routes.yaml`
   * @param array $data Output from the grid
   * @return array
   */
  private function collectRoutesFromData($data)
  {
    $routes = ['redirect' => [], 'vanity' => []];
    foreach($data as $key => $redirect) {
      $type = $redirect['status_code'] === '301' ? 'redirect' : 'vanity';
      $routes[$type][$redirect['source']] = $redirect['target'];
    }
    return $routes;
  }

  /**
   * Convert the site `routes.yaml` file to an array that can be displayed in the CP
   * @return array
   */
  private function extractGridDataFromFile()
  {
    $redirects = $this->readFromRoutesFile();
    $data = [];

    $redirect = \array_key_exists('redirect', $redirects) ? $redirects['redirect'] : [];
    $vanity = \array_key_exists('vanity', $redirects) ?  $redirects['vanity'] : [];

    foreach($redirect as $from => $to) {
      $data[] = ['source' => $from, 'target' => $to, 'status_code' => '301'];
    }

    foreach($vanity as $from => $to) {
      $data[] = ['source' => $from, 'target' => $to, 'status_code' => '302'];
    }

    return ['redirects' => $data];
  }

  /**
   * Parse the site's `routes.yaml` file to an array
   * @return array
   */
  private function readFromRoutesFile()
  {
    $redirectsFile = File::get(self::ROUTES_FILE);
    return YAML::parse($redirectsFile);
  }

  /**
   * Write an array of processed data to the site's `routes.yaml` file
   * @param array $data The data to be written
   * @return null
   */
  private function writeToRoutesFile($data)
  {
    $yaml = YAML::dump($data);
    return File::put(self::ROUTES_FILE, $yaml);
  }

  /**
   * Creates a new redirect
   */
  public static function create_redirect($from, $to, $isPermenant = true)
  {

  }
}
