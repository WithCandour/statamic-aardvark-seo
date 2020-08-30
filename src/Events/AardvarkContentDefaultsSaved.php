<?php

namespace WithCandour\AardvarkSeo\Events;

use Statamic\Events\Event;
use Statamic\Contracts\Git\ProvidesCommitMessage;

class AardvarkContentDefaultsSaved extends Event implements ProvidesCommitMessage
{
    /**
     * @var WithCandour\AardvarkSeo\Content\Defaults
     */
    public $defaults;

    /**
     * @param WithCandour\AardvarkSeo\Content\Defaults $defaults
     */
    public function __construct($defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * @return string
     */
    public function commitMessage()
    {
        return 'Aardvark content defaults saved';
    }
}
