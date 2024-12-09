<?php

namespace App\Console\Commands;

use App\Events\SupplierOrderErrorEvent;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderProduct;
use App\Models\SupplierProduct;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;

class ProcessSupplierOrders extends Command
{
    protected $signature = 'orders:process-supplier-orders';

    protected $description = 'Verifică evenimentele de tip supplier_order din calendar și plasează comenzile corespunzătoare';

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
        $details = json_decode($event->details, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('JSON invalid pentru evenimentul: ' . $event->id);
            $this->broadcastErrorToAdmin('JSON invalid pentru evenimentul: ' . $event->id, $event->id);
            return;
        }

        if (!isset($details['supplier']) || !isset($details['supplierName']) || !isset($details['productQuantities'])) {
            $this->error('Informațiile sunt incomplete în detaliile evenimentului: ' . $event->id);
            $this->broadcastErrorToAdmin('Informațiile sunt incomplete în detaliile evenimentului: ' . $event->id, $event->id);
            return;
        }

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

        foreach ($details['productQuantities'] as $productData) {
            $supplierProduct = SupplierProduct::find($productData['productId']);
            
            //disponibilitate furnizori
            if ($supplierProduct->stock < $productData['quantity']) {
                $errorMessage = "Stoc insuficient pentru produsul {$supplierProduct->name}. Disponibil: {$supplierProduct->stock}, solicitat: {$productData['quantity']}";
                $this->error($errorMessage);
                $this->broadcastErrorToAdmin($errorMessage, $event->id);
                continue;  // Trecem la următorul produs
            }
            $this->createSupplierOrderProduct($order, $productData);
            $this->updateStock($productData);
        }

        $this->info('Comanda a fost creată pentru furnizorul ' . $details['supplierName']);
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

        if ($supplierProduct) {
            $supplierProduct->stock -= $productData['quantity'];
            $supplierProduct->save();
        } else {
            $this->error("Produsul cu ID {$productData['productId']} nu a fost găsit la furnizor.");
        }

        $product = Product::where('name', $supplierProduct->name)
            ->where('category', $supplierProduct->category)
            ->first();

        if ($product) {
            $product->stock += $productData['quantity'];
            $product->save();
        } else {

            Product::create([
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
            event(new SupplierOrderErrorEvent($errorMessage, $admin->id));


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
