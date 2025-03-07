<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Http\Requests\ReviewCommentRequest;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Services\Badges\CommenterBadgeService;
use App\Services\NotificationService;
use Faker\Provider\Uuid;

class ReviewCommentController extends Controller
{
    protected $badgeService, $notificationService;

    public function __construct(CommenterBadgeService $badgeService, NotificationService $notificationService)
    {
        $this->badgeService = $badgeService;
        $this->notificationService = $notificationService;
    }

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

        $this->badgeService->checkAndAssignBadges($user);

        broadcast(new CommentEvent($comment, $user, $reviewer, $this->notificationService));

        return redirect()->back()
            ->with('success', 'Comment Review created successfully!');

    }

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
