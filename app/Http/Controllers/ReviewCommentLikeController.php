<?php

namespace App\Http\Controllers;

use App\Models\ReviewComment;
use App\Services\BadgeService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ReviewCommentLikeController extends Controller
{
    protected $userService;
    protected $badgeService;

    public function __construct(UserService $userService, BadgeService $badgeService)
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

        $this->badgeService->awardTrustedCommenterBadge($user);
    }


    public function unlike(string $commentId): void
    {
        $user = Auth()->user();
        $comment = ReviewComment::find($commentId);

        $this->userService->unlikeComment($user, $comment);
        $comment->likes = $comment->commentLikes()->count();
        $comment->save();
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
    public function store(Request $request)
    {
        //
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
