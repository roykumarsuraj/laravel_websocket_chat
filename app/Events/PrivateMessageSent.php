<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PrivateMessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $fromUserId;
    public $toUserId;
    public $message;

    public function __construct($from, $to, $message)
    {
        $this->fromUserId = $from;
        $this->toUserId = $to;
        $this->message = $message;
    }

    // Important: broadcast to the recipient, not sender
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->toUserId);
    }

    public function broadcastAs()
    {
        return 'message.received';
    }
}

