<?php

namespace WithCandour\AardvarkSeo\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;
use WithCandour\AardvarkSeo\Blueprints\CP\OnPageSeoBlueprint;

class BlueprintsUpdate extends Command
{
    use RunsInPlease;

    protected $signature = 'aardvark:blueprints:update';

    protected $description = 'Process all blueprints and append the Aardvark SEO on-page SEO fields';

    public function handle()
    {
        $settings = AardvarkStorage::getYaml('blueprints', Site::current(), true);
        $excluded = $settings->get('exclude_blueprints', ['asset', 'user']);

        $all = Blueprint::all();

        foreach($all as $blueprint) {
            $handle = $blueprint->handle();

            if(!in_array($handle, $excluded)) {
                $contents = $blueprint->contents();

                $on_page_bp = OnPageSeoBlueprint::requestBlueprint();
                $on_page_fields = $on_page_bp->contents()['sections']['main'];

                $contents['sections']['SEO'] = $on_page_fields;

                $blueprint->setContents($contents);

                $blueprint->save();

                $this->line("<info>Blueprint '{$handle}' updated</info>");
            }
        }
    }
}
