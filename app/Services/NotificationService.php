<?php

namespace App\Services;

use App\Events\NotificationUpdatedEvent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class NotificationService
{
    public function removeLikeNotification($review)
    {
        $notification = Notification::where('user_id', $review->user->id)
            ->where('type', 'ReviewLiked')
            ->whereJsonContains('data->review_id', $review->id)
            ->first();

        if ($notification) {
            $notification->delete();
            $this->updateNotifications($review->user); // nr notif necitite
        }

    }

    public function removeCommentNotification($comment)
    {
        $reviewOwner = $comment->review->user;
        $notification = Notification::where('user_id', $reviewOwner->id)
            ->where('type', 'CommentEvent')
            ->whereJsonContains('data->comment_id', $comment->id)
            ->first();

        if ($notification) {
            $notification->delete();
            // actualizează numărul de notificări necitite
            $this->updateNotifications($reviewOwner);
        }

    }

    public function updateNotifications(User $user)
    {
        $notificationsCount = $user->notifications()->where('is_read', false)->count();

        broadcast(new NotificationUpdatedEvent($notificationsCount, $user));
    }

}