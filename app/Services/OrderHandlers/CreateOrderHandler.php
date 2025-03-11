<?php
namespace App\Services\OrderHandlers;

use App\Events\OrderFailedEvent;
use App\Models\ClientOrder;
use App\Enums\OrderStatus;
use App\Models\Product;
use App\Services\NotificationService;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\DB;

class CreateOrderHandler extends AbstractOrderHandler
{
    public function handle(ClientOrder $order, array $validatedData): void
    {
        try {
            DB::beginTransaction();
        $totalPrice = $this->calculateTotal($validatedData['cartItems'], $validatedData['discountAmount'] ?? 0);

        $status = count($validatedData['cartItems']) > 0 ? OrderStatus::Pending : OrderStatus::Created;

        $order->fill([
            'id' => Uuid::uuid(),
            'user_id' => $validatedData['user_id'],
            'total_price' => $totalPrice,  // Calculăm totalul final dinainte
            'status' => $status,
            'email' => $validatedData['email'],
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'address' => $validatedData['adress'],
            'apartment' => $validatedData['apartament'],
            'state' => $validatedData['state'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'zip_code' => $validatedData['code'],
            'phone' => $validatedData['phone'],
            'promo_code' => $validatedData['promoCode'] ?? null,
            'discount_amount' => $validatedData['discountAmount'] ?? 0,
        ]);

        $order->save();

        parent::handle($order, $validatedData);
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        event(new OrderFailedEvent($validatedData['user_id'], $order, app(NotificationService::class), 'Eroare la crearea comenzii'));
        $order->update(['status' => OrderStatus::Canceled]);
        throw $e;
    }
    }

    private function calculateTotal($cartItems, $discountAmount = 0)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $product = Product::findOrFail($item['product']['id']);
            $total += $product->price * $item['quantity'];
        }
        $total += 5; //taxes
        $total += 10; //shipping fee

        // Aplicăm reducerea dacă există
        if ($discountAmount > 0) {
            $total -= ($total * $discountAmount) / 100;
        }

        return max(0, $total); // Ne asigurăm că totalul nu devine negativ
    }
}
