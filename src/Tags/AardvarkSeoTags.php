<?php

namespace WithCandour\AardvarkSeo\Tags;

use Statamic\Facades\Site;
use Statamic\Tags\Tags;
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
     * Return the robots tag content
     * (done here to prevent a bunch of ifs and butts in the template file)
     *
     * @return string
     */
    public function robotsTag()
    {
        $ctx = collect($this->context);
        $attrs = [];

        if($ctx->get('no_index_page') || $ctx->get('aardvark_general_settings')['no_index_site']) {
            array_push($attrs, 'noindex', 'noodp');
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
