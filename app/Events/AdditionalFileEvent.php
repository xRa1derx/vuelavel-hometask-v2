<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdditionalFileEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $uuid;
    private int $userId;
    public array $files;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $uuid, int $userId, array $files)
    {
        $this->uuid = $uuid;
        $this->userId = $userId;
        $this->files = $files;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('store_message_' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'add_additional_files';
    }

    public function broadcastWith(): array
    {
        return [
            'uuid' => $this->uuid,
            'files' => $this->files
        ];
    }
}
