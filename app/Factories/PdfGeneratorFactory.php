<?php
namespace App\Factories;

use App\Interfaces\StorageStrategyInterface;
use App\Services\PdfGenerators\ClientInvoicePdfGenerator;
use App\Services\PdfGenerators\ParticipantsListPdfGenerator;
use App\Services\PdfGenerators\SupplierInvoicePdfGenerator;
use Illuminate\Support\Facades\Log;


class PdfGeneratorFactory
{
    public static function create(string $type, StorageStrategyInterface $storageStrategy)
    {
        Log::info("🛠 Creare generator PDF de tip: {$type}");
    
        $generator = match ($type) {
            'participants' => new ParticipantsListPdfGenerator($storageStrategy),
            'supplier_invoice' => new SupplierInvoicePdfGenerator($storageStrategy),
            'client_invoice' => new ClientInvoicePdfGenerator($storageStrategy),
            default => null
        };
    
        if (!$generator) {
            Log::error("❌ `PdfGeneratorFactory` nu a putut crea un generator pentru tipul {$type}");
        } else {
            Log::info("✅ Generator creat cu succes: " . get_class($generator));
        }
    
        return $generator;
    }
    
}
