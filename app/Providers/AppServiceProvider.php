<?php

namespace App\Providers;

use App\Http\Controllers\GameController;
use App\Http\Controllers\QrScannerController;
use App\Interfaces\UserAchievementInterface;
use App\Models\ClientOrder;
use App\Models\Product;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Services\UserAchievementService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    
    {
        $this->app->when(GameController::class)
            ->needs(UserAchievementInterface::class)
            ->give(UserAchievementService::class);

        $this->app->when(QrScannerController::class)
            ->needs(UserAchievementInterface::class)
            ->give(UserAchievementService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        User::observe(UserObserver::class);
        ClientOrder::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
    }
}
