<?php
namespace App\Services\SupplierLowStockOrderHandlers;

use App\Interfaces\SupplierLowStockOrderHandlerInterface;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;

abstract class AbstractSupplierLowStockOrderHandler implements SupplierLowStockOrderHandlerInterface
{
    protected ?SupplierLowStockOrderHandlerInterface $nextHandler = null;

    public function setNext(SupplierLowStockOrderHandlerInterface $next): SupplierLowStockOrderHandlerInterface
    {
        $this->nextHandler = $next;
        return $next;
    }

    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null,?SupplierOrder $order = null)
    {
        if ($this->nextHandler) {
            $this->nextHandler->handle($quantity, $supplierProduct);
        }
    }
}
