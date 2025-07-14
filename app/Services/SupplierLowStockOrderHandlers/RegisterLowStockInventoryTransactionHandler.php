<?php

namespace App\Services\SupplierLowStockOrderHandlers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;
use App\Enums\TransactionType;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class RegisterLowStockInventoryTransactionHandler extends AbstractSupplierLowStockOrderHandler
{
    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null, ?SupplierOrder $order = null)
    {
        if (!$supplierProduct || !$order || !$quantity) {
            return;
        }

        try {
            $product = Product::where('product_sku', $supplierProduct->product_sku)
                ->first();

            InventoryTransaction::create([
                'id' => Uuid::uuid(),
                'transaction_type' => TransactionType::IN->value, // Tranzacție de tip IN (aprovizionare)
                'supplier_order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'transaction_date' => now(),
                'description' => "Aprovizionare de la furnizor pentru produsul {$product->name}",
            ]);

            $this->nextHandler?->handle($quantity, $supplierProduct, $order);
        } catch (\Exception $e) {
            Log::error("Eroare la înregistrarea tranzacției de aprovizionare: {$e->getMessage()}");
        }
    }
}
