<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use TripUp\PubSub\Events\ExampleEvent;

class TestBroadcaster extends \Tests\TestCase
{

    public function testSendMessage()
    {
        $this->assertTrue(true);
    }

}
