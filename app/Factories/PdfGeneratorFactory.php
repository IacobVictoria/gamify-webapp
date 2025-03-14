<?php
namespace App\Factories;

use App\Interfaces\StorageStrategyInterface;
use App\Services\PdfGenerators\ClientInvoicePdfGenerator;
use App\Services\PdfGenerators\NpsReportPdfGenerator;
use App\Services\PdfGenerators\ParticipantsListPdfGenerator;
use App\Services\PdfGenerators\SupplierInvoicePdfGenerator;
use Illuminate\Support\Facades\Log;


class PdfGeneratorFactory
{
    public static function create(string $type, StorageStrategyInterface $storageStrategy)
    {
        $generator = match ($type) {
            'participants' => new ParticipantsListPdfGenerator($storageStrategy),
            'supplier_invoice' => new SupplierInvoicePdfGenerator($storageStrategy),
            'client_invoice' => new ClientInvoicePdfGenerator($storageStrategy),
            'nps_report' => new NpsReportPdfGenerator($storageStrategy),
            default => null
        };

        return $generator;
    }

}
