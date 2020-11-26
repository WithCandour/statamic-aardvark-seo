<?php

namespace WithCandour\AardvarkSeo\Tags;

use Statamic\Facades\Entry;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;
use WithCandour\AardvarkSeo\Schema\SchemaGraph;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;
use WithCandour\AardvarkSeo\Facades\PageDataParser;

class AardvarkSeoTags extends Tags
{
    protected static $handle = 'aardvark-seo';

    /**
     * Return the <head /> tag content required for on-page SEO
     *
     * @return string
     */
    public function head()
    {
        $data = PageDataParser::getData(collect($this->context));

        $view = view('aardvark-seo::tags.head', $data);

        if($this->params->get('debug')) {
            return $view;
        }

        return preg_replace(
            [
                "/<!--(.|\s)*?-->/",
                "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/",
            ],
            [
                "",
                "\n",
            ],
            $view
        );
    }

    /**
     * Return the body content
     */
    public function body()
    {
        $data = PageDataParser::getData(collect($this->context));
        return view('aardvark-seo::tags.body', $data);
    }

    /**
     * Return the footer content
     */
    public function footer()
    {
        $data = PageDataParser::getData(collect($this->context));
        return view('aardvark-seo::tags.footer', $data);
    }

    /**
     * Return the data for hreflang tags
     */
    public function hreflang()
    {
        $ctx = collect($this->context);

        if(!$ctx->get('id')) {
            return null;
        }

        $data = Entry::find($ctx->get('id'));

        if(!$data) {
            return null;
        }

        $alternates = $data->sites()->filter(function($locale) use ($data) {
            return !empty($data->in($locale));
        })->reject(function($locale) use ($data) {
            return $locale === $data->locale();
        });

        if($alternates->count() > 0) {
            return $alternates->map(function($locale) use ($data) {
                $site = Site::get($locale);
                $localized_data = $data->in($locale);
                return "<link rel='alternate' href=\"{$localized_data->absoluteUrl()}\" hreflang=\"{$site->locale()}\" >";
            })->join("\n");
        }
    }

    /**
     * Return the schema graph object
     *
     * @return string
     */
    public function graph()
    {
        $ctx = collect($this->context);
        $graph = new SchemaGraph($ctx);
        return $graph->build();
    }

    /**
     * Return the list of social icons created in the 'Social' menu
     *
     * @return string
     */
    public function socials()
    {
        $data = PageDataParser::getData(collect($this->context));
        $socials = $data->get('aardvark_social_settings')->get('social_links');
        if($socials) {
            return $this->parseLoop($socials->raw());
        }
        return false;
    }

    /**
     * Return the robots tag content
     * (done here to prevent a bunch of ifs and butts in the template file)
     *
     * @return string
     */
    public function robotsTag()
    {
        $ctx = collect($this->context);
        $attrs = [];

        $global_no_index = $ctx->get('aardvark_general_settings')['no_index_site'];

        if($ctx->get('no_index_page') || $global_no_index->raw()) {
            array_push($attrs, 'noindex');
        }

        if($ctx->get('no_follow_links')) {
            array_push($attrs, 'nofollow');
        }

        if(!empty($attrs)) {
            $attrs_string = implode(', ', $attrs);
            return "<meta name=\"robots\" content=\"{$attrs_string}\">";
        }

        return false;
    }

    /**
     * Return a generated canonical URL - this should contain pagination vars
     * if any are set
     *
     * @return string
     */
    public function generatedCanonical()
    {
        $data = collect($this->context);
        $vars = $data->get('get');
        $current_url = $data->get('permalink');
        if ($vars && $page = collect($vars)->get('page')) {
            $current_url .= '?page=' . urlencode($page);
        }
        return $current_url;
    }
}
