<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\CheckoutRequest;
use App\Jobs\ExpediteOrderJob;
use App\Models\ClientOrder;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Services\BadgeService;
use App\Services\DompdfGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class UserCheckoutController extends Controller
{
    protected $badgeService, $pdfGenerator;

    public function __construct(BadgeService $badgeService, DompdfGeneratorService $pdfGenerator)
    {
        $this->badgeService = $badgeService;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CheckoutRequest $request)
    {

        $user = Auth::user();

        $validatedData = $request->validated();

        $status = count($validatedData['cartItems']) > 0 ? OrderStatus::Pending : OrderStatus::Created;

        $order = ClientOrder::create([
            'id' => Uuid::uuid(),
            'user_id' => $user->id,
            'total_price' => $this->calculateTotal($validatedData['cartItems']),
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
        //este pending cat timp pun produse in ea 
        //aici sa fac comanda placed
        // Lansăm job-urile pentru expediere și livrare automată
        
        //FACTURA AMAZON 
        // $filename = "invoice_{$order->id}.pdf";
        // $pdfUrl = $this->pdfGenerator->generateClientInvoicePdf(['order' => $order], $filename);

        // // Salvează URL-ul facturii în baza de date
        // $order->update(['invoice_url' => $pdfUrl]);


        // ExpediteOrderJob::dispatch($order)->delay(now()->addMinutes(1));

        // $this->badgeService->shoopingBadges($user);

        //redirect catre Plata Stripe
        return redirect()->route('stripe.index', ['order_id' => $order->id]);
    }
    private function calculateTotal($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $product = Product::findOrFail($item['product']['id']); // Găsim produsul
            $total += $product->price * $item['quantity']; // Calculăm prețul total
        }
        $total += 5; //taxes
        $total += 10; //shipping fee
        return $total;
    }


    //ORDER INVOICE PDF


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
