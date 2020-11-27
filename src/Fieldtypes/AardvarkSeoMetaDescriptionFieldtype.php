<?php

namespace WithCandour\AardvarkSeo\Fieldtypes;

use Statamic\Facades\Site;
use Statamic\Fields\Fieldtype;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

class AardvarkSeoMetaDescriptionFieldtype extends Fieldtype
{
    protected $selectable = false;
}
