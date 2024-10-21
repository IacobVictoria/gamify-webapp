<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Services\UserService;
use Illuminate\Http\Request;
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
        $searchQuery = $request->input('search', '');

        $products = Product::where('name', 'like', "%{$searchQuery}%")->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'searchQueryProp' => $searchQuery,
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
    public function show(Request $request, string $id)
    {
        $user = Auth()->user();

        $product = Product::find($id);
        $sortOrder = $request->input('order', "");
        $buyers = $request->input('buyers', "");

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
        $noStatistics=true;

        if (
            $buyers === 'true' && $reviewsQuery->whereHas('user.orders', function ($query) use ($id) {
                $query->where('product_id', $id);
            })->exists()){

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
            'product' => $product,
            'reviews' => $reviews,
            'noBuyersMessage' => $noBuyersMessage,
            'statistics' => $statistics,
            'averageRating' => $averageRating,
            'noStatistics' =>$noStatistics
        ]);
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
