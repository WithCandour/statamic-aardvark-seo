<?php

namespace WithCandour\AardvarkSeo\Http\Middleware;

use Statamic\Facades\Site;
use Statamic\Support\Str;
use WithCandour\AardvarkSeo\Redirects\Repositories\RedirectsRepository;

class RedirectsMiddleware
{
    public function handle($request, $next)
    {
        // If there is a 404 search our redirects and stuff
        $response = $next($request);

        if($response->getStatusCode() === 404) {
            $path = Str::ensureLeft($request->path(), '/');

            // First check the manual redirects
            $manualRepository = $this->getManualRedirectsRepository();
            if($manualRepository->sourceExists($path)) {

                $redirect = $manualRepository->getBySource($path);
                $target = $redirect['target_url'];
                $status = $redirect['status_code'];
                $is_active = $redirect['is_active'];

                if($is_active) {
                    return redirect($target, $status);
                }
            }

            // Then check the auto redirects
        }

        return $response;
    }

    private function getManualRedirectsRepository()
    {
        return new RedirectsRepository('redirects/manual', Site::current());
    }
}
