<?php

use App\Console\Commands\CalculateWeeklyLeaderboard;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyMinute();

Schedule::command(CalculateWeeklyLeaderboard::class)->weeklyOn(1, '00:00');
