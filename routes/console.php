<?php

use App\Console\Commands\CalculateWeeklyLeaderboard;
use App\Console\Commands\CheckInactiveUsers;
use App\Console\Commands\ManageDiscountsCommand;
use App\Console\Commands\ManageEventsCommand;
use App\Console\Commands\ProcessLowStockOrders;
use App\Console\Commands\ProcessSupplierOrders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->everyMinute();

Schedule::command(CalculateWeeklyLeaderboard::class)->weeklyOn(1, "00:00");
//Schedule::command(ProcessSupplierOrders::class)->everyMinute();// daca pun sa se faca azi la 12 si il pun cand e 11, o sa se faca abia maine la 00, nu e bine
Schedule::command(CheckInactiveUsers::class)->everyThirtyMinutes();
Schedule::command(ManageDiscountsCommand::class)->everyMinute();
Schedule::command(ManageEventsCommand::class)->everyMinute();
Schedule::command(ProcessLowStockOrders::class)->everyMinute();