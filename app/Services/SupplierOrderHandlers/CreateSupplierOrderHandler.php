<?php
namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Events\SupplierOrderSuccessEvent;
use App\Services\SupplierOrderNotificationService;
use Faker\Provider\Uuid;


class CreateSupplierOrderHandler extends AbstractSupplierOrderHandler
{
    protected SupplierOrderNotificationService $notificationService;

    public function __construct(SupplierOrderNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        if (!$event) {
            return;
        }

        try {
            $details = json_decode($event->details, true);

            $order = SupplierOrder::create([
                'id' => Uuid::uuid(),
                'supplier_id' => $details['supplier'],
                'total_price' => $this->calculateTotal($details['productQuantities']),
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

            broadcast(new SupplierOrderSuccessEvent($order, $admin->id));

            // Trimitem mai departe în lanț
            $this->nextHandler?->handle($event, $order);
        } catch (\Exception $e) {
            $this->notificationService->notifyAdminForOrderError(
                "Eroare la crearea comenzii: " . $e->getMessage(),
                $event->id
            );
        }
    }

    private function calculateTotal(array $productQuantities)
    {
        return array_reduce($productQuantities, function ($total, $productData) {
            $supplierProduct = SupplierProduct::find($productData['productId']);
            return $total + ($supplierProduct->price * $productData['quantity']);
        }, 0);
    }
}
