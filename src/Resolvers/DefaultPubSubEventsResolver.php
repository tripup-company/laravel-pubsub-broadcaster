<?php

namespace TripUp\PubSub\Resolvers;

use TripUp\PubSub\Contracts\PubSubEventsResolver;
use TripUp\PubSub\Events\PubSubEvent;

class DefaultPubSubEventsResolver implements PubSubEventsResolver
{
    protected $re = '/^eloquent\.(\w+):\s+(.*)$/m';
    /**
     * @var array
     */
    protected $events = [];

    public function getPubSubEvents(): array
    {
        return collect($this->events)->map(function ($item) {
            preg_match_all($this->re, $item["event"], $matches, PREG_SET_ORDER, 0);
            $action = trim($matches[0][1]);
            $model = trim($matches[0][2]);
            return new PubSubEvent($action, $model, "unknown");
        })->toArray();
    }

    /**
     * @param $event
     * @return bool
     */
    public function shouldPublish($event, $data = []): bool
    {
        $res = false;
        $alloweActions = config("pubsub.actions");
        $allowedModels = config("pubsub.models");
        if (preg_match_all($this->re, $event, $matches, PREG_SET_ORDER, 0)) {
            $action = trim($matches[0][1]);
            $model = trim($matches[0][2]);
            $res = in_array($action, $alloweActions) && in_array($model, $allowedModels);
        }

        return $res;
    }

    /**
     * @param $event
     * @param $data
     * @return mixed|void
     */
    public function pushEvent($event, $data = [])
    {
        $events[] = ['event' => $event, 'payload' => $data];
    }
}
