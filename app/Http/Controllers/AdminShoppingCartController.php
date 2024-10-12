<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminShoppingCartRequest;
use App\Http\Requests\ShoppingCartRequest;
use App\Models\Product;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminShoppingCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            $cookieCart = json_decode(request()->cookie('cart_' . auth()->id(), '[]'), true);
            if (!empty($cookieCart)) {
                session(['cart' => $cookieCart]);
                $cartItems = $cookieCart;
            }
        }
        $cartItems = array_values($cartItems);


        return Inertia::render('Admin/Purchase/ShoppingCart', [
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
    public function store(AdminShoppingCartRequest $request)
    {
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
        $product = SupplierProduct::with('supplier')->find($productId);

        if (!$product || !$quantity) {
            return redirect()->back()->withErrors(['msg' => 'Product or quantity is missing.']);
        }

        $order_product = [
            'product' => $product,
            'quantity' => $quantity,
        ];

        $cart = session()->get('cart', []);

        if (isset($cart[$product['id']])) {

            $cart[$product['id']]['quantity'] += $quantity;
        } else {

            $cart[$product['id']] = $order_product;
        }

        session()->put('cart', $cart);

        return redirect()->route('admin.shopping-cart.index')
            ->withCookie(cookie('cart_' . auth()->id(), json_encode($cart), 60 * 24 * 30));

    }

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
    public function update(Request $request, string $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->input('quantity');
            session(['cart' => $cart]);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);

        return redirect()->route('admin.shopping-cart.index')
            ->withCookie(cookie('cart_' . auth()->id()), json_encode($cart), 60 * 24 * 30)
            ->with('success', 'Product deleted from cart successfully.');
    }
}
