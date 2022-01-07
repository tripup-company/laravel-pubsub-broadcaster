<?php
return [
    "debug" => env("PUBSUB_BROADCASTER_DEBUG", false),
    "project_id" => env("GOOGLE_CLOUD_PROJECT",'tripup-test-195809'),
    "key_file_path" => env("GOOGLE_APPLICATION_CREDENTIALS",  "C:\OS\domains\laravel-pubsub-broadcaster\key.json")
];
