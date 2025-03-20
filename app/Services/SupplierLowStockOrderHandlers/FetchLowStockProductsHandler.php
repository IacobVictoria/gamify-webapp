<?php
namespace App\Services\SupplierLowStockOrderHandlers;

use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;
use App\Services\SupplierOrderNotificationService;

class FetchLowStockProductsHandler extends AbstractSupplierLowStockOrderHandler
{
    protected SupplierOrderNotificationService $notificationService;

    public function __construct(SupplierOrderNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    private int $threshold = 5; // Pragul minim pentru a considera un produs cu stoc scăzut
    private int $maxOrderQuantity = 50; // Cantitate maximă comandată per produs
    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null,?SupplierOrder $order = null,)
    {
        // Găsim toate produsele cu stoc sub pragul minim
        $lowStockProducts = Product::where('stock', '<', $this->threshold)->get();

        if ($lowStockProducts->isEmpty()) {
            return;
        }

        foreach ($lowStockProducts as $product) {
            $supplierProduct = SupplierProduct::where('product_sku', $product->product_sku)
                ->first();

            if (!$supplierProduct) {
                $messageError = "Produsul '{$product->name}' nu a fost găsit la furnizor.";
                $this->notificationService->notifyAdminErrorLowStockSupplier($messageError, $product->id);
                continue;
            }
            $orderQuantity = min($this->maxOrderQuantity, $supplierProduct->stock);

            if ($orderQuantity <= 0) {
                $this->notificationService->notifyAdminErrorLowStockSupplier(
                    "Stoc insuficient pentru produsul '{$product->name}' la furnizor.",
                    $product->id
                );
                continue;
            }
   
            $this->nextHandler?->handle($orderQuantity,$supplierProduct, $order);
        }
    }
}