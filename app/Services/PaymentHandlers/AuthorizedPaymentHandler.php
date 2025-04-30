<?php
namespace App\Services\PaymentHandlers;

use App\Events\OrderFailedEvent;
use App\Jobs\SendWhatsappMessageJob;
use App\Models\ClientOrder;
use App\Enums\OrderStatus;
use App\Models\Product;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class AuthorizedPaymentHandler extends AbstractPaymentHandler
{
    public function handle(ClientOrder $order, array $paymentData): void
    {
        try {
            DB::beginTransaction();
            SendWhatsappMessageJob::dispatch('order_confirmed', ['name' => $order->user->name, 'order_id' => $order->id]);

            parent::handle($order, $paymentData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            event(new OrderFailedEvent($order->user, $order, app(NotificationService::class), 'Procesul comenzii nu a decurs bine.'));
            // Refacem stocul doar dacă a fost deja modificat (după autorizare)
            if ($order->status === OrderStatus::Authorized) {
                $this->restoreStock($order);
            }
            $order->update(['status' => OrderStatus::Canceled]);
            throw $e;
        }
    }
    public function restoreStock(ClientOrder $order)
    {
        foreach ($order->products as $orderProduct) {
            $product = Product::find($orderProduct->product_id);
            if ($product) {
                $product->stock += $orderProduct->quantity;
                $product->save();
            }
        }
    }

}
