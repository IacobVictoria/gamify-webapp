<?php

use App\Models\HangmanSession;
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

Broadcast::channel('leaderboard.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin-channel', function ($user) {
    return $user && $user->hasRole('admin');
});

Broadcast::channel('chat.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat_read.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});
Broadcast::channel('user_message.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat_status', function ($user) {

    return true;
});
Broadcast::channel('friend-requests.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin-channel.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user_newEvent.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});
Broadcast::channel('user_newDiscount.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});
Broadcast::channel('user_newProduct.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});
Broadcast::channel('user_restockProduct.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});
Broadcast::channel('user_reminder.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});

Broadcast::channel('hangman-session.{id}', function ($user, $id) {
    $session = HangmanSession::find($id);
    if (!$session) {
        return false; 
    }
    // Permite accesul doar dacă utilizatorul este creator sau oponent
    return $user->id === $session->creator_id || $user->id === $session->opponent_id;
});
