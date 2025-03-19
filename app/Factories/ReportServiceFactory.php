<?php
namespace App\Factories;

use App\Services\Reports\ClientInvoiceReportService;
use App\Services\Reports\GamesActivityReportService;
use App\Services\Reports\ProductsActivityReportService;
use App\Services\Reports\RewardsActivityReportService;
use App\Services\Reports\SalesStockReportService;
use App\Services\Reports\SupplierInvoiceReportService;
use App\Services\Reports\UserActivityReportService;

class ReportServiceFactory
{
    public static function create(string $category)
    {
        $serviceClass = match ($category) {
            'products_activity_monthly' => ProductsActivityReportService::class,
            'user_activity_monthly' => UserActivityReportService::class,
            'sales_stock_monthly' => SalesStockReportService::class,
            'games_activity_monthly' => GamesActivityReportService::class,
            'rewards_activity_monthly' => RewardsActivityReportService::class,
            'client_invoice' => ClientInvoiceReportService::class,
            'supplier_invoice' => SupplierInvoiceReportService::class,
            default => null,
        };

        return $serviceClass ? app($serviceClass) : null; // SINGLETON
    }
}
