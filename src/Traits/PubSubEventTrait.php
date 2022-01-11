<?php

namespace TripUp\PubSub\Traits;

trait PubSubEventTrait
{
    /**
     * @var string
     */
    public $project;
    /**
     * @var array
     */
    public $payload = [];
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
     * @return string
     */
    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }
}
