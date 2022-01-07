<?php

namespace TripUp\PubSub;

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Broadcasting\BroadcastManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TripUp\PubSub\Broadcasters\GoogleBroadcaster;

class PubSubBroadcasterServiceProvider extends PackageServiceProvider
{
    public function packageBooted()
    {
        $this->app->make(BroadcastManager::class)->extend('google-pubsub', function ($app, $config) {
            $client = new PubSubClient([
                'keyFilePath' => config("pubsub.key_file_path"),
            ]);
            return new GoogleBroadcaster($client);
        });

    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('pubsub')
            ->hasConfigFile();
    }
}
