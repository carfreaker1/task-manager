<?php

namespace App\Events;

use App\Models\Message;
use App\Models\NewPrivateMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

     public $message;

    public function __construct(NewPrivateMessage $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn()
    {
        // Unique private channel for two users
        return new PrivateChannel('taskmanager.' . $this->message->to_id);
    }

     public function broadcastAs()
    {
        // Name for JS binding
        return 'MessageSent';
    }
}
