<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;


class ClearCartHandler extends AbstractPaymentHandler
{
    public function handle(ClientOrder $order, array $paymentData): void
    {
        $userId = $order->user_id;

        if ($userId) {
            Session::forget('cart');
            
            Cookie::queue(Cookie::forget('cart_' . $userId));
        }

        parent::handle($order, $paymentData);
    }
}
