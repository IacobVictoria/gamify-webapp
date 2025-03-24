<?php

namespace App\Interfaces;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
interface UserServiceInterface
{
    public function likeReview(User $user, Review $review);
    public function unlikeReview(User $user, Review $review);
    public function isVerified(User $user, string $productId,string $reviewCreatedAt);
    public function hasLikedReview(User $user, Review $review);

    public function likeComment(User $user, ReviewComment $review);
    public function unlikeComment(User $user, ReviewComment $review);
    public function hasLikedComment(User $user, ReviewComment $review);

}