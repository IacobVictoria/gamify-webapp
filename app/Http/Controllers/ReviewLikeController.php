<?php

namespace App\Http\Controllers;

use App\Events\ReviewLikedEvent;
use App\Models\Review;
use App\Services\Badges\ReviewerBadgeService;
use App\Services\NotificationService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ReviewLikeController extends Controller
{
    protected $badgeService, $userService, $notificationService;

    public function __construct(ReviewerBadgeService $badgeService, UserService $userService, NotificationService $notificationService)
    {
        $this->badgeService = $badgeService;
        $this->userService = $userService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {

    }

    public function like(string $reviewId)
    {
        $user = Auth()->user();

        $review = Review::find($reviewId);
        $this->userService->likeReview($user, $review);

        // sa fac un update pe noul nr de like uri 
        $review->likes = $review->reviewLikes()->count();
        $review->save();

        broadcast(new ReviewLikedEvent($user, $review, $this->notificationService));

        $this->badgeService->checkAndAssignBadges($user);
    }

    public function unlike(string $reviewId)
    {
        $user = Auth()->user();
        $review = Review::find($reviewId);

        $this->userService->unlikeReview($user, $review);
        $review->likes = $review->reviewLikes()->count();
        $review->save();

        //delete the notification of liked review
        $this->notificationService->removeLikeNotification($review);
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
