<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewMedia;
use App\Services\Badges\ReviewerBadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Faker\Provider\Uuid;

class ReviewController extends Controller
{
    protected $badgeService;

    public function __construct(ReviewerBadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

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
        $review->title = $validated['title'];
        $review->rating = $validated['rating'];
        $review->description = $validated['description'];
        $review->save();


        if ($request->hasFile('media')) {

            $file = $request->file('media');

            $fileType = in_array($file->extension(), ['jpg', 'jpeg', 'png','webp']) ? 'image' : (in_array($file->extension(), ['mp4', 'mov', 'avi', 'mkv', 'wmv']) ? 'video' : 'unknown');
            $filePath = $file->store('public/media', 's3');

            Storage::disk('s3')->setVisibility($filePath, 'public');

            $fileUrl = Storage::disk('s3')->url($filePath);

            ReviewMedia::create([
                'id' => Uuid::uuid(),
                'review_id' => $review->id,
                'filename' => basename($filePath),
                'url' => $fileUrl,
                'type' => $fileType
            ]);


        }

        $user = Auth()->user();

        $this->badgeService->checkAndAssignBadges($user);

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
    public function update(Request $request, string $productId, string $reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $validated = $request->all();

        $review->title = $validated['title'];
        $review->description = $validated['description'];
        $review->rating = $validated['rating'];
        $review->save();

        if ($request->hasFile('media')) {

            $file = $request->file('media');

            $fileType = in_array($file->extension(), ['jpg', 'jpeg', 'png','webp']) ? 'image' : (in_array($file->extension(), ['mp4', 'mov', 'avi', 'mkv', 'wmv']) ? 'video' : 'unknown');
            $filePath = $file->store('public/media', 's3');

            Storage::disk('s3')->setVisibility($filePath, 'public');

            $fileUrl = Storage::disk('s3')->url($filePath);

            ReviewMedia::create([
                'id' => Uuid::uuid(),
                'review_id' => $review->id,
                'filename' => basename($filePath),
                'url' => $fileUrl,
                'type' => $fileType
            ]);


        }
        $user = Auth()->user();

        $this->badgeService->checkAndAssignBadges($user);

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

        return redirect()->back();
    }
}
