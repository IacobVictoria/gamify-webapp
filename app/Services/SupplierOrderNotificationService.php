<?php

namespace App\Services;

use App\Events\NewProductNotificationEvent;
use App\Events\ProductRestockedNotificationEvent;
use App\Events\SupplierOrderErrorEvent;
use App\Interfaces\SupplierOrderNotificationInterface;
use App\Models\Notification;
use App\Models\User;
use Faker\Provider\Uuid;
class SupplierOrderNotificationService implements SupplierOrderNotificationInterface
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function notifyUserForNewProduct($newProduct)
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->hasRole('User')) {
                broadcast(new NewProductNotificationEvent($newProduct, $user, $this->notificationService));
            }

        }
    }

    public function notifyUserForRestockedProductWishlist($product)
    {
        $users = $this->getUsersWithProductInWishlist($product->id);

        foreach ($users as $user) {
            broadcast(new ProductRestockedNotificationEvent($product, $user, $this->notificationService));
        }
    }
    /**
     * Obține utilizatorii care au un anumit produs în wishlist.
     */
    private function getUsersWithProductInWishlist($productId)
    {
        return User::whereHas('wishlists', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->get();
    }

    public function notifyAdminForOrderError($errorMessage, $eventId)
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->first();

        if ($admin) {
            broadcast(new SupplierOrderErrorEvent($errorMessage, $admin->id));

            Notification::create([
                'id' => Uuid::uuid(),
                'message' => 'Eroare procesare comandă: ' . $errorMessage,
                'type' => 'error',
                'user_id' => $admin->id,
                'data' => json_encode(['eventId' => $eventId, 'errorMessage' => $errorMessage]),
                'is_read' => false,
                'handled' => false,
            ]);
        }
    }

    public function notifyAdminErrorLowStockSupplier($message, $productId)
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->first();

        if ($admin) {
            broadcast(new SupplierOrderErrorEvent($message, $admin->id));

            Notification::create([
                'id' => Uuid::uuid(),
                'message' => 'Eroare procesare comandă: ' . $message,
                'type' => 'error',
                'user_id' => $admin->id,
                'data' => json_encode(['productId' => $productId, 'errorMessage' => $message]),
                'is_read' => false,
                'handled' => false,
            ]);
        }
    }

}