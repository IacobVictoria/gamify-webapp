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
        }
    }

    public function updateNotifications(User $user)
    {
        $notificationsCount = $user->notifications()->where('is_read', false)->count();

        broadcast(new NotificationUpdatedEvent($notificationsCount, $user));
    }

}