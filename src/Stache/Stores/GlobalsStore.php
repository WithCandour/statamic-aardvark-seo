<?php

namespace WithCandour\AardvarkSeo\Stache\Stores;

use Illuminate\Support\Facades\App;
use Statamic\Facades\File;
use Statamic\Facades\Site;
use Statamic\Facades\YAML;
use Statamic\Support\Arr;
use Statamic\Support\Str;
use Statamic\Stache\Stores\BasicStore;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet;

class GlobalsStore extends BasicStore
{
    public function key()
    {
        return 'aardvark-seo-globals';
    }

    public function makeItemFromFile($path, $contents)
    {
        $relative = str_after($path, $this->directory);
        $handle = str_before($relative, '.yaml');

        // If it's a variables file that was requested, instead assume that the
        // base file was requested. The variables will get made as part of it.
        if (Site::hasMultiple() && Str::contains($relative, '/')) {
            $handle = pathinfo($relative, PATHINFO_FILENAME);
            $path = $this->directory.$handle.'.yaml';
            $data = YAML::file($path)->parse();

            return $this->makeMultiSiteGlobalFromFile($handle, $path, $data);
        }

        $data = YAML::file($path)->parse($contents);

        return Site::hasMultiple()
            ? $this->makeMultiSiteGlobalFromFile($handle, $path, $data)
            : $this->makeSingleSiteGlobalFromFile($handle, $path, $data);
    }

    protected function makeSingleSiteGlobalFromFile($handle, $path, $data)
    {
        $set = $this->makeBaseGlobalFromFile($handle, $path, $data);

        return $set->addLocalization(
            $set
                ->makeLocalization(Site::default()->handle())
                ->initialPath($path)
                ->data($data['data'] ?? [])
        );
    }

    protected function makeMultiSiteGlobalFromFile($handle, $path, $data)
    {
        $set = $this->makeBaseGlobalFromFile($handle, $path, $data);

        Site::all()->filter(function ($site) use ($handle) {
            return File::exists($this->directory.$site->handle().'/'.$handle.'.yaml');
        })->map->handle()->map(function ($site) use ($set) {
            return $this->makeVariables($set, $site);
        })->filter()->each(function ($variables) use ($set) {
            $set->addLocalization($variables);
        });

        return $set;
    }


    protected function makeBaseGlobalFromFile($handle)
    {
        return App::make(GlobalSet::class)
            ->handle($handle);
    }

    protected function makeVariables($set, $site)
    {
        $variables = $set->makeLocalization($site);

        // todo: cache the reading and parsing of the file
        if (! File::exists($path = $variables->path())) {
            return;
        }
        $data = YAML::file($path)->parse();

        $variables
            ->initialPath($path)
            ->data(Arr::except($data, 'origin'));

        if ($origin = Arr::get($data, 'origin')) {
            $variables->origin($origin);
        }

        return $variables;
    }
}
