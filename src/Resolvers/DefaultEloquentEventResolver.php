<?php

namespace TripUp\PubSub\Resolvers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use TripUp\PubSub\Contracts\EloquentEventResolver;
use TripUp\PubSub\Events\PubSubEvent;

/**
 * Detect if an eloquent event should be processed and make PubSubEvent
 */
class DefaultEloquentEventResolver implements EloquentEventResolver
{
    protected $re = '/^eloquent\.(\w+):\s+(.*)$/m';
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @return array
     */
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
                $payloadFields = config("pubsub.event_match.$action.$entity");
                $payload = $this->buildPayload($payload, $entityInstance, $payloadFields);
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
     * @param $payload
     * @param Model $entityInstance
     * @param $payloadFields
     * @return mixed
     */
    public function buildPayload($payload, Model $entityInstance, $payloadFields)
    {
        if (!is_array($payloadFields)) {
            return $payload;
        }
        foreach ($payloadFields as $field) {
            $attributes = $entityInstance->getAttributes();
            if (!Arr::has($attributes, $field)) {
                continue;
            }
            $payload[$field] = $entityInstance->getAttributeValue($field);
        }

        return $payload;
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
            $allowedModels = $this->getAllowedModels($action);
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

    /**
     * @param string $action
     * @return array
     */
    protected function getAllowedModels(string $action): array
    {
        $allowedModels= [];
        $models = config("pubsub.event_match.$action");
        foreach ($models as $key => $item) {
            $allowedModels[] = is_array($item) ? $key : $item;
        }
        return $allowedModels;
    }
}
