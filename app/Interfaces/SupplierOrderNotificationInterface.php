<?php

namespace App\Interfaces;

interface SupplierOrderNotificationInterface
{
    public function notifyUserForNewProduct($product);
    public function notifyUserForRestockedProductWishlist($product);
}