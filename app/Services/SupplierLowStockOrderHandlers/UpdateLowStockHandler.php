<?php

namespace App\Services\SupplierLowStockOrderHandlers;

use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;

class UpdateLowStockHandler extends AbstractSupplierLowStockOrderHandler
{
    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null, ?SupplierOrder $order = null)
    {
        if (!$supplierProduct || !$order) {
            return;
        }

        try {
            $product = Product::where('name', $supplierProduct->name)
                ->where('category', $supplierProduct->category)
                ->first();

            $supplierProduct->stock -= $quantity;
            $supplierProduct->save();

            $product->stock += $quantity;
            $product->save();

            $this->nextHandler?->handle($quantity, $supplierProduct, $order);
        } catch (\Exception $e) {

        }
    }
}