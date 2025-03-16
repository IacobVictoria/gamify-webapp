<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;
use App\Models\Product;
use App\Models\InventoryTransaction;
use App\Models\OrderProduct;
use App\Enums\TransactionType;
use Illuminate\Support\Facades\Log;
use Faker\Provider\Uuid;

class InventoryTransactionHandler extends AbstractPaymentHandler
{
    public function handle(ClientOrder $order, array $validatedData): void
    {
            foreach ($order->products as $product) {

                InventoryTransaction::create([
                    'id' => Uuid::uuid(),
                    'transaction_type' => TransactionType::OUT->value, // Vânzare către client
                    'product_id' => $product->id,
                    'client_order_id' => $order->id,
                    'quantity' => $product->pivot->quantity,
                    'transaction_date' => now(),
                    'description' => "Vânzare către clientul cu id-ul {$order->user->id} pentru produsul {$product->name}",
                ]);

            
        }

        parent::handle($order, $validatedData);
    }
}
