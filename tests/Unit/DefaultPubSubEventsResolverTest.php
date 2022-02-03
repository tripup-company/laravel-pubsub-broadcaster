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

    public function testShouldReset()
    {
        $this->assertTrue($this->service->shouldPublish("eloquent.saved: App\Models\Product "));
        $this->assertFalse($this->service->shouldPublish("eloquent.created: App\Models\Product"));
        $this->assertFalse($this->service->shouldPublish("eloquent.saved: App\Models\Availability"));
        $this->assertFalse($this->service->shouldPublish("some wrong data"));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('pubsub.event_match.saved', ['App\Models\Product']);
        $this->service = $this->app->make(PubSubEventsResolver::class);
    }
}
