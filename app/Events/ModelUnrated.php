<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelUnrated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $qualifier;
    public $rateable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Model $qualifier, Model $rateable )
    {
        $this->qualifier = $qualifier;
        $this->rateable = $rateable;
    }

    public function getQualifier(): Model
    {
        return $this->qualifier;
    }

    public function getRateable(): Model
    {
        return $this->rateable;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
