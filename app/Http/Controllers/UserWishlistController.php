<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserWishlistController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $user = Auth::user();

        $productsLiked = $user->wishlists()->with('product')->get();
        return Inertia::render('User/WishLists/Wishlist', [
            'favorites' => $productsLiked
        ]);
    }

    public function like(string $productId)
    {
        $user = Auth()->user();

        $product = Product::find($productId);
        $this->userService->likeProduct($user, $product);
    }

    public function dislike(string $productId)
    {
        $user = Auth()->user();
        $product = Product::find($productId);

        $this->userService->dislikeProduct($user, $product);

    }
}
