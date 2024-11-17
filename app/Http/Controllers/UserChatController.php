<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Events\MessageRead;
use App\Models\ChatMessage;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserChatController extends Controller
{
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
            ->map(function ($messages) {
                $lastMessage = $messages->sortByDesc('sent_at')->first(); //mesajul cel mai recent
                $friend = $lastMessage->sender_id === Auth::id() ? $lastMessage->receiver : $lastMessage->sender;

                return [
                    'friend' => [
                        'id' => $friend->id,
                        'name' => $friend->name,
                    ],
                    'lastMessage' => $lastMessage,
                    'sent_at' => $lastMessage->sent_at,
                    'is_read' => $lastMessage->is_read
                ];
            });

        return Inertia::render('ChatRoom/Chat', [
            'currentUser' => $currentUser,
            'conversations' => $conversations,
        ]);
    }

    public function markMessagesAsRead(string $friendId)
    {
        $currentUser = Auth::user();
        $friend = User::find($friendId);

        $updatedRows = ChatMessage::where('sender_id', $friendId)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        if ($updatedRows > 0) {
            broadcast(new MessageRead($friendId, $currentUser->id));
        }

        return response()->json(['status' => 'success']);
    }

    public function getConversation( $friendId)
    {
        $currentUser = Auth::user();
        $friend = User::find($friendId);

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
            ->get();

        // facem toate mesajele ca fiind citite când accesăm conversația
        $updatedRows = ChatMessage::where('sender_id', $friendId)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
            
        if ($updatedRows > 0) {
            broadcast(new MessageRead($friendId, $currentUser->id));
        }

        return response()->json($messages);
    }



    public function sendMessage(string $friendId, Request $request)
    {
        $currentUser = Auth::user();

        $message = ChatMessage::create([
            'id' => Uuid::uuid(),
            'sender_id' => $currentUser->id,
            'receiver_id' => $friendId,
            'content' => $request->input('message'),
            'sent_at' => now(),
            'is_read' => false,
        ]);


        broadcast(new ChatMessageSent($message));

        return response()->json($message, 200);
    }
}
