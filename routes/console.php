<?php

use App\Console\Commands\CalculateWeeklyLeaderboard;
use App\Console\Commands\CheckInactiveUsers;
use App\Console\Commands\ManageDiscountsCommand;
use App\Console\Commands\ManageEventsCommand;
use App\Console\Commands\ProcessLowStockOrders;
use App\Console\Commands\ProcessSupplierOrders;
use App\Console\Commands\SendNpsReportCommand;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->everyMinute();

Schedule::command(CalculateWeeklyLeaderboard::class)->weeklyOn(1, "00:00");
Schedule::command(ProcessSupplierOrders::class)->everyMinute();
Schedule::command(CheckInactiveUsers::class)->everyThirtyMinutes();
Schedule::command(ManageDiscountsCommand::class)->everyMinute();
Schedule::command(ManageEventsCommand::class)->everyMinute();
Schedule::command(ProcessLowStockOrders::class)->everyMinute();
Schedule::command(SendNpsReportCommand::class)->monthly();