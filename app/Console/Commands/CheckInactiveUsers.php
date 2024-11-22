<?php

namespace App\Console\Commands;

use App\Events\UserStatusChangedEvent;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-inactive-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $cacheKey = 'user_activity_' . $user->id;

            //  dacă utilizatorul este inactiv
            if (!Cache::has($cacheKey)) {
                broadcast(new UserStatusChangedEvent($user, 'Offline'))->toOthers();
                $this->info("User {$user->name} marked as offline.");
            } else {
                $this->info("User {$user->name} is still active.");
            }
        }
    }
}
