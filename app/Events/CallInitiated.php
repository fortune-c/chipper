<?php

namespace App\Events;

use App\Models\Call;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallInitiated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Call $call)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('conversation.' . $this->call->conversation_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->call->id,
            'type' => $this->call->type,
            'status' => $this->call->status,
            'screen_sharing' => $this->call->screen_sharing,
            'initiator' => [
                'id' => $this->call->initiator->id,
                'name' => $this->call->initiator->name,
            ],
        ];
    }
}
