<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;
use App\Models\Product;
use App\Models\OrderProduct;
use Faker\Provider\Uuid;

class UpdateStockHandler extends AbstractPaymentHandler
{
    public function handle(ClientOrder $order, array $validatedData): void
    {
        if ($order->status === 'Authorized') {
            foreach ($validatedData['cartItems'] as $item) {
                $product = Product::findOrFail($item['product']['id']);

                OrderProduct::create([
                    'id' => Uuid::uuid(),
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $product->stock -= $item['quantity'];
                $product->save();
            }
        }

        parent::handle($order, $validatedData);
    }
}
