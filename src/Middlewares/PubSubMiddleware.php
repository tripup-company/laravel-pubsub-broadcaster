<?php

namespace TripUp\PubSub\Middlewares;


use Illuminate\Support\Facades\Event;
use TripUp\PubSub\Contracts\EloquentEventResolver;

class PubSubMiddleware
{
    /**
     * @var EloquentEventResolver
     */
    protected $pubSubResolver;

    public function __construct(EloquentEventResolver $pubSubResolver)
    {
        $this->pubSubResolver = $pubSubResolver;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }

    /**
     * Terminate
     *
     * @param $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        foreach ($this->pubSubResolver->getPubSubEvents() as $event) {
            Event::dispatch($event);
        }
    }
}
