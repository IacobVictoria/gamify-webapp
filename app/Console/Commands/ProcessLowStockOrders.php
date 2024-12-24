<?php

namespace App\Console\Commands;

use App\Events\SupplierOrderErrorEvent;
use App\Events\SupplierOrderSuccessEvent;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Report;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderProduct;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Services\DompdfGeneratorService;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;

class ProcessLowStockOrders extends Command
{
    protected $signature = 'orders:process-low-stock';
    protected $description = 'Verifică stocurile și generează comenzi pentru produsele cu stoc scăzut';
    protected $pdfGenerator;
    public function __construct(DompdfGeneratorService $pdfGenerator)
    {
        parent::__construct();
        $this->pdfGenerator = $pdfGenerator;
    }
    public function handle()
    {
        $this->info('Procesarea comenzilor pentru stocuri scăzute...');
        $this->processLowStockProducts();
        $this->info('Procesare finalizată.');
    }
    private function processLowStockProducts()
    {
        $threshold = 5; // Prag minim pentru stocuri
        $maxOrderQuantity = 50; // Cantitate maximă comandată per produs

        // Produsele cu stoc sub prag
        $lowStockProducts = Product::where('stock', '<', $threshold)->get();

        foreach ($lowStockProducts as $product) {
            $supplierProduct = SupplierProduct::where('name', $product->name)
                ->where('category', $product->category)
                ->first();

            if (!$supplierProduct) {
                $this->notifyAdminError(
                    "Produsul '{$product->name}' nu a fost găsit la furnizor.",
                    $product->id
                );
                continue;
            }

            // Cantitatea care trebuie comandată
            $orderQuantity = min($maxOrderQuantity, $supplierProduct->stock);

            if ($orderQuantity <= 0) {
                $this->notifyAdminError(
                    "Stoc insuficient pentru produsul '{$product->name}' la furnizor.",
                    $product->id
                );
                continue;
            }

            // Crearea comenzii
            $order = $this->createSupplierOrderForProduct($supplierProduct, $orderQuantity);

            if ($order) {
                $this->info("Comandă creată pentru produsul '{$product->name}' cu succes.");
                $this->notifyAdminSuccess($order);
            }
        }
    }

    private function createSupplierOrderForProduct($supplierProduct, $quantity)
    {
        try {
            $order = SupplierOrder::create([
                'id' => Uuid::uuid(),
                'supplier_id' => $supplierProduct->supplier_id,
                'total_price' => $supplierProduct->price * $quantity,
                'status' => 'pending',
                'company_name' => 'My company',
                'order_date' => now(),
                'email' => 'contact@company.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address' => '123 Main Street',
                'apartment' => 'Apt 4B',
                'state' => 'Some State',
                'city' => 'Some City',
                'country' => 'Some Country',
                'zip_code' => '12345',
                'phone' => '0123456789',
            ]);

            // Asocierea produselor cu comanda
            SupplierOrderProduct::create([
                'id' => Uuid::uuid(),
                'order_id' => $order->id,
                'product_id' => $supplierProduct->id,
                'quantity' => $quantity,
                'price' => $supplierProduct->price,
            ]);

            // Actualizarea stocurilor
            $supplierProduct->stock -= $quantity;
            $supplierProduct->save();

            $product = Product::where('name', $supplierProduct->name)
                ->where('category', $supplierProduct->category)
                ->first();

            if ($product) {
                $product->stock += $quantity;
                $product->save();
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

            // Generăm factura
            $this->generateAndSaveInvoice($order, $details);

            return $order;
        } catch (\Exception $e) {
            $this->notifyAdminError(
                "Eroare la crearea comenzii pentru produsul '{$supplierProduct->name}': {$e->getMessage()}",
                $supplierProduct->id
            );
            return null;
        }
    }
    private function generateAndSaveInvoice($order, $details)
    {
        // Generăm numele fișierului pentru factură
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

        // Creăm datele pentru factură

        $invoiceData = [
            'order' => $order,
            'products' => $products, // Lista produselor și cantitățile
            'supplierName' => $details['supplierName'], // Numele furnizorului
        ];
        // Generăm PDF-ul și îl salvăm în S3
        $filePath = $this->pdfGenerator->generateInvoicePdf($invoiceData, $filename);

        // Salvăm factura în tabelul reports pentru acces
        Report::create([
            'id' => Uuid::uuid(),
            'type' => 'supplier_invoice',
            'title' => "Factura pentru Comanda {$order->id}",
            's3_path' => $filePath,
        ]);

        $this->info("Factura pentru comanda '{$order->id}' a fost generată și salvată la '{$filePath}'.");
    }
    private function notifyAdminSuccess($order)
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->first();

        if ($admin) {
            broadcast(new SupplierOrderSuccessEvent($order, $admin->id));
        }
    }

    private function notifyAdminError($message, $productId)
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->first();

        if ($admin) {
            broadcast(new SupplierOrderErrorEvent($message, $admin->id));

            Notification::create([
                'id' => Uuid::uuid(),
                'message' => 'Eroare procesare comandă: ' . $message,
                'type' => 'error',
                'user_id' => $admin->id,
                'data' => json_encode(['productId' => $productId, 'errorMessage' => $message]),
                'is_read' => false,
                'handled' => false,
            ]);
        }
    }
}
