<?php
namespace App\Factories;

use App\Interfaces\StorageStrategyInterface;
use App\Services\PdfGenerators\ClientInvoicePdfGenerator;
use App\Services\PdfGenerators\NpsReportPdfGenerator;
use App\Services\PdfGenerators\ParticipantsListPdfGenerator;
use App\Services\PdfGenerators\SalesStockMonthlyReportPdfGenerator;
use App\Services\PdfGenerators\SupplierInvoicePdfGenerator;
use App\Services\PdfGenerators\UserActivityMonthlyReportPdfGenerator;

class PdfGeneratorFactory
{
    public static function create(string $type, StorageStrategyInterface $storageStrategy)
    {
        $generator = match ($type) {
            'participants' => new ParticipantsListPdfGenerator($storageStrategy),
            'supplier_invoice' => new SupplierInvoicePdfGenerator($storageStrategy),
            'client_invoice' => new ClientInvoicePdfGenerator($storageStrategy),
            'nps_report' => new NpsReportPdfGenerator($storageStrategy),
            'user_activity_monthly' => new UserActivityMonthlyReportPdfGenerator($storageStrategy),
            'sales_stock_monthly' => new SalesStockMonthlyReportPdfGenerator($storageStrategy),
            default => null
        };

        return $generator;
    }

}
