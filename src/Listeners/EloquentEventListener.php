<?php

namespace TripUp\PubSub\Listeners;

use TripUp\PubSub\Contracts\EloquentEventResolver;

class EloquentEventListener
{
    /**
     * @var EloquentEventResolver
     */
    protected $eventsResolver;

    /**
     * @param EloquentEventResolver $eventsResolver
     */
    public function __construct(EloquentEventResolver $eventsResolver)
    {
        $this->eventsResolver = $eventsResolver;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event, $data)
    {
        if ($this->eventsResolver->shouldPublish($event)) {
            $this->eventsResolver->pushEvent($event, $data);
        }
    }
}
