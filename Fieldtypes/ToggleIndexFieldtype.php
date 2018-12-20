<?php

namespace Statamic\Addons\SeoBox\Fieldtypes;

use Statamic\Extend\Fieldtype;

class ToggleIndexFieldtype extends Fieldtype
{

  public $selectable = false;

  public function process($data)
  {
      return (bool) $data;
  }

  public function canHaveDefault()
  {
      return true;
  }

}
