<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Http\Requests\ReviewCommentRequest;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Services\BadgeService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class ReviewCommentController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }
    public function index()
    {
        //
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
    public function store(ReviewCommentRequest $request, string $reviewId)
    {
        $review = Review::find($reviewId);
        $user = Auth()->user();

        $validatedData = $request->validated();

        $comment = new ReviewComment();
        $comment->id = Uuid::uuid();
        $comment->review_id = $reviewId;
        $comment->user_id = $user->id;
        $comment->description = $validatedData['description'];
        $comment->save();

        $this->badgeService->awardActiveCommenterBadge($user);

        broadcast(new CommentEvent($comment,$user));

        return redirect()->back()
            ->with('success', 'Comment Review created successfully!');

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewCommentRequest $request, string $commentId)
    {
        $comment = ReviewComment::find($commentId);
        $validatedData = $request->validated();
        $comment->description = $validatedData['description'];
        $comment->save();

        return redirect()->back()
            ->with('success', 'Comment Review updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $commentId)
    {
        $review = ReviewComment::findOrFail($commentId);
        $review->delete();

        return redirect()->back()
            ->with('success', 'Review deleted successfully!');
    }
}
