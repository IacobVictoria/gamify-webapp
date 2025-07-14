<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RecommendedProduct;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class UserRecommandationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $recommendations = RecommendedProduct::where('user_id', $userId)
            ->with('product')
            ->orderByDesc('score')
            ->get();

        if ($recommendations->isEmpty()) {
            // total_units_sold pentru fiecare produs din comenzi
            $topProducts = DB::table('order_products')
                ->select('product_id', DB::raw('SUM(quantity) as total_units_sold'))
                ->groupBy('product_id')
                ->orderByDesc('total_units_sold')
                ->limit(6)
                ->get();

      
            $productIds = $topProducts->pluck('product_id')->toArray();

            $products = Product::whereIn('id', $productIds)->get();

            $recommendations = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => $product->image_url,
                    'slug' => $product->slug,
                ];
            });
        } else {
            $recommendations = $recommendations->map(function ($rec) {
                return [
                    'id' => $rec->product->id,
                    'name' => $rec->product->name,
                    'price' => $rec->product->price,
                    'old_price'=>$rec->product->old_price,
                    'image_url' => $rec->product->image_url,
                    'slug' => $rec->product->slug,
                ];
            })->take(6);
        }

        return Inertia::render('User/Recommendations', [
            'recommendations' => $recommendations,
        ]);
    }

}
