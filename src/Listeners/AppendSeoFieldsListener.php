<?php

// TODO: Make this part of the process
// REQUIRES: https://github.com/statamic/cms/pull/1990/files

namespace WithCandour\AardvarkSeo\Listeners;

use Statamic\Events\Data\PublishBlueprintFound;
use WithCandour\AardvarkSeo\Blueprints\CP\OnPageSeoBlueprint;

class AppendSeoFieldsListener
{
    /**
     * @param \Statamic\Events\Data\PublishBlueprintFound $event
     *
     * @return void
     */
    public function handle(PublishBlueprintFound $event)
    {
        $bp = $event->blueprint;
        $contents = $bp->contents();

        $on_page_bp = OnPageSeoBlueprint::requestBlueprint();
        $on_page_fields = $on_page_bp->contents()['sections']['main'];

        $contents['sections']['SEO'] = $on_page_fields;

        $bp->setContents($contents);
    }
}
