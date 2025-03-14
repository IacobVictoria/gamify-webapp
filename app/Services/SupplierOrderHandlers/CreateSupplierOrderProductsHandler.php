<?php
namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderProduct;
use App\Models\SupplierProduct;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class CreateSupplierOrderProductsHandler extends AbstractSupplierOrderHandler
{
    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        if (!$event || !$order) {
            return;
        }

        $details = json_decode($event->details, true) ?? [];
        $productQuantities = $details['productQuantities'] ?? [];

        foreach ($productQuantities as $productData) {
            $supplierProduct = SupplierProduct::find($productData['productId']);

            if (!$supplierProduct) {
                Log::error("Produsul cu ID {$productData['productId']} nu a fost găsit.");
                continue;
            }

            SupplierOrderProduct::create([
                'id' => Uuid::uuid(),
                'order_id' => $order->id,
                'product_id' => $supplierProduct->id,
                'quantity' => $productData['quantity'],
                'price' => $supplierProduct->price,
            ]);

            Log::info("Produs {$supplierProduct->name} adăugat în comanda {$order->id}.");
        }

        $this->nextHandler?->handle($event, $order);
    }
}
