<?php

namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\InventoryTransaction;
use App\Models\SupplierOrder;
use App\Models\Product;
use App\Enums\TransactionType;
use App\Models\SupplierProduct;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class RegisterInventoryTransactionHandler extends AbstractSupplierOrderHandler
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
            
            $product = Product::where('name', $supplierProduct->name)
            ->where('category', $supplierProduct->category)
            ->first();

            InventoryTransaction::create([
                'id' => Uuid::uuid(),
                'transaction_type' => TransactionType::IN->value, // Tranzacție de tip IN (aprovizionare)
                'supplier_order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'transaction_date' => now(),
                'description' => "Aprovizionare de la furnizor {$order->supplier->name} pentru produsul {$product->name}",
            ]);
        }

        $this->nextHandler?->handle($event, $order);
    }
}
