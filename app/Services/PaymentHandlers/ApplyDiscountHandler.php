<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;
use App\Services\DiscountService;

class ApplyDiscountHandler extends AbstractPaymentHandler
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function handle(ClientOrder $order, array $paymentData): void
    {
        if ($order->promo_code) {
            $this->discountService->markPromoCodeAsUsed($order->user, $order->promo_code);
        }

        parent::handle($order, $paymentData);
    }
}
