<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP\Interfaces;

interface Publishable
{
    /**
     * Return the Statamic blueprint which this form uses
     *
     * @return Statamic\Fields\Blueprint
     */
    public function getBlueprint();

    /**
     *  Return the data from this page's storage file
     *
     * @return array
     */
    public function getData();
}
