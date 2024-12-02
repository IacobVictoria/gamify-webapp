<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Events\MessageRead;
use App\Events\MessageUnreadUpdatedEvent;
use App\Events\UserStatusChangedEvent;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Hamcrest\Arrays\IsArray;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PHPUnit\Framework\Constraint\IsEmpty;

class UserChatController extends Controller
{
    protected $notificationService;

    public function __construct(
        NotificationService $notificationService
    ) {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $currentUser = Auth::user();

        $conversations = ChatMessage::where('sender_id', $currentUser->id)
            ->orWhere('receiver_id', $currentUser->id)
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($currentUser) {
                return $message->sender_id === $currentUser->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages) use ($currentUser) {
                $lastMessage = $messages->sortByDesc('sent_at')->first(); //mesajul cel mai recent
                $friend = $lastMessage->sender_id === Auth::id() ? $lastMessage->receiver : $lastMessage->sender;

                $unreadCount = $messages->where('receiver_id', $currentUser->id)
                    ->where('is_read', false) // Mesaje necitite
                    ->count();
                // broadcast(new UserStatusChangedEvent($currentUser, 'online', $friend));
    
                return [
                    'friend' => [
                        'id' => $friend->id,
                        'name' => $friend->name,
                    ],
                    'lastMessage' => $lastMessage,
                    'sent_at' => $lastMessage->sent_at,
                    'is_read' => $lastMessage->is_read,
                    'unreadCount' => $unreadCount,
                    'status' => ''
                ];
            })->values()
            ->toArray();

        return Inertia::render('ChatRoom/Chat', [
            'currentUser' => $currentUser,
            'conversations' => $conversations,
        ]);
    }

    public function markMessagesAsRead(string $friendId)
    {
        $currentUser = Auth::user();

        $updatedRows = ChatMessage::where('sender_id', $friendId)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        if ($updatedRows > 0) {
            broadcast(new MessageRead($friendId, $currentUser->id));
        }

        return response()->json(['status' => 'success']);
    }

    public function getConversation(Request $request, $friendId)
    {
        $currentUser = Auth::user();
        $friend = User::find($friendId);
        $offset = $request->query('offset', 0); // Offset-ul implicit este 0
        $limit = $request->query('limit', 10);

        //  intreaga conversație între utilizatorul curent și prietenul selectat
        $messages = ChatMessage::where(function ($query) use ($currentUser, $friend) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $friend->id);
        })
            ->orWhere(function ($query) use ($currentUser, $friend) {
                $query->where('sender_id', $friend->id)
                    ->where('receiver_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->offset($offset) // Offset-ul pentru paginare
            ->limit($limit)
            ->get()
            ->map(function ($message) {
                $message->sent_at_formatted = Carbon::parse($message->created_at)->format('H:i');
                return $message;
            });

        // facem toate mesajele ca fiind citite când accesăm conversația
        $updatedRows = ChatMessage::where('sender_id', $friendId)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $unreadMessages = ChatMessage::where('receiver_id', $friendId)
            ->where('is_read', 0)
            ->count();

        broadcast(new MessageUnreadUpdatedEvent($unreadMessages, $currentUser->id, $friend));
        if ($updatedRows > 0) {
            broadcast(new MessageRead($friendId, $currentUser->id));

        }

        return response()->json($messages);
    }

    public function sendMessage(string $friendId, Request $request)
    {
        $currentUser = Auth::user();
        $messageType = $request->input('message_type', 'text');
        $attachmentUrl = null;

        if ($messageType === 'file') {
            $request->validate([
                'file' => 'required|file|mimes:mp3,wav,webm|max:2048',
            ]);

            $file = $request->file('file');

            $filePath = $file->store('voice_messages', 's3');  // fisierul în folderul 'voice_messages'

            Storage::disk('s3')->setVisibility($filePath, 'public');

            $attachmentUrl = Storage::disk('s3')->url($filePath);

        }

        $message = ChatMessage::create([
            'id' => Uuid::uuid(),
            'sender_id' => $currentUser->id,
            'receiver_id' => $friendId,
            'content' => $messageType === 'file' ? '[Voice message]' : $request->input('message'),
            'reply_to_message_id' => $request->input('reply_to_message_id'),
            'sent_at' => now(),
            'message_type' => $messageType,
            'attachment_url' => $attachmentUrl,
            'is_read' => false,
        ]);

        broadcast(new ChatMessageSent($message));
        $this->notificationService->updateNotificationChat($currentUser, $friendId);
        // broadcast(new MessageUnreadUpdatedEvent($unreadMessages, $friendId,$currentUser));
        return response()->json($message, 200);
    }
    public function checkUserStatus($userId)
    {
        $cacheKey = 'user_activity_' . $userId;

        $status = Cache::get($cacheKey, 'Offline');

        return response()->json([
            'status' => $status
        ]);
    }

    public function searchFriendConversation(Request $request)
    {
        $currentUser = Auth::user();
        $email = $request->input('emailFriend');

        $friends = User::where('email', 'like', "%{$email}%")->get(['id', 'name', 'email']);

        if ($friends->isEmpty()) {
            return response()->json([], 200);
        }

        // grupăm toate mesajele între utilizatorul curent și prietenii găsiți
        $conversations = ChatMessage::where(function ($query) use ($currentUser, $friends) {
            $query->where('sender_id', $currentUser->id)
                ->whereIn('receiver_id', $friends->pluck('id'));
        })
            ->orWhere(function ($query) use ($currentUser, $friends) {
                $query->whereIn('sender_id', $friends->pluck('id'))
                    ->where('receiver_id', $currentUser->id);
            })
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($currentUser) {
                // grupăm mesajele pe baza ID-ului prietenului
                return $message->sender_id === $currentUser->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages) use ($currentUser) {
                $lastMessage = $messages->sortByDesc('created_at')->first();
                $friend = $lastMessage->sender_id === $currentUser->id ? $lastMessage->receiver : $lastMessage->sender;

                $unreadCount = $messages->where('receiver_id', $currentUser->id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'friend' => [
                        'id' => $friend->id,
                        'name' => $friend->name,
                    ],
                    'lastMessage' => $lastMessage,
                    'sent_at' => $lastMessage->sent_at,
                    'is_read' => $lastMessage->is_read,
                    'unreadCount' => $unreadCount,
                    'status' => Cache::get('user_activity_' . $friend->id, 'Offline'),
                ];
            })
            ->values()
            ->toArray();

        return response()->json($conversations);
    }

}
