<?php

namespace App\Events;

use App\Models\CommunityMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(CommunityMessage $message)
    {
        // Eager load the user and their profile_image for the UI
        $this->message = $message->load('user:id,name,profile_image');
    }

    public function broadcastOn(): array
    {
        // Public channel for global chat, or PrivateChannel for studio teams
        return [
            new Channel('community.chat.' . $this->message->channel_id),
        ];
    }
}
