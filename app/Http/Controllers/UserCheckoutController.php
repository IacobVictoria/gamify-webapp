<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\CheckoutRequest;
use App\Jobs\ExpediteOrderJob;
use App\Models\ClientOrder;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use App\Services\DompdfGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class UserCheckoutController extends Controller
{
    protected $pdfGenerator;

    public function __construct(DompdfGeneratorService $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function index()
    {
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            $cookieCart = json_decode(request()->cookie('cart_' . auth()->id(), '[]'), true);
            if (!empty($cookieCart)) {
                session(['cart' => $cookieCart]);
                $cartItems = $cookieCart; //  cartItems =  produsele din cookie
            }
        }

        $cartItems = array_values($cartItems);

        return Inertia::render('User/Checkout', [
            "cartItems" => $cartItems,
        ]);
    }

    public function store(CheckoutRequest $request)
    {

        $user = Auth::user();

        $validatedData = $request->validated();

        $status = count($validatedData['cartItems']) > 0 ? OrderStatus::Pending : OrderStatus::Created;

        $order = ClientOrder::create([
            'id' => Uuid::uuid(),
            'user_id' => $user->id,
            'total_price' => $this->calculateTotal($validatedData['cartItems'], $validatedData['discountAmount'] ?? 0),
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
            'placed_at' => null,
            'promo_code' => $validatedData['promoCode'] ?? null, // Salvează codul promoțional
            'discount_amount' => $validatedData['discountAmount'] ?? 0,
        ]);

        if ($status === OrderStatus::Pending) {
            foreach ($validatedData['cartItems'] as $item) {
                $product = Product::findOrFail($item['product']['id']); // Verificăm produsul

                // Creăm o înregistrare în tabela pivot
                OrderProduct::create([
                    'id' => Uuid::uuid(),
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'], // cantitatea din coș
                    'price' => $product->price, // prețul produsului la momentul actual
                ]);
                $product->stock = $product->stock - $item['quantity'];
                $product->save();
            }
        }
        // Finalizăm comanda -> Trece din `Pending` în `Placed`
        $order->update([
            'status' => OrderStatus::Placed,
            'placed_at' => now(),
        ]);

        //redirect catre Plata Stripe
        return redirect()->route('stripe.index', ['order_id' => $order->id]);
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



    //ORDER INVOICE PDF

}
