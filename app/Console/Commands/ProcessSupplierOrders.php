<?php

namespace App\Console\Commands;

use App\Events\ProductRestockedNotificationEvent;
use App\Events\SupplierOrderErrorEvent;
use App\Events\SupplierOrderSuccessEvent;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Report;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderProduct;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Services\DompdfGeneratorService;
use App\Services\SupplierOrderNotificationService;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessSupplierOrders extends Command
{
    protected $signature = 'orders:process-supplier-orders';

    protected $description = 'Verifică evenimentele de tip supplier_order din calendar și plasează comenzile corespunzătoare';
    protected $supplierOrderService;
    protected $pdfGenerator;
    public function __construct(SupplierOrderNotificationService $supplierOrderService, DompdfGeneratorService $pdfGenerator)
    {
        parent::__construct();
        $this->supplierOrderService = $supplierOrderService;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function handle()
    {
        $today = Carbon::today();

        // Obținem evenimentele de tip 'supplier_order' din calendar pentru ziua de azi
        $events = Event::where('type', 'supplier_order')
            ->whereDate('start', $today)
            ->get();

        if ($events->isEmpty()) {
            $this->info('Nu există evenimente de tip supplier_order pentru astăzi.');
            return;
        }

        foreach ($events as $event) {
            $this->processEvent($event);
        }

        $this->info('Comenzile au fost procesate.');
    }

    private function processEvent($event)
    {
        $details = $this->parseEventDetails($event);
        if (!$details) {
            return;
        }

        $order = $this->createSupplierOrder($details, $event);
        if (!$order) {
            return;
        }

        $this->processProducts($details['productQuantities'], $order, $event);

        $this->info('Comanda a fost creată pentru furnizorul ' . $details['supplierName']);
    }
    private function parseEventDetails($event)
    {
        $details = json_decode($event->details, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->handleError('JSON invalid pentru evenimentul: ' . $event->id, $event->id);
            return null;
        }

        if (!isset($details['supplier'], $details['supplierName'], $details['productQuantities'])) {
            $this->handleError('Informațiile sunt incomplete în detaliile evenimentului: ' . $event->id, $event->id);
            return null;
        }

        return $details;
    }
    private function createSupplierOrder($details, $event)
    {
        try {
            $order = SupplierOrder::create([
                'id' => Uuid::uuid(),
                'supplier_id' => $details['supplier'],
                'total_price' => $this->calculateTotal($details['productQuantities']),
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
            // Generăm și salvăm factura
            $this->generateAndSaveInvoice($order, $details);

            $admin = User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })->first();

            broadcast(new SupplierOrderSuccessEvent($order, $admin->id));
            return $order;
        } catch (\Exception $e) {
            $this->handleError('Eroare la crearea comenzii: ' . $e->getMessage(), $event->id);
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

    private function processProducts($productQuantities, $order, $event)
    {
        foreach ($productQuantities as $productData) {
            $supplierProduct = SupplierProduct::find($productData['productId']);

            if (!$this->validateStock($supplierProduct, $productData, $event)) {
                continue;
            }

            $this->createSupplierOrderProduct($order, $productData);
            $this->updateStock($productData); // Notificările sunt acum gestionate aici
        }
    }
    private function validateStock($supplierProduct, $productData, $event)
    {
        if ($supplierProduct->stock < $productData['quantity']) {
            $errorMessage = "Stoc insuficient pentru produsul {$supplierProduct->name}. Disponibil: {$supplierProduct->stock}, solicitat: {$productData['quantity']}";
            $this->handleError($errorMessage, $event->id);
            return false;
        }

        return true;
    }

    private function handleError($message, $eventId)
    {
        $this->error($message);
        $this->broadcastErrorToAdmin($message, $eventId);
    }
    private function createSupplierOrderProduct($order, $productData)
    {
        $supplierProduct = SupplierProduct::find($productData['productId']);

        if (!$supplierProduct) {
            $this->error("Produsul cu ID {$productData['productId']} nu a fost găsit la furnizor.");
            return;
        }

        $order = SupplierOrderProduct::create([
            'id' => Uuid::uuid(),
            'order_id' => $order->id,
            'product_id' => $productData['productId'],
            'quantity' => $productData['quantity'],
            'price' => $supplierProduct->price,
        ]);
    }
    private function updateStock($productData)
    {
        $supplierProduct = SupplierProduct::find($productData['productId']);

        if (!$supplierProduct) {
            $this->error("Produsul cu ID {$productData['productId']} nu a fost găsit la furnizor.");
            return;
        }

        $wasOutOfStock = $supplierProduct->stock === 0;

        // Actualizare stoc la furnizor
        $supplierProduct->stock -= $productData['quantity'];
        $supplierProduct->save();

        // Caut produsul în baza de date locală
        $product = Product::where('name', $supplierProduct->name)
            ->where('category', $supplierProduct->category)
            ->first();

        if ($product) {
            // Dacă produsul există, actualizăm stocul local
            $product->stock += $productData['quantity'];
            $product->save();

            if ($wasOutOfStock && $product->stock > 0) {
                $this->supplierOrderService->notifyUserForRestockedProductWishlist($product);
            }

        } else {
            // Dacă produsul nu există, îl creăm și notificăm utilizatorii
            $newProduct = Product::create([
                'id' => Uuid::uuid(),
                'name' => $supplierProduct->name,
                'price' => $supplierProduct->price,
                'category' => $supplierProduct->category,
                'description' => $supplierProduct->description,
                'stock' => $productData['quantity'],
                'score' => $supplierProduct->score,
                'calories' => $supplierProduct->calories,
                'protein' => $supplierProduct->protein,
                'carbs' => $supplierProduct->carbs,
                'fats' => $supplierProduct->fats,
                'fiber' => $supplierProduct->fiber,
                'sugar' => $supplierProduct->sugar,
                'ingredients' => $supplierProduct->ingredients,
                'allergens' => $supplierProduct->allergens
            ]);

            // Notificare pentru produs nou
            $this->supplierOrderService->notifyUserForNewProduct($newProduct);
        }
    }

    private function calculateTotal($productQuantities)
    {
        return array_reduce($productQuantities, function ($total, $productData) {
            $supplierProduct = SupplierProduct::find($productData['productId']);
            return $total + ($supplierProduct->price * $productData['quantity']);
        }, 0);
    }
    private function broadcastErrorToAdmin($errorMessage, $eventId)
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->first();

        if ($admin) {
            broadcast(new SupplierOrderErrorEvent($errorMessage, $admin->id));

            Notification::create([
                'id' => Uuid::uuid(),
                'message' => 'Eroare procesare comandă: ' . $errorMessage,
                'type' => 'error',
                'user_id' => $admin->id,
                'data' => json_encode(['eventId' => $eventId, 'errorMessage' => $errorMessage]),
                'is_read' => false,
                'handled' => false,
            ]);
        }
    }

}
