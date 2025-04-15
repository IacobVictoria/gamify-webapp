<?php
namespace App\Factories;

use App\Interfaces\StorageStrategyInterface;
use App\Services\PdfGenerators\ClientInvoicePdfGenerator;
use App\Services\PdfGenerators\GamesActivityReportPdfGenerator;
use App\Services\PdfGenerators\NpsReportPdfGenerator;
use App\Services\PdfGenerators\ProductsActivityReportPdfGenerator;
use App\Services\PdfGenerators\RewardsActivityReportPdfGenerator;
use App\Services\PdfGenerators\SalesStockReportPdfGenerator;
use App\Services\PdfGenerators\SupplierInvoicePdfGenerator;
use App\Services\PdfGenerators\UserActivityReportPdfGenerator;

class PdfGeneratorFactory
{
    public static function create(string $category, StorageStrategyInterface $storageStrategy)
    {
        $generator = match ($category) {
            'supplier_invoice' => new SupplierInvoicePdfGenerator($storageStrategy),
            'client_invoice' => new ClientInvoicePdfGenerator($storageStrategy),
            'nps_report' => new NpsReportPdfGenerator($storageStrategy),
            'user_activity' => new UserActivityReportPdfGenerator($storageStrategy),
            'sales_stock' => new SalesStockReportPdfGenerator($storageStrategy),
            'games_activity' => new GamesActivityReportPdfGenerator($storageStrategy),
            'products_activity' => new ProductsActivityReportPdfGenerator($storageStrategy),
            'rewards_activity' => new RewardsActivityReportPdfGenerator($storageStrategy),
            default => null
        };

        return $generator;
    }

}
