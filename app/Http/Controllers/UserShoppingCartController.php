<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserShoppingCartController extends Controller
{

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


        return Inertia::render('User/ShoppingCart', [
            "cartItems" => $cartItems,
        ]);

    }

    public function addToCart(Request $request)
    {

        $product = $request->input('product'); // Get the product details
        $quantity = $request->input('quantity'); // Get the quantity

        // Ensure that product details and quantity are correctly set
        if (!$product || !$quantity) {
            return redirect()->back()->withErrors(['msg' => 'Product or quantity is missing.']);
        }

        // Prepare the order product data
        $order_product = [
            'product' => $product, // All attributes from product
            'quantity' => $quantity, // Use 'quantity' instead of 'cantity'
        ];

        // Retrieve the existing cart from the session
        $cart = session()->get('cart', []);

        // Check if the product is already in the cart
        if (isset($cart[$product['id']])) {
            // If the product is already in the cart, update the quantity
            $cart[$product['id']]['quantity'] += $quantity;
        } else {
            // If it's a new product, add it to the cart
            $cart[$product['id']] = $order_product;
        }

        // Update the cart in the session
        session()->put('cart', $cart);

        // Redirect to the shopping cart index route
        return redirect()->route('user.shopping-cart.index')
            ->withCookie(cookie('cart_' . auth()->id(), json_encode($cart), 60 * 24 * 30));
    }

    public function update(Request $request, string $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->input('quantity');
            session(['cart' => $cart]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $productId)
    {
        //  coșul de cumpărături din sesiune

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            // Îndepărtează produsul din coș
            unset($cart[$productId]);
        }

        session()->put('cart', $cart); // save cart in session

        return redirect()->route('user.shopping-cart.index')
            ->withCookie(cookie('cart_' . auth()->id(), json_encode($cart), 60 * 24 * 30)) // Salvează coșul în cookie
            ->with('success', 'Product deleted from cart successfully.');
    }

    public function topProducts()
    {
        $user = Auth()->user();

        $products = $user->orders()->with('products')->get()
            ->pluck('products')
            ->flatten();

        $topProducts = $products->groupBy('id')->map(function ($group) {
            return [
                'slug' => $group->first()->slug,
                'name' => $group->first()->name,
                'total_quantity' => $group->sum(fn($product) => $product->pivot->quantity),
            ];
        })->take(5)->sortByDesc('total_quantity')->values();

        return $topProducts;
    }

}



