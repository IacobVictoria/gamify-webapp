<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\BadgeService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ReviewLikeController extends Controller
{
    protected $badgeService;
    protected $userService;

    public function __construct(BadgeService $badgeService, UserService $userService)
    {
        $this->badgeService = $badgeService;
        $this->userService = $userService;
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

        $this->badgeService->reviewerBadges($user);
    }

    public function unlike(string $reviewId)
    {
        $user = Auth()->user();
        $review = Review::find($reviewId);

        $this->userService->unlikeReview($user, $review);
        $review->likes = $review->reviewLikes()->count();
        $review->save();
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