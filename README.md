# Laravel Google Pub/Sub Broadcaster

Easily broadcast events to Google Pub/Sub with Laravel.

## Installation

```bash
$ composer require whatdafox/laravel-google-pubsub-broadcaster
```

Laravel will detect the service provider automatically.

## Usage

Update your `.env` file:

```
BROADCAST_DRIVER=google-pubsub
GOOGLE_CLOUD_PROJECT=dev-moment-244207
GOOGLE_APPLICATION_CREDENTIALS=gcloud.json
```

Create an event and enable broadcasting:

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $someData;

    /**
     * Create a new event instance.
     *
     * @param $someData
     */
    public function __construct($someData)
    {
        $this->someData = $someData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('your-channel-name');
    }
}
```

## Subscribe

Package to subscribe to a topic will be coming soon. In the meantime, you can leverage the Google PubSub PHP library, for example:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Broadcasting\Channel;
use Illuminate\Console\Command;
use Google\Cloud\PubSub\PubSubClient;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubsub:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Google PubSub channel';

    /**
     * Execute the console command.
     *
     * @param PubSubClient $client
     * @return mixed
     */
    public function handle(PubSubClient $client)
    {
        $topic = $client->topic('channel-name');
        
        if(!$topic->exists()) {
            $topic->create();
        }
        
        $subscription = $topic->subscription('subscriber-name');

        if(!$subscription->exists()) {
            $subscription->create();
        }

        while(true) {
            $messages = $subscription->pull([
                'returnImmediately' => true,
                'maxMessages' => 5,
            ]);

            if(empty($messages)) {
                continue;
            }

            foreach ($messages as $message) {
                
                // Do something with the message
                
                $subscription->acknowledge($message);
            }
        }
    }
}
```
