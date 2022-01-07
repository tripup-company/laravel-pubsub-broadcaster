<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use TripUp\PubSub\Events\ExampleEvent;

class TestBroadcaster extends \Tests\TestCase
{

    public function testSendMessage()
    {
       // Event::fake();
        print_r(config("pubsub"));
        Config::set("broadcasting.default", "google-pubsub");
        // print_r(config("broadcasting"));
        Event::dispatch(new ExampleEvent("Hello world"));
//        Event::assertDispatched(ExampleEvent::class, function ($e) {
//            print_r($e);
//            return true;
//        });
        $this->assertTrue(true);
    }

}
