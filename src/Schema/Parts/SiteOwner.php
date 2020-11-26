<?php

namespace WithCandour\AardvarkSeo\Schema\Parts;

use Spatie\SchemaOrg\Schema;
use Statamic\Facades\Asset;
use Statamic\Facades\Config;
use Statamic\Facades\URL;
use WithCandour\AardvarkSeo\Schema\SchemaIds;
use WithCandour\AardvarkSeo\Schema\Parts\Contracts\SchemaPart;

class SiteOwner implements SchemaPart
{
    public function __construct($context = [])
    {
       $this->context = $context;
    }

    public function data()
    {
        $type = $this->context->get('company_or_person')->raw();
        if($type === 'company') {
            $owner = Schema::organization();
            $owner->name($this->context->get('target_name')->raw());
            $logo = $this->context->get('company_logo')->value();
            if(!empty($logo)) {
                $logoObject = Schema::imageObject();
                $logoObject->url($logo->absoluteUrl());
                $logoObject->width($logo->width());
                $logoObject->height($logo->height());
                $owner->logo($logoObject);
            }
        } else {
            $owner = Schema::person();
            $owner->name($this->context->get('target_name')->raw());
        }
        $owner->setProperty('@id', self::id());
        $owner->url(URL::makeAbsolute(Config::getSiteUrl()));
        return $owner;
    }

    /**
     * Return the ID of the site owner
     */
    public static function id()
    {
        return URL::makeAbsolute(Config::getSiteUrl()) . SchemaIds::SITE_OWNER;
    }
}
