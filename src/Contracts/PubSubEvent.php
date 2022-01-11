<?php

namespace TripUp\PubSub\Contracts;

interface PubSubEvent
{
    public function getProject(): string;

    public function getAction(): string;

    public function getEntity(): string;

    public function getEntityId(): string;

    public function getPayload(): string;

}
