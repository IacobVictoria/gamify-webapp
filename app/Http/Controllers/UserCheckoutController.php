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
use App\Services\OrderHandlers\OrderHandlerInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

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

        return Inertia::render('User/Checkout', [
            "cartItems" => $cartItems,
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
