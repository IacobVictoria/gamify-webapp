<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('users.{id}', function (User $user, $id) {
//     return (int) $user->id === (int) $id;
// });
Broadcast::channel('comments.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('obtain_badge.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

