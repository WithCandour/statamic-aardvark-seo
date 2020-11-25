<?php

namespace WithCandour\AardvarkSeo\Listeners\Redirects\Subscribers;

use WithCandour\AardvarkSeo\Listeners\Redirects\AutoRedirectsListener;

class AutoRedirectsSubscriber
{
    /**
     * Subscribe to content saved events to listen for modified URLs
     *
     * @var array
     */
    protected $events =
    [
        \Statamic\Events\EntrySaving::class,
        \Statamic\Events\TermSaving::class,
    ];

    /**
     * Register the invalidation listener for the events
     */
    public function subscribe($events)
    {
        foreach($this->events as $event) {
            $events->listen($event, AutoRedirectsListener::class . '@handle');
        }
    }
}
