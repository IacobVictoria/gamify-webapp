<?php

namespace App\Jobs;

use App\Events\DiscountApplied;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BroadcastDiscountAppliedJob implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $description;

    /**
     * Create a new job instance.
     */
    public function __construct($description)
    {
        $this->description = $description;
    }

    /**
     * The name of the broadcasting channel.
     */
    public function broadcastOn()
    {
        return ['discounts'];
    }

    /**
     * The event's broadcasting name.
     */
    public function broadcastAs()
    {
        return 'DiscountApplied';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();

        foreach ($users as $user) {
            broadcast(new DiscountApplied($this->description, app(NotificationService::class), $user));
        }
    }
}

