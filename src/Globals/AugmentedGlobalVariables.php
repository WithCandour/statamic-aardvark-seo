<?php

namespace WithCandour\AardvarkSeo\Globals;

use Statamic\Data\AbstractAugmented;

class AugmentedGlobalVariables extends AbstractAugmented
{
    public function keys()
    {
        return $this->data->values()->keys()->all();
    }

    public function site()
    {
        if ($site = $this->data->value('site')) {
            return $this->wrapValue($site, 'site');
        }
    }
}
