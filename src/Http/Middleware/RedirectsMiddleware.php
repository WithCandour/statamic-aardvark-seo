<?php

namespace WithCandour\AardvarkSeo\Http\Middleware;

use Statamic\Facades\Site;
use Statamic\Support\Str;
use Statamic\Facades\Config;
use Statamic\Facades\URL;
use WithCandour\AardvarkSeo\Redirects\Repositories\RedirectsRepository;

class RedirectsMiddleware
{
    public function handle($request, $next)
    {
        // If there is a 404 search our redirects and stuff
        $response = $next($request);

        if ($response->getStatusCode() === 404) {
            // Remove site URL and root from the request to account for
            // Subdirectory multisite installations

            // Absolute site URL
            $absolute_site_url = URL::makeAbsolute(Config::getSiteUrl());
            $processed_url = preg_replace('#^' . $absolute_site_url . '#', '', $request->url());

            // Ensure we have a leading slash
            $source_url = Str::ensureLeft($processed_url, '/');

            // First check the manual redirects
            $manualRepository = $this->getManualRedirectsRepository();
            if ($manualRepository->sourceExists($source_url)) {
                $redirect = $manualRepository->getBySource($source_url);

                $target = $redirect['target_url'];

                // If the target is relative - prepend the site root
                if (Str::startsWith($target, '/')) {
                    $target = URL::prependSiteRoot($target);
                }

                $status = $redirect['status_code'];
                $is_active = $redirect['is_active'];

                if ($is_active) {
                    return redirect($target, $status);
                }
            }
        }

        return $response;
    }

    private function getManualRedirectsRepository()
    {
        return new RedirectsRepository('redirects/manual', Site::current());
    }
}
