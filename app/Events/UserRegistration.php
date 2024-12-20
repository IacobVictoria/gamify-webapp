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

class UserRegistration implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public function __construct( $user)
    {
        $this->user=$user;
    }

  
    public function broadcastOn()
    {
               new Channel('user.'. $this->user->id);
      
    }

    public function broadcastAs()
    {
        return 'UserRegistration';
    }

    public function broadcastWith(){
        return[
            "message" => "[{$this->user->name}]",
        ];
    }
}
