<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;
use App\Models\User;
use App\Jobs\ExpediteOrderJob;

class ExpediteOrderHandler extends AbstractPaymentHandler
{
    public function handle(ClientOrder $order, array $paymentData): void
    {
        $user = User::findOrFail($order->user_id);
        ExpediteOrderJob::dispatch($order, $user)->delay(now()->addMinutes(1));

        parent::handle($order, $paymentData);
    }
}
