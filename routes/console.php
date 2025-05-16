<?php

use App\Console\Commands\CalculateWeeklyLeaderboard;
use App\Console\Commands\CheckInactiveUsers;
use App\Console\Commands\GenerateMeetingReportsCommand;
use App\Console\Commands\ManageDiscountsCommand;
use App\Console\Commands\ProcessLowStockOrders;
use App\Console\Commands\ProcessSupplierOrders;
use App\Console\Commands\RunRecommenderPipeline;


Schedule::command(CalculateWeeklyLeaderboard::class)->weeklyOn(1, "00:00");
Schedule::command(ProcessSupplierOrders::class)->everyMinute();
Schedule::command(ManageDiscountsCommand::class)->everyMinute();
Schedule::command(ProcessLowStockOrders::class)->everyMinute();
Schedule::command(GenerateMeetingReportsCommand::class)->everyMinute();
Schedule::command('backup:run')->monthlyOn(1, '00:00');
Schedule::command('backup:clean')->monthlyOn(1, '00:10');
Schedule::command(RunRecommenderPipeline::class)->weekly();