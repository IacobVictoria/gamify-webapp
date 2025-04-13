<?php

namespace App\Services\SupplierLowStockOrderHandlers;

use App\Events\SupplierOrderSuccessEvent;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\SupplierOrderNotificationService;
use Faker\Provider\Uuid;


class CreateSupplierLowStockOrderHandler extends AbstractSupplierLowStockOrderHandler
{
    protected SupplierOrderNotificationService $notificationService;
    protected NotificationService $notifService;

    public function __construct(SupplierOrderNotificationService $notificationService, NotificationService $notifService)
    {
        $this->notificationService = $notificationService;
        $this->notifService = $notifService;
    }

    public function handle(?int $quantity = null, ?SupplierProduct $supplierProduct = null, ?SupplierOrder $order = null)
    {
        if (!$supplierProduct) {
            return;
        }

        try {
            $order = SupplierOrder::create([
                'id' => Uuid::uuid(),
                'supplier_id' => $supplierProduct->supplier_id,
                'total_price' => $supplierProduct->price * $quantity,
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

            $admin = User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })->first();

            if ($admin) {
                broadcast(new SupplierOrderSuccessEvent($order, $admin->id, $this->notifService));
            }

            $this->nextHandler?->handle($quantity, $supplierProduct, $order);
        } catch (\Exception $e) {

            $this->notificationService->notifyAdminErrorLowStockSupplier(
                "Eroare la crearea comenzii pentru produsul '{$supplierProduct->name}': {$e->getMessage()}",
                $supplierProduct->id
            );
        }
    }
}
