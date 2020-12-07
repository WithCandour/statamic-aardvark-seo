<?php

namespace WithCandour\AardvarkSeo\Modifiers;

use Statamic\Modifiers\Modifier;
use WhiteCube\Lingua\Service as Lingua;

class ParseLocaleModifier extends Modifier
{
    protected static $handle = 'aardvark_parse_locale';

    public static function index($value)
    {
        $parsed = preg_replace('/\.utf8/i', '', $value);

        // Convert to ISO 639-1
        $lang = Lingua::create($parsed);
        $iso_format = $lang->toISO_639_1();

        return $iso_format;
    }
}
