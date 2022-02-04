<?php

namespace TripUp\PubSub\Contracts;

interface EloquentEventResolver
{
    /**
     * @param $event
     * @param $data
     * @return mixed
     */
    public function pushEvent($event, $data = null);

    /**
     * @param $event
     * @return bool
     */
    public function shouldPublish($event, $data = null): bool;

    /**
     * @return array
     */
    public function getPubSubEvents(): array;
}
