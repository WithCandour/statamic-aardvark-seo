<?php

namespace WithCandour\AardvarkSeo\Tags;

use Statamic\Tags\Tags;

class AardvarkSeoTags extends Tags
{
    protected static $handle = 'aardvark-seo';

    public function head()
    {
        return view('aardvark-seo::tags.head');
    }
}
