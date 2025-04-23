<?php

namespace App\Http\Controllers;

use App\Models\RecommendedProduct;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserRecommandationController extends Controller
{
    public function index(){
        $userId = Auth::id();

        $recommendations = RecommendedProduct::where('user_id', $userId)
            ->with('product')
            ->orderByDesc('score')
            ->get()
            ->map(function ($rec) {
                return [
                    'id' => $rec->product->id,
                    'name' => $rec->product->name,
                    'price' => $rec->product->price,
                    'image_url' => $rec->product->image_url,
                    'slug' => $rec->product->slug,
                ];
            });

        return Inertia::render('User/Recommendations', [
            'recommendations' => $recommendations,
        ]);
    }
}
