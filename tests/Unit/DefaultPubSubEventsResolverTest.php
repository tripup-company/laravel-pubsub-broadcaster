<?php


namespace Tests\unit\Services;

use Tests\TestCase;
use TripUp\PubSub\Contracts\PubSubEventsResolver;

class DefaultPubSubEventsResolverTest extends TestCase
{
    /**
     * @var PubSubEventsResolver
     */
    private $service;

    public function testShouldPublish()
    {
        $this->assertTrue($this->service->shouldPublish("eloquent.saved: App\Models\Product "));
        $this->assertFalse($this->service->shouldPublish("eloquent.created: App\Models\Product"));
        $this->assertFalse($this->service->shouldPublish("eloquent.saved: App\Models\Availability"));
        $this->assertFalse($this->service->shouldPublish("some wrong data"));
    }

    public function testPushEvent()
    {
        $this->service->pushEvent("eloquent.saved: App\Models\Product ", ["v1"]);
        $this->service->pushEvent("eloquent.saved: App\Models\Product ", ["v2"]);
        $this->service->pushEvent("eloquent.saved: App\Models\Availability ", ["v1"]);
        $this->service->pushEvent("eloquent.saved: App\Models\Availability ", ["v2"]);
        $this->service->pushEvent("wrong event", ["v2"]);
        $events = $this->service->getPubSubEvents();
        $this->assertCount(2, $events);
        $this->assertEquals("v2", $events[0]->entityId);
        $this->assertEquals("v2", $events[1]->entityId);
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('pubsub.event_match.saved', ['App\Models\Product']);
        $this->service = $this->app->make(PubSubEventsResolver::class);
    }
}
