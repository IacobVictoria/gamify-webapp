<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
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
    public function showPublic($public_token)
    {
        $user = User::where('public_token', $public_token)->firstOrFail();
        $wishlist = $user->wishlists()->with('product')->get();

        $currentUserWishlistProductIds = auth()->user()
            ->wishlists()
            ->pluck('product_id')
            ->toArray();
            
        $wishlist = $wishlist->map(function ($item) use ($currentUserWishlistProductIds) {
            $item->alreadyInMyWishlist = in_array($item->product_id, $currentUserWishlistProductIds);
            return $item;
        });

        return inertia('User/WishLists/PublicWishlist', [
            'friend' => $user->only(['name', 'id']),
            'wishlist' => $wishlist,
        ]);
    }

}
