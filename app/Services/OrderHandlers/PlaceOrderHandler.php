<?php
namespace App\Services\OrderHandlers;

use App\Models\ClientOrder;
use App\Enums\OrderStatus;

class PlaceOrderHandler extends AbstractOrderHandler
{
    public function handle(ClientOrder $order, array $validatedData): void
    {
        $order->update([
            'status' => OrderStatus::Placed,
            'placed_at' => now(),
        ]);

        parent::handle($order, $validatedData);
    }
}
