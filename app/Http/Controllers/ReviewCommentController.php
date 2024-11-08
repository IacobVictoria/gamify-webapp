<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Http\Requests\ReviewCommentRequest;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Services\BadgeService;
use App\Services\NotificationService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewCommentController extends Controller
{
    protected $badgeService, $notificationService;

    public function __construct(BadgeService $badgeService, NotificationService $notificationService)
    {
        $this->badgeService = $badgeService;
        $this->notificationService = $notificationService;
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

        $user = Auth()->user();
        $reviewer = Review::find($reviewId)->user;

        $validatedData = $request->validated();

        $comment = new ReviewComment();
        $comment->id = Uuid::uuid();
        $comment->review_id = $reviewId;
        $comment->user_id = $user->id;
        $comment->description = $validatedData['description'];
        $comment->save();

        $this->badgeService->awardActiveCommenterBadge($user);

        broadcast(new CommentEvent($comment, $user, $reviewer, $this->notificationService));

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
    public function destroy(string $commentId)
    {
        $comment = ReviewComment::findOrFail($commentId);

        $this->notificationService->removeCommentNotification($comment);
        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comment deleted successfully!');
    }
}
