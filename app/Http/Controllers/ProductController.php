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
        $searchDropdownCategory = $request->input('category', '');

        $productsQuery = Product::where('name', 'like', "%{$searchQuery}%")
            ->where('category', 'like', "%{$searchDropdownCategory}%")
            ->where('is_published', true);

        $categories = Product::whereNotNull('category')->pluck('category')->unique();


        $products = $productsQuery->paginate(9)->through(function ($product) use ($user) {

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
                'slug' => $product->slug,
                'old_price' => !empty($discounts) ? $oldPrice : null,
                'isFavorite' => $this->userService->hasLikedProduct($user, $product),
                'discounts' => $discountValues,
                'image' => $product->image_url
            ];
        });


        return Inertia::render('Products/Index', [
            'products' => $products,
            'searchQueryProp' => $searchQuery,
            'categories' => $categories,
            'searchCategory' => $searchDropdownCategory,
        ]);
    }

    public function show($slug, Request $request)
    {
        $user = auth()->user();
        $product = $this->getProductBySlug($slug);
        $isFavorite = $this->userService->hasLikedProduct($user, $product);
        $comparisonChecked = $this->isInComparison($product->id);
        $finalPriceData = $this->calculateDiscounts($product);
        $isVerifiedBuyer = $this->userService->isVerifiedBuyer(Auth()->user(), $product->id);

        [$reviews, $statistics, $averageRating, $noStatistics] =
            $this->prepareReviewsData($product, $user, $request);

        return Inertia::render('Products/Show', [
            'product' => $this->formatProductData($product, $finalPriceData),
            'isFavorite' => $isFavorite,
            'reviews' => $reviews,
            'statistics' => $statistics,
            'averageRating' => $averageRating,
            'noStatistics' => $noStatistics,
            'isVerifiedBuyer' => $isVerifiedBuyer,
            'comparisonChecked' => $comparisonChecked,
        ]);
    }

    private function getProductBySlug(string $slug): Product
    {
        return Product::where('slug', $slug)->firstOrFail();
    }

    private function isInComparison($productId): bool
    {
        $comparison = session('comparison', []);
        return in_array($productId, $comparison);
    }

    private function calculateDiscounts(Product $product): array
    {
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

        return [
            'discounts' => $discountValues,
            'finalPrice' => $finalPrice,
            'oldPrice' => !empty($discounts) ? $oldPrice : null
        ];

    }

    private function prepareReviewsData(Product $product, $user, Request $request): array
    {
        $sortOrder = $request->input('order', '');

        $orderColumn = $sortOrder === 'populare' ? 'likes' : 'updated_at';
        $orderDirection = 'desc';

        $reviews = $product->reviews()->with(['user:id,name,gender', 'reviewMedia'])
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $totalReviews = $reviews->count();
        $statistics = $reviews->groupBy('rating')->mapWithKeys(fn($group, $rating) => [
            $rating => ($group->count() / $totalReviews) * 100
        ]);
        $averageRating = $totalReviews > 0 ? $reviews->sum('rating') / $totalReviews : 0;

        $reviews = $reviews->map(fn($review) => $this->formatReview($review, $user, $product->id));

        $noStatistics = true;


        return [$reviews, $statistics, $averageRating, $noStatistics];
    }

    private function formatReview($review, $user, $productId): array
    {
        $userReview = $review->user;
        $isVerified = $this->userService->isVerifiedBuyer($userReview, $productId);

        $comments = $review->reviewComments()
            ->with('user:id,name,gender')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn($comment) => [
                'id' => $comment->id,
                'description' => $comment->description,
                'likes' => $comment->likes,
                'isLiked' => $this->userService->hasLikedComment($user, $comment),
                'updated_at' => $comment->updated_at->format('Y-m-d'),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'gender' => $comment->user->gender,
                    'role' => $comment->user->roles->first(),
                ],
            ]);

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
            'isVerified' => $isVerified,
            'comments' => $comments,
            'commentsCount' => $comments->count(),
        ];
    }

    private function formatProductData(Product $product, array $pricing): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $pricing['finalPrice'],
            'old_price' => $pricing['oldPrice'],
            'discounts' => $pricing['discounts'],
            'category' => $product->category,
            'image_url' => $product->image_url,
            'description' => $product->description,
            'stock' => $product->stock,
            'calories' => $product->calories,
            'protein' => $product->protein,
            'carbs' => $product->carbs,
            'fats' => $product->fats,
            'fiber' => $product->fiber,
            'sugar' => $product->sugar,
            'ingredients' => explode(',', $product->ingredients),
            'allergens' => explode(',', $product->allergens),
        ];
    }

}