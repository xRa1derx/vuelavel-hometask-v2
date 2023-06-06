<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConnectingStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('connecting_status_' . $this->id)
        ];
    }

    public function broadcastAs(): string
    {
        return 'connecting_status';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id
        ];
    }
}
