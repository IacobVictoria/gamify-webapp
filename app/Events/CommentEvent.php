<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment, $user;
    
    public function __construct($comment,$user)
    {
        $this->comment = $comment;
        $this->user = $user;
    }

  
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('comments.'.$this->user->id),
        ];
    }

    public function broadcastAs(){
        return 'CommentEvent';
    }

    public function broadcastWith(){
        return [
            'message'=> $this->comment->description,
            'user_name'=>$this->user->name
        ];
    }
}
