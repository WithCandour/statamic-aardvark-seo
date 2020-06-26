<?php

namespace WithCandour\AardvarkSeo\Storage;

interface Storage
{
    public static function getYaml(string $handle, bool $returnCollection);

    public static function putYaml(string $handle, array $data);
}
