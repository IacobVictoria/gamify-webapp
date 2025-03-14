<?php
namespace App\Services\SupplierOrderHandlers;

use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Models\Event;
use App\Models\SupplierOrder;
use App\Models\Report;
use App\Models\SupplierProduct;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class GenerateSupplierInvoiceHandler extends AbstractSupplierOrderHandler
{
    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        if (!$event || !$order) {
            return;
        }

        try {
            $details = json_decode($event->details, true) ?? [];

            $storageStrategy = StorageStrategyFactory::create('s3');
            $generator = PdfGeneratorFactory::create('supplier_invoice', $storageStrategy);
            $filename = "supplier_invoice_{$order->id}.pdf";

            $products = collect($details['productQuantities'])->map(function ($product) {
                $productData = SupplierProduct::find($product['productId']);

                if ($productData) {
                    $product['productData'] = $productData;
                } else {
                    $product['productData'] = null;  // Asigură-te că atribui `null` când produsul nu este găsit
                }

                return $product;
            });

            $invoiceData = [
                'order' => $order,
                'products' => $products, // Lista produselor și cantitățile
                'supplierName' => $details['supplierName'],
                'filename' => $filename
            ];

            // Generăm PDF și îl salvăm
            $filePath = $generator->generatePdf($invoiceData);

            // Salvăm factura în tabelul reports
            $report = Report::create([
                'id' => Uuid::uuid(),
                'type' => 'supplier_invoice',
                'title' => "Factura pentru Comanda {$order->id}",
                's3_path' => $filePath,
            ]);
            
            $order->report_id = $report->id;
            $order->save();

            // 🔹 Salvăm referința către factură în event details
            $details = json_decode($event->details, true) ?? [];
            $details['s3_path'] = $filePath;
            $event->details = json_encode($details);
            $event->save();

            // Continuăm chain-ul
            $this->nextHandler?->handle($event, $order);
        } catch (\Exception $e) {
            Log::error("Error generating invoice: " . $e->getMessage());
        }
    }
}
