<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $searchQuery = $request->input('search', '');

        $products = Product::where('name', 'like', "%{$searchQuery}%")->get();

        $products = $products->map(function ($product) use ($user) {

            $discounts = Cache::get("discount_product_{$product->id}", []);

            // Extragem doar reducerile din array-ul asociativ
            $discountValues = array_map(fn($discount) => $discount['discount'], $discounts);

            // Dacă nu există `old_price`, îl setăm la prețul original (prima dată când se aplică reduceri)
            $oldPrice = $product->old_price ?? $product->price;

            // Aplicăm reducerile cumulate
            $totalDiscount = array_reduce($discountValues, function ($carry, $discount) {
                return $carry * (1 - $discount / 100);
            }, 1);

            // Dacă nu există reduceri, `finalPrice` rămâne `price`
            $finalPrice = !empty($discountValues) ? round($oldPrice * $totalDiscount, 2) : $product->price;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $finalPrice,
                'old_price' => !empty($discounts) ? $oldPrice : null,
                'isFavorite' => $this->userService->hasLikedProduct($user, $product),
                'discounts' => $discountValues
            ];
        });

        return Inertia::render('Products/Index', [
            'products' => $products,
            'searchQueryProp' => $searchQuery,
        ]);
    }

    public function show(Request $request, string $id)
    {
        $user = Auth()->user();

        $product = Product::find($id);
        $isFavorite = $this->userService->hasLikedProduct($user, $product);
        $sortOrder = $request->input('order', "");
        $buyers = $request->input('buyers', "");
        $comparison = session('comparison', []);
        $comparisonChecked = in_array($id, $comparison);

        $discounts = Cache::get("discount_product_{$product->id}", []);

        // Extragem doar reducerile din array-ul asociativ
        $discountValues = array_map(fn($discount) => $discount['discount'], $discounts);

        // Dacă `old_price` este null, îl setăm la `price`
        $oldPrice = $product->old_price ?? $product->price;

        // Aplicăm reducerile cumulate
        $totalDiscount = array_reduce($discountValues, function ($carry, $discount) {
            return $carry * (1 - $discount / 100);
        }, 1);

        // Dacă există reduceri, aplicăm discount-ul la preț
        $finalPrice = !empty($discountValues) ? round($oldPrice * $totalDiscount, 2) : $product->price;
        
        //Sorting and filter 
        $orderColumn = 'updated_at';
        $orderDirection = 'desc';

        if ($sortOrder === 'populare') {
            $orderColumn = 'likes';
            $orderDirection = 'desc';
        }

        $reviewsQuery = $product->reviews()->with(['user:id,name,gender', 'reviewMedia']);

        if ($buyers === 'true') {
            $reviewsQuery->whereHas('user.orders', function ($query) use ($id) {
                $query->where('product_id', $id);
            });
        }
        $noBuyersMessage = '';
        $noStatistics = true;

        if (
            $buyers === 'true' && $reviewsQuery->whereHas('user.orders', function ($query) use ($id) {
                $query->where('product_id', $id);
            })->exists()
        ) {

            $noBuyersMessage = 'Nu există cumpărători care să fi lăsat o recenzie pentru acest produs.';
            $noStatistics = false;
        }


        $reviews = $reviewsQuery->orderBy($orderColumn, $orderDirection)->get();

        //Statistics

        $totalReviews = $reviews->count();

        $statistics = $reviews->groupBy('rating')->mapWithKeys(function ($group, $rating) use ($totalReviews) {
            $percentage = ($group->count() / $totalReviews) * 100;
            return [$rating => $percentage];
        });

        $averageRating = 0;

        if ($totalReviews > 0) {
            $averageRating = $reviews->sum('rating') / $totalReviews;
        }

        $reviews = $reviews->map(function ($review) use ($user, $id) {
            $userReview = $review->user;
            $isVerfied = $this->userService->isVerified($userReview, $id);
            $comments = $review->reviewComments()->with('user:id,name,gender')->orderBy('updated_at', 'desc')->get()->map(function ($comment) use ($user) {
                return [
                    'id' => $comment->id,
                    'description' => $comment->description,
                    'likes' => $comment->likes,
                    'isLiked' => $this->userService->hasLikedComment($user, $comment),
                    'updated_at' => $comment->updated_at->format('Y-m-d'), // formatul Y-m-d pentru updated_at
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'gender' => $comment->user->gender,
                        'role' => $comment->user->roles->first(),
                    ]
                ];
            });
            return [
                'id' => $review->id,
                'title' => $review->title,
                'description' => $review->description,
                'rating' => $review->rating,
                'likes' => $review->likes,
                'updated_at' => $review->updated_at->format('Y-m-d'),
                'user' => $review->user,
                'reviewMedia' => $review->reviewMedia,
                'isLiked' => $this->userService->hasLikedReview($user, $review),
                'isVerified' => $isVerfied,
                'comments' => $comments,
                'commentsCount' => $comments->count()
            ];
        });

        return Inertia::render('Products/Show', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $finalPrice,
                'old_price' => !empty($discounts) ? $oldPrice : null,
                'discounts' => $discountValues,
                'category' => $product->category
            ],
            'isFavorite' => $isFavorite,
            'reviews' => $reviews,
            'noBuyersMessage' => $noBuyersMessage,
            'statistics' => $statistics,
            'averageRating' => $averageRating,
            'noStatistics' => $noStatistics,
            'comparisonChecked' => $comparisonChecked
        ]);
    }
}