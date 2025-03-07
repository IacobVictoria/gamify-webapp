<?php

namespace App\Services;

use App\Events\MessageUnreadUpdatedEvent;
use App\Events\NotificationUpdatedEvent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class NotificationService 
{
    public function removeLikeNotification($review):void
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

    public function removeCommentNotification($comment):void
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

    public function updateNotifications(User $user): void
    {
        $notificationsCount = $user->notifications()->where('is_read', false)->count();

        broadcast(new NotificationUpdatedEvent($notificationsCount, $user));
    }

    public function updateNotificationChat(?User $user, $friendId): void
    {
        $userCurrent = User::find($friendId);
        $unreadMessages = $userCurrent->chatMessagesReceived()->where('is_read', false)->count();
        broadcast(new MessageUnreadUpdatedEvent($unreadMessages, $friendId, $user));
    }
}