<?php

namespace App\Services;
use App\Interfaces\BadgeServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Badge;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use App\Models\UserBadge;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Auth;

class UserService implements UserServiceInterface
{

    public function likeReview(?User $user, Review $review)
    {
        if (!$user) {
            return;
        }

        if (!$this->hasLikedReview($user, $review)) {
            $user->reviewLikes()->create([
                'id' => Uuid::uuid(),
                'review_id' => $review->id,
            ]);
        }
    }

    public function unlikeReview(?User $user, Review $review)
    {
        if (!$user) {
            return;
        }

        if ($this->hasLikedReview($user, $review)) {
            $user->reviewLikes()->where('review_id', $review->id)->delete();
        }
    }

    public function isVerified(?User $user, string $productId)
    {
        if (!$user) {
            return;
        }

        foreach ($user->orders as $order) {
            if ($order->products->contains('id', $productId)) {
                return true;
            }
        }
        return false;
    }

    public function hasLikedReview(?User $user, Review $review)
    {
        if (!$user) {
            return;
        }
        return $user->reviewLikes()->where('review_id', $review->id)->exists();
    }
    public function likeComment(?User $user, ReviewComment $comment): void
    {
        if (!$user) {
            return;
        }

        if (!$this->hasLikedComment($user, $comment)) {
            $user->commentLikes()->create([
                'id' => Uuid::uuid(),
                'review_comment_id' => $comment->id,
            ]);
        }


    }
    public function unlikeComment(?User $user, ReviewComment $comment)
    {
        if (!$user) {
            return;
        }

        if ($this->hasLikedComment($user, $comment)) {
            $user->commentLikes()->where('review_comment_id', $comment->id)->delete();
        }
    }
    public function hasLikedComment(?User $user, ReviewComment $comment)
    {
        if (!$user) {
            return;
        }

        return $user->commentLikes()->where('review_comment_id', $comment->id)->exists();
    }
}