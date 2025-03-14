<?php

namespace App\Services\SupplierLowStockOrderHandlers;

use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Models\Report;
use App\Models\SupplierOrder;
use App\Models\Product;
use App\Models\SupplierProduct;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class GenerateSupplierLowStockInvoiceHandler extends AbstractSupplierLowStockOrderHandler
{
    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null, ?SupplierOrder $order = null)
    {
        if (!$order) {
            return;
        }

        $details = [
            'productQuantities' => [
                [
                    'productId' => $supplierProduct->id,
                    'quantity' => $quantity,
                    'price' => $supplierProduct->price,
                ]
            ],
            'supplierName' => $supplierProduct->supplier->name, // Presupunem că furnizorul are atributul `name`
        ];

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
        // Datele pentru generare PDF
        $invoiceData = [
            'order' => $order,
            'products' => $products, // Lista produselor și cantitățile
            'supplierName' => $details['supplierName'],
            'filename' => $filename
        ];
        // Generăm factura și o salvăm
        $filePath = $generator->generatePdf($invoiceData);
        $report = Report::create([
            'id' => Uuid::uuid(),
            'type' => 'supplier_invoice',
            'title' => "Factura pentru Comanda {$order->id}",
            's3_path' => $filePath,
        ]);

        $order->report_id = $report->id;
        $order->save();
        $this->nextHandler?->handle($quantity, $supplierProduct, $order);
    }
}
