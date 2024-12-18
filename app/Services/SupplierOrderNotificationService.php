<?php

namespace App\Services;

use App\Events\NewProductNotificationEvent;
use App\Events\ProductRestockedNotificationEvent;
use App\Interfaces\SupplierOrderNotificationInterface;
use App\Models\User;
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

}