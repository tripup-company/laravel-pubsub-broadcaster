<?php

namespace TripUp\PubSub\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ExampleEvent implements ShouldBroadcastNow
{
    public $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]|string|string[]|void
     */
    public function broadcastOn()
    {
        return new Channel("test-channel");
    }
}
