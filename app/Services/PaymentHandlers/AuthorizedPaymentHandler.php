<?php
namespace App\Services\PaymentHandlers;

use App\Events\OrderFailedEvent;
use App\Models\ClientOrder;
use App\Enums\OrderStatus;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class AuthorizedPaymentHandler extends AbstractPaymentHandler
{
    public function handle(ClientOrder $order, array $paymentData): void
    {
        try {
            DB::beginTransaction();
            $order->update(['status' => OrderStatus::Authorized]);

            parent::handle($order, $paymentData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            event(new OrderFailedEvent($order->user, $order, app(NotificationService::class), 'Plata nu a fost autorizată.'));
            throw $e;
        }
    }
}
