<?php

namespace WithCandour\AardvarkSeo\Schema\Parts;

use Spatie\SchemaOrg\Schema;
use Statamic\Facades\Config;
use Statamic\Facades\URL;
use WithCandour\AardvarkSeo\Schema\SchemaIds;
use WithCandour\AardvarkSeo\Schema\Parts\SiteOwner;
use WithCandour\AardvarkSeo\Schema\Parts\Contracts\SchemaPart;

class WebSite implements SchemaPart
{
    public function __construct($context = [])
    {
        $this->context = $context;
    }

    public function data()
    {
        $site = Schema::webSite();
        $site->url(URL::makeAbsolute(Config::getSiteUrl()));
        $site->setProperty('publisher', ['@id' => SiteOwner::id()]);
        $site->setProperty('@id', self::id());
        return $site;
    }

    public static function id()
    {
        return URL::makeAbsolute(Config::getSiteUrl()) . SchemaIds::WEB_SITE;
    }
}
