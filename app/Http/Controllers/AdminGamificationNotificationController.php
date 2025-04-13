<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AdminGamificationNotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadNotifications = $notifications->where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications
        ]);
    }
    public function markAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
