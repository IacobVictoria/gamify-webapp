<?php

namespace App\Http\Controllers;

use App\Models\ReviewComment;
use App\Services\Badges\CommenterBadgeService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ReviewCommentLikeController extends Controller
{
    protected $userService;
    protected $badgeService;

    public function __construct(UserService $userService, CommenterBadgeService $badgeService)
    {
        $this->userService = $userService;
        $this->badgeService = $badgeService;
    }


    public function like(string $commentId): void
    {
        $user = Auth()->user();

        $comment = ReviewComment::find($commentId);
        $this->userService->likeComment($user, $comment);

        $comment->likes = $comment->commentLikes()->count();
        $comment->save();

        $this->badgeService->checkAndAssignBadges($user);
    }


    public function unlike(string $commentId): void
    {
        $user = Auth()->user();
        $comment = ReviewComment::find($commentId);

        $this->userService->unlikeComment($user, $comment);
        $comment->likes = $comment->commentLikes()->count();
        $comment->save();
    }
}
