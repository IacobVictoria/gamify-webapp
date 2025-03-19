<?php

namespace App\Providers;

use App\Factories\MeetingReportHandlerFactory;
use App\Factories\OrderHandlerFactory;
use App\Factories\PaymentHandlerFactory;
use App\Factories\SupplierLowStockOrderHandlerFactory;
use App\Factories\SupplierOrderHandlerFactory;
use App\Http\Controllers\GameController;
use App\Http\Controllers\QrScannerController;
use App\Interfaces\BadgeAssignerInterface;
use App\Interfaces\MeetingReportHandlerInterface;
use App\Interfaces\SupplierLowStockOrderHandlerInterface;
use App\Interfaces\SupplierOrderHandlerInterface;
use App\Interfaces\UserAchievementInterface;
use App\Interfaces\UserScoreInterface;
use App\Models\ClientOrder;
use App\Models\Product;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Services\Badges\BadgeAssignerService;
use App\Services\DiscountService;
use App\Services\MedalService;
use App\Services\NotificationService;
use App\Services\OrderHandlers\OrderHandlerInterface;
use App\Services\PaymentHandlers\PaymentHandlerInterface;
use App\Services\UserAchievementService;
use App\Services\UserScoreService;
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

        $this->app->bind(BadgeAssignerInterface::class, BadgeAssignerService::class);
        //returnează o instanță a BadgeAssignerService, care implementează BadgeAssignerInterface.
        $this->app->bind(UserScoreInterface::class, function ($app) {
            return new UserScoreService(
                $app->make(NotificationService::class),
                $app->make(DiscountService::class),
                $app->make(MedalService::class),
            );
        });
        
        $this->app->bind(OrderHandlerInterface::class, function ($app) {
            return OrderHandlerFactory::create($app);
        });
      
        $this->app->bind(PaymentHandlerInterface::class, function ($app) {
            return PaymentHandlerFactory::create($app);
        });

        $this->app->bind(SupplierOrderHandlerInterface::class, function ($app) {
            return SupplierOrderHandlerFactory::create($app);
        });

        $this->app->bind(SupplierLowStockOrderHandlerInterface::class, function ($app) {
            return SupplierLowStockOrderHandlerFactory::create($app);
        });

        $this->app->bind(MeetingReportHandlerInterface::class, function ($app) {
            return MeetingReportHandlerFactory::create($app);
        });
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
