<?php

namespace App\Services\SupplierLowStockOrderHandlers;

use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderProduct;
use App\Models\SupplierProduct;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class CreateSupplierLowStockOrderProductsHandler extends AbstractSupplierLowStockOrderHandler
{
    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null, ?SupplierOrder $order = null)
    {
        if (!$supplierProduct || !$order) {
            return;
        }

        // Adăugăm produsul în comanda furnizorului
        SupplierOrderProduct::create([
            'id' => Uuid::uuid(),
            'order_id' => $order->id,
            'product_id' => $supplierProduct->id,
            'quantity' => $quantity,
            'price' => $supplierProduct->price,
        ]);


        $this->nextHandler?->handle($quantity, $supplierProduct, $order);
    }
}
