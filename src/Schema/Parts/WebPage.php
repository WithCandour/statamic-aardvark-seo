<?php

namespace WithCandour\AardvarkSeo\Schema\Parts;

use Spatie\SchemaOrg\Schema;
use Statamic\Facades\Config;
use Statamic\Facades\URL;
use WithCandour\AardvarkSeo\Schema\SchemaIds;
use WithCandour\AardvarkSeo\Schema\Parts\WebSite;
use WithCandour\AardvarkSeo\Schema\Parts\Contracts\SchemaPart;

class WebPage implements SchemaPart
{
    public function __construct($context = [])
    {
        $this->context = $context;
    }

    public function data()
    {
        $page = Schema::webPage();
        $page->setProperty('@id', self::id());
        $page->url(URL::makeAbsolute(URL::getCurrent()));
        $title = $this->context->get('meta_title') ?: $this->context->get('calculated_title', '');
        $page->name($title);
        $page->isPartOf(['@id' => WebSite::id()]);
        $page->inLanguage(Config::getFullLocale());
        if ($this->context->get('last_modified')) {
            $page->datePublished($this->context->get('last_modified')->format('c'));
            $page->dateModified($this->context->get('last_modified')->format('c'));
        }
        return $page;
    }

    public static function id()
    {
        return URL::makeAbsolute(Config::getSiteUrl()) . SchemaIds::WEB_PAGE;
    }
}
