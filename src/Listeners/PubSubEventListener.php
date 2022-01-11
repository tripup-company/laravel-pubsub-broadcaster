<?php

namespace TripUp\PubSub\Listeners;

use TripUp\PubSub\Contracts\PubSubEventsResolver;

class PubSubEventListener
{
    /**
     * @var PubSubEventsResolver
     */
    protected $eventsResolver;

    /**
     * @param PubSubEventsResolver $eventsResolver
     */
    public function __construct(PubSubEventsResolver $eventsResolver)
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
