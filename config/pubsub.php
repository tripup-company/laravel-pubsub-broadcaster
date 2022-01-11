<?php
return [
    "key_file_path" => env("GOOGLE_APPLICATION_CREDENTIALS", base_path("key.json")),
    'default_topic' => env("PUBSUB_DEFAULT_TOPIC", 'product-changed'),
    'event_resolver' => \TripUp\PubSub\Resolvers\DefaultPubSubEventsResolver::class,
    'app_name' => env("APP_NAME", 'Default app name'),

    'actions' => ["created", "saved", "deleted"],
    'models' => [
        //...
        // \App\Models\Product::class,
    ],
];
