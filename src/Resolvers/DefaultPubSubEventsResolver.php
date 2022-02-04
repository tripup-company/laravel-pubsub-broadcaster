<?php

namespace TripUp\PubSub\Resolvers;

use Illuminate\Database\Eloquent\Model;
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
        $res = [];
        foreach ($this->events as $event => $payload) {
            if (!preg_match_all($this->re, $event, $matches, PREG_SET_ORDER, 0)){
                continue;
            };
            $action = trim($matches[0][1]);
            $entity = trim($matches[0][2]);
            $entityInstance = array_shift($payload);
            $entityId = null;
            if ($entityInstance instanceof Model) {
                $entityId = $entityInstance->getKey();
            };
            if (is_string($entityInstance)) {
                $entityId = $entityInstance;
            };
            $res[] = new PubSubEvent(
                $action,
                $entity,
                $entityId,
                $payload
            );
        }
        return $res;
    }


    /**
     * @param $event
     * @return bool
     */
    public function shouldPublish($event, $data = []): bool
    {
        $res = false;
        $config = config("pubsub.event_match");
        $allowedActions = array_keys($config);

        if (preg_match_all($this->re, $event, $matches, PREG_SET_ORDER, 0)) {
            $action = trim($matches[0][1]);
            $model = trim($matches[0][2]);
            $allowedModels = config("pubsub.event_match.$action");
            $res = in_array($action, $allowedActions) && in_array($model, $allowedModels);
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
        $this->events[$event] = $data;
    }
}
