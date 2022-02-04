<?php

namespace TripUp\PubSub\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PubSubEvent implements ShouldBroadcastNow
{
    /**
     * @var string
     */
    public $payload;
    /**
     * @var string
     */
    public $action;
    /**
     * @var string
     */
    public $entity;
    /**
     * @var string
     */
    public $entityId;

    /**
     * @param array $payload
     * @param string $action
     * @param string $entity
     * @param string $entityId
     */
    public function __construct(string $action, string $entity, string $entityId, array $payload = [])
    {
        $this->payload = json_encode($payload);
        $this->action = $action;
        $this->entity = $entity;
        $this->entityId = $entityId;
    }

    /**
     * Returns pubsub tipic name
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]|string|string[]|void
     */
    public function broadcastOn()
    {
        return new Channel(config("pubsub.default_topic"));
    }
    /**
     * Pubsub message body
     *
     * @return string
     */
    public function broadcastAs()
    {
        return config("pubsub.app_name");
    }

}
