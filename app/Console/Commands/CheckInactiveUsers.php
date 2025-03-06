<?php

namespace App\Console\Commands;

use App\Events\UserStatusChangedEvent;
use App\Jobs\CheckInactiveUsersJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckInactiveUsers extends Command
{
    protected $signature = 'app:check-inactive-users';
    protected $description = 'Command description';

    public function handle()
    {
        dispatch(new CheckInactiveUsersJob());
        $this->info('Job-ul de verificare a utilizatorilor inactivi a fost trimis.');
    }
}
