<?php

namespace App\Events;

use App\Traits\GeneralTrait;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SearchAboutDrivers
{
    use Dispatchable, InteractsWithSockets, SerializesModels,GeneralTrait;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $trip_id,$category_id;
    public function __construct($trip_id,$category_id)
    {
        $this->trip_id = $trip_id;
        $this->category_id = $category_id;
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
