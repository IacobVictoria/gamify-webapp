<?php

namespace App\Interfaces;

use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;

interface SupplierLowStockOrderHandlerInterface
{
    public function setNext(SupplierLowStockOrderHandlerInterface $next): SupplierLowStockOrderHandlerInterface;
    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null,?SupplierOrder $order = null);
}