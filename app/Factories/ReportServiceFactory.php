<?php
namespace App\Factories;

use App\Services\Reports\ClientInvoiceReportService;
use App\Services\Reports\GamesActivityReportService;
use App\Services\Reports\NpsActivityReportService;
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
            'products_activity' => ProductsActivityReportService::class,
            'user_activity' => UserActivityReportService::class,
            'sales_stock' => SalesStockReportService::class,
            'games_activity' => GamesActivityReportService::class,
            'rewards_activity' => RewardsActivityReportService::class,
            'client_invoice' => ClientInvoiceReportService::class,
            'nps_report' => NpsActivityReportService::class,
            'supplier_invoice' => SupplierInvoiceReportService::class,
            default => null,
        };

        return $serviceClass ? app($serviceClass) : null; // SINGLETON
    }
}
