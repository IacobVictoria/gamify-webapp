<?php
namespace App\Factories;

use App\Interfaces\StorageStrategyInterface;
use App\Services\PdfGenerators\ClientInvoicePdfGenerator;
use App\Services\PdfGenerators\GamesActivityMonthlyReportPdfGenerator;
use App\Services\PdfGenerators\NpsReportPdfGenerator;
use App\Services\PdfGenerators\ParticipantsListPdfGenerator;
use App\Services\PdfGenerators\ProductsActivityMonthlyReportPdfGenerator;
use App\Services\PdfGenerators\RewardsActivityMonthlyReportPdfGenerator;
use App\Services\PdfGenerators\SalesStockMonthlyReportPdfGenerator;
use App\Services\PdfGenerators\SupplierInvoicePdfGenerator;
use App\Services\PdfGenerators\UserActivityMonthlyReportPdfGenerator;

class PdfGeneratorFactory
{
    public static function create(string $categoryId, StorageStrategyInterface $storageStrategy)
    {
        $generator = match ($categoryId) {
            'participants' => new ParticipantsListPdfGenerator($storageStrategy),
            'supplier_invoice' => new SupplierInvoicePdfGenerator($storageStrategy),
            'client_invoice' => new ClientInvoicePdfGenerator($storageStrategy),
            'nps_report' => new NpsReportPdfGenerator($storageStrategy),
            'user_activity_monthly' => new UserActivityMonthlyReportPdfGenerator($storageStrategy),
            'sales_stock_monthly' => new SalesStockMonthlyReportPdfGenerator($storageStrategy),
            'games_activity_monthly' => new GamesActivityMonthlyReportPdfGenerator($storageStrategy),
            'products_activity_monthly' => new ProductsActivityMonthlyReportPdfGenerator($storageStrategy),
            'rewards_activity_monthly' => new RewardsActivityMonthlyReportPdfGenerator($storageStrategy),
            default => null
        };

        return $generator;
    }

}
