<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
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
                $lastMessage = $messages->last(); //mesajul cel mai recent
                $friend = $lastMessage->sender_id === Auth::id() ? $lastMessage->receiver : $lastMessage->sender;

                return [
                    'friend' => [
                        'id' => $friend->id,
                        'name' => $friend->name,
                    ],
                    'lastMessage' => $lastMessage,
                    'sent_at' => $lastMessage->sent_at,
                ];
            });

        return Inertia::render('ChatRoom/Chat', [
            'currentUser' => $currentUser,
            'conversations' => $conversations,
        ]);
    }

    public function getConversation(string $friendId)
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
        ]);


        broadcast(new ChatMessageSent($message));

        return response()->json($message, 200);
    }
}
