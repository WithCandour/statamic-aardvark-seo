<?php

namespace Statamic\Addons\SeoBox\Traits;

use Statamic\Addons\SeoBox\Controllers\RedirectsController;

trait ManagesRedirects
{
  /**
   * Create a redirect from an old (moved) url to it's new location
   * @param string $from The source
   * @param string $to The target location for the redirect
   * @return null
   */
  public function redirectChangedRoute($from, $to, $isPermenant = true)
  {
    return RedirectsController::create_redirect($from, $to, $isPermenant);
  }
}
