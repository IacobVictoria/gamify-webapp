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
    public function show(string $id)
    {
        $user = Auth()->user();

        $product = Product::find($id);
        $reviews = $product->reviews()->with('user:id,name,gender')->orderBy('updated_at', 'desc')->get();

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
                'isLiked' => $this->userService->hasLikedReview($user, $review),
                'isVerified' => $isVerfied,
                'comments' => $comments,
                'commentsCount' => $comments->count()
            ];
        });

        return Inertia::render('Products/Show', [
            'product' => $product,
            'reviews' => $reviews
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
