<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;
use App\Services\Badges\ShoppingBadgeService;

class ApplyBadgeHandler extends AbstractPaymentHandler
{
    protected $badgeService;

    public function __construct(ShoppingBadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function handle(ClientOrder $order, array $paymentData): void
    {
        $this->badgeService->checkAndAssignBadges($order->user);

        parent::handle($order, $paymentData);
    }
}
