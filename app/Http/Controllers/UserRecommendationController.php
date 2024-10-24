<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserRecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $orderedProducts = $user->orders()->with('products')->get()->flatmap(function ($order) {
            return $order->products;
        });
        //Recomandări pe baza categoriilor cele mai folosite in comenzi
        $topCategories = $orderedProducts
            ->groupBy('category')->sortByDesc(function ($productsInCategory) {
                return $productsInCategory->count();
            })->take(2)
            ->keys();

        $recommendedProductsByCategories = Product::whereIn('category', $topCategories)->take(5)->get();

        //Produse similare din punct de vedere calorii si ingrediente cu cel mai comandat produs

        $mostOrderedProductId = $orderedProducts->groupBy('id')->sortByDesc(function ($product) {
            return $product->count();
        })->keys()->first();

        $mostOrderedProduct = Product::find($mostOrderedProductId);

        $caloriesTolerance = 50;

        $similarProductsByCaloriesAndIngredients = Product::whereBetween('calories', [$mostOrderedProduct->calories - $caloriesTolerance, $mostOrderedProduct + $caloriesTolerance])
            ->where('id', '!=', $mostOrderedProduct->id); // exclud produsul
        $ingredients = explode(',', $mostOrderedProduct->ingredients);

        foreach ($ingredients as $ingredient) {
            $similarProductsByCaloriesAndIngredients->orWhere('ingredients', 'like', '%' . trim($ingredient) . '%');
        }

        $similarProductsByCaloriesAndIngredients = $similarProductsByCaloriesAndIngredients->take(3)->get();

        //Produse cu mai multe proteine in price range ul userului

        $userPriceRange = $orderedProducts->pluck('price')->average();
        $priceLowerBound = $userPriceRange * 0.8; // 20% below average price
        $priceUpperBound = $userPriceRange * 1.2;

        $highProteinProducts = Product::orderBy('protein', 'desc')->take(5)->get();

        $highProteinProductsInPriceRange = $highProteinProducts->whereIn('price', [$priceLowerBound, $priceUpperBound]);

        //Produse fără alergeni 

        $allergenFreeProducts = Product::where('allergens', null)->take(3)->get();

        //Recomandări pe baza prețurilor preferate

        $recommendedProductsByPrice = Product::where('price', '<=', $userPriceRange)->take(5)->get();

        //Low Carb Options Based on Your Purchases

        $lowCarbThreshold = 20;

        $lowCarbProducts = Product::where('carbs', '<=', $lowCarbThreshold)
            ->whereIn('category', $topCategories)
            ->whereIn('price', [$priceLowerBound, $priceUpperBound])
            ->get();

        // Popular Among People with Similar Preferences

        $similarUsers = User::whereHas('orders.products', function ($query) use ($orderedProducts) {
            $query->whereIn('category', $orderedProducts->pluck('category'));
        })->where('id', '!=', $user->id)
            ->get();

        $popularProductsForSimilarUsers = Product::whereIn('id', function ($query) use ($similarUsers) {
            $query->select('product_id')
                ->from('order_product') // pivot table for orders and products
                ->whereIn('user_id', $similarUsers->pluck('id'))
                ->groupBy('product_id')
                ->limit(5);
        })->get();

        return Inertia::render('User/Recommendation', [
            'recommendedProductsByCategories' => $recommendedProductsByCategories,
            'similarProductsByCaloriesAndIngredients ' => $similarProductsByCaloriesAndIngredients,
            'highProteinProductsInPriceRange' => $highProteinProductsInPriceRange,
            'allergenFreeProducts' => $allergenFreeProducts,
            'recommendedProductsByPrice' => $recommendedProductsByPrice,
            'lowCarbProducts' => $lowCarbProducts,
            'popularProductsForSimilarUsers' => $popularProductsForSimilarUsers

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
    public function store(Request $request)
    {
        //
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
