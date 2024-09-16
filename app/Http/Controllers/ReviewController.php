<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Faker\Provider\Uuid;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $productId)
    {
        $product = Product::findOrFail($productId);
        $reviews = Review::where('product_id', $productId)->get();

        return Inertia::render('Products/Reviews', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $productId)
    {

        return Inertia::render(
            'Reviews/ReviewForm',
            [
                'productId' => $productId,
                'review' => null // Formular gol
            ]
        );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request, string $productId)
    {
        $validated = $request->validated();
        $review = new Review();
        $review->id = Uuid::uuid();
        $review->product_id = $productId;
        $review->user_id = auth()->id();
        $review->rating = $validated['rating'];
        $review->description = $validated['description'];
        $review->save();

        return redirect()->back()
        ->with('success', 'Review updated successfully!');
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
    public function edit(string $productId, string $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        return Inertia::render('Reviews/ReviewForm', [
            'productId' => $productId,
            'review' => $review //precompletare
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, string $productId, string $reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $validated = $request->validated();
        $review->description = $validated['description'];
        $review->rating = $validated['rating'];
        $review->save();

        return redirect()->back()
            ->with('success', 'Review updated successfully!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productId, string $reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        return redirect()->route('products.show', $productId)
            ->with('success', 'Review deleted successfully!');
    }
}
