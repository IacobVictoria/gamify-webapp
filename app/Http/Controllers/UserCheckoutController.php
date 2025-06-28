<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\ClientOrder;
use App\Services\OrderHandlers\OrderHandlerInterface;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserCheckoutController extends Controller
{

    protected $orderHandler;

    public function __construct(OrderHandlerInterface $orderHandler)
    {
        $this->orderHandler = $orderHandler;
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

        //coduri promotionale nefolosite

        $user = auth()->user();
        $usedDiscounts = collect(json_decode($user->used_discounts, true) ?? [])
            ->filter(fn($d) => isset($d['used']) && $d['used'] === false)
            ->map(function ($d, $level) {
                return [
                    'label' => ucfirst($level) . ' - ' . $d['discount'] . '%',
                    'code' => $d['code'],
                    'discount' => $d['discount'],
                ];
            })
            ->values();

        return Inertia::render('User/Checkout', [
            "cartItems" => $cartItems,
            'availablePromoCodes' => $usedDiscounts,
        ]);
    }

    public function store(CheckoutRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        // Inițiem comanda (dar nu salvăm încă în DB)
        $order = new ClientOrder();
        $validatedData['user_id'] = $user->id;

        // Rulăm Chain of Responsibility pentru creare și procesare comandă
        $this->orderHandler->handle($order, $validatedData);

        //redirect catre Plata Stripe
        return redirect()->route('stripe.index', ['order_id' => $order->id]);
    }

}
