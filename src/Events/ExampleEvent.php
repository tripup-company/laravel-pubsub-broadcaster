<?php

namespace TripUp\PubSub\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ExampleEvent implements ShouldBroadcastNow
{
    // Pubsub message attributes
    public $type="some event type";
    public $entity = "some entyity name";
    public $entityId = "some entity id";
    public $payload;

    /**
     * @param $data
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Returns pubsub tipic name
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]|string|string[]|void
     */
    public function broadcastOn()
    {
        return new Channel("product-changed");
    }
    /**
     * Pubsub message body
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'Some application name';
    }
}
