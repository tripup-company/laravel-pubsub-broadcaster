<?php
return [
    "key_file_path" => env("GOOGLE_APPLICATION_CREDENTIALS", base_path("key.json")),
    'default_topic' => env("PUBSUB_DEFAULT_TOPIC", 'entity-changed'),
    'event_resolver' => \TripUp\PubSub\Resolvers\DefaultPubSubEventsResolver::class,
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
