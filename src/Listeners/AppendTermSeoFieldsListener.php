<?php

namespace WithCandour\AardvarkSeo\Listeners;

use Statamic\Events\TermBlueprintFound;
use Statamic\Support\Str;
use WithCandour\AardvarkSeo\Blueprints\CP\OnPageSeoBlueprint;
use WithCandour\AardvarkSeo\Listeners\Contracts\SeoFieldsListener;

class AppendTermSeoFieldsListener implements SeoFieldsListener
{
    /**
     * @param \Statamic\Events\TermBlueprintFound $event
     *
     * @return void
     */
    public function handle(TermBlueprintFound $event)
    {
        // We don't want the SEO fields to get added to the blueprint editor
        if (Str::contains(request()->url(), '/blueprints/')) {
            return;
        }

        $handle = $event->blueprint->namespace();
        if ($this->check_content_type($handle)) {
            $bp = $event->blueprint;
            $contents = $bp->contents();

            $on_page_bp = OnPageSeoBlueprint::requestBlueprint();
            $on_page_key = (array_key_exists('tabs', $on_page_bp->contents()) ? 'tabs' : 'sections');
            $on_page_fields = $on_page_bp->contents()[$on_page_key]['main'];

            $contents[$on_page_key]['SEO'] = $on_page_fields;

            $bp->setContents($contents);
        }
    }

    public function check_content_type(string $blueprint_namespace)
    {
        $ns_parts = explode('.', $blueprint_namespace);
        $taxonomy_handle = !empty($ns_parts[1]) ? $ns_parts[1] : null;
        $excluded_taxonomies = config('aardvark-seo.excluded_taxonomies', []);
        if (\in_array($taxonomy_handle, $excluded_taxonomies)) {
            return false;
        }
        return true;
    }
}
