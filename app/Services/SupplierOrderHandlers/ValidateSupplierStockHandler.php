<?php
namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;
use App\Services\SupplierOrderNotificationService;
use Illuminate\Support\Facades\Log;

class ValidateSupplierStockHandler extends AbstractSupplierOrderHandler
{
    protected SupplierOrderNotificationService $notificationService;

    public function __construct(SupplierOrderNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        if (!$event ) {
            return;
        }

        $details = json_decode($event->details, true) ?? [];
        $productQuantities = $details['productQuantities'] ?? [];

        foreach ($productQuantities as $productData) {
            $supplierProduct = SupplierProduct::find($productData['productId']);

            if (!$supplierProduct || $supplierProduct->stock < $productData['quantity']) {
                $errorMessage = "Stoc insuficient pentru produsul {$supplierProduct->name}. Disponibil: {$supplierProduct->stock}, solicitat: {$productData['quantity']}";
                $this->notificationService->notifyAdminForOrderError($errorMessage, $event->id);
                return; // Ieșim din chain dacă un produs nu are stoc suficient
            }
        }

        $this->nextHandler?->handle($event, $order);
    }
}
