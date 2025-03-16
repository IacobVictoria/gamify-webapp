<?php
namespace App\Services\OrderHandlers;

use App\Models\ClientOrder;
use App\Enums\OrderStatus;
use App\Models\OrderProduct;
use App\Models\Product;
use Faker\Provider\Uuid;

class CreateProductsOrderHandler extends AbstractOrderHandler
{
    public function handle(ClientOrder $order, array $validatedData): void
    {
        if (!isset($validatedData['cartItems']) || !is_array($validatedData['cartItems'])) {
            throw new \Exception("Eroare: Nu există produse în cartItems sau format invalid.");
        }

        foreach ($validatedData['cartItems'] as $item) {
            $product = Product::findOrFail($item['product']['id']);

            OrderProduct::create([
                'id' => Uuid::uuid(),
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }
        parent::handle($order, $validatedData);
    }
}