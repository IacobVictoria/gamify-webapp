<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestAccepted;
use App\Events\NotificationUpdatedEvent;
use App\Models\ChatMessage;
use App\Models\Friend;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    protected $notificationService;

    function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getNotifications()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();
        $unreadNotifications = $user->notifications()->where('is_read', false)->count();

        // json pentru axios
        return response()->json([
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    public function markNotificationsAsRead()
    {
        $user = Auth::user();

        $unreadNotificationsCount = $user->notifications()->where('is_read', false)->count();

        if ($unreadNotificationsCount > 0) {
            $user->notifications()->where('is_read', false)->update(['is_read' => true]);

            return response()->json(['message' => 'Notificările au fost marcate ca citite']);
        } else {

            return response()->json(['message' => 'Nu sunt notificări necitite'], 400);
        }
    }

    public function handleRequestNotification(Request $request, $notifId)
    {
        $action = $request->input('action');
        $notification = Notification::findOrFail($notifId);

        if ($notification->type === 'FriendRequest') {
            $notification->handled = true;
            $notification->save();
            $data = json_decode($notification->data, true);
            if ($action === 'accept') {
                Friend::create([
                    'id' => Uuid::uuid(),
                    'user_id' => auth()->id(),
                    'friend_id' => $data['sender_id'],
                ]);
                // cel care a trimis si friend request ul sa trimita si mesajul
                ChatMessage::create([
                    'id' => Uuid::uuid(),
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => auth()->id(),
                    'content' => "Bună! Hai să vorbim!",
                    'is_read' => false,
                    'sent_at' => now(),
                    'message_type' => 'text',
                    'attachment_url' => null,
                    'reply_to_message_id' => null,
                ]);
                $sender = User::find($data['sender_id']);
                broadcast(new FriendRequestAccepted($sender, Auth::user(), $this->notificationService));
            }
            return response()->json(['message' => 'Notification handled successfully']);
        }
        return response()->json(['message' => 'Invalid notification type'], 400);

    }

    public function destroy(string $notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->delete();
    }
}
