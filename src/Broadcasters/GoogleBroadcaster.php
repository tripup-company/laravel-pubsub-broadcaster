<?php
namespace TripUp\PubSub\Broadcasters;

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\Broadcaster;

class GoogleBroadcaster implements Broadcaster
{

    /**
     * @var PubSubClient
     */
    private $client;

    /**
     * @param PubSubClient $client
     */
    public function __construct(PubSubClient $client)
    {
        $this->client = $client;
    }

    /**
     * Broadcast the given event.
     *
     * @param array $channels
     * @param string $event
     * @param array $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        foreach($channels as $channel) {
            $topic = $this->getTopic($channel);
            $topic->publish([
                'data' => $event,
                'attributes' => $payload,
            ]);
        }
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function auth($request)
    {
        //
    }

    /**
     * Return the valid authentication response.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        //
    }

    /**
     * @param $channel
     * @return \Google\Cloud\PubSub\Topic
     */
    private function getTopic(Channel $channel)
    {
        $topic = $this->client->topic($channel->name);
        return $topic;
    }
}
