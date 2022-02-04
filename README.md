# Laravel Google Pub/Sub Broadcaster

Easily broadcast events to Google Pub/Sub with Laravel.

## Installation

```bash
$ composer require tripup-company/laravel-pubsub-broadcaster
```

Laravel will detect the service provider automatically.

After package installation you should add new driver to broadcast config file:
```php
...
'connections' => [
   ...     
   'google-pubsub' => [
     'driver' => 'google-pubsub',
   ],
   ...
],
...
```
You can publish the config file with:
```bash
php artisan vendor:publish --provider="TripUp\PubSub\PubSubBroadcasterServiceProvider"
```

This is the contents of the published config file:

```php
return [
    "key_file_path" => env("GOOGLE_APPLICATION_CREDENTIALS", base_path("key.json")),
    'default_topic' => env("PUBSUB_DEFAULT_TOPIC", 'product-changed'),
    'event_resolver' => \TripUp\PubSub\Resolvers\DefaultEloquentEventResolver::class,
    'app_name' => env("APP_NAME", 'Default app name'),

    'event_match' => [
        //Available actions https://laravel.com/docs/8.x/eloquent#events
        "created" => [
            // Models class names
        ],
        "saved" => [],
        "deleted" => []
        // ...
    ],
];

```
## Usage

Update your `.env` file:

```
BROADCAST_DRIVER=google-pubsub
GOOGLE_APPLICATION_CREDENTIALS=<path to your google credential file>
```

Create an event and enable broadcasting:

```php
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
     * @param $payload
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
```

Next, in any place of your application:
```php
 \Event::dispatch(new ExampleEvent("Some payload"));
```

## Event listener registration

In your EventService provider you should add new listeners:
```php
 protected $listen = [
        ....
        'eloquent.created:*' => [
            EloquentEventListener::class,
        ],
        'eloquent.updated:*' => [
            EloquentEventListener::class,
        ],
        'eloquent.saved:*' => [
            EloquentEventListener::class,
        ],
        'eloquent.deleted:*' => [
            EloquentEventListener::class,
        ],
        ...
    ];
```
## Middleware 

To auto sending of PubSubEvents you should add PubSubMiddleware to application kernel.

## PubSub event resolver

To separate useless eloquent events and prepare PubSubEvent 
you should implement PubSubEventsResolver contract or use DefaultEloquentEventResolver.




