<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('comments.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('obtain_badge.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('review_liked.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notifications.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('leaderboard.{userId}', function ($user, $userId) {
    // leaderboard-ul din cache
    $leaderboard = Cache::get('weekly_leaderboard', []);

    // dacă utilizatorul există în leaderboard și are rolul 'user'
    return in_array($userId, array_column($leaderboard, 'user_id')) && $user->id == $userId;
});
