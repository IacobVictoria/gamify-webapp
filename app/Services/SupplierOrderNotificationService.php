<?php

namespace App\Services;

use App\Events\NewProductNotificationEvent;
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

    }

}