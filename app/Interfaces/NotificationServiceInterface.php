<?php

namespace App\Interfaces;

use App\Models\User;
interface NotificationServiceInterface
{
    public function removeLikeNotification($review): void;
    public function removeCommentNotification($comment): void;
    public function updateNotifications(User $user): void;
    public function updateNotificationChat(?User $user, int $friendId): void;
}