<?php

namespace App\Jobs;

use App\Events\UserStatusChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class CheckInactiveUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $cacheKey = 'user_activity_' . $user->id;

            if (!Cache::has($cacheKey)) {
                broadcast(new UserStatusChangedEvent($user, 'Offline'));
            }
        }
    }
}
