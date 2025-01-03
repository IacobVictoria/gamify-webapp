<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestSent;
use App\Models\Friend;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    protected $notificationService;

    function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function sendFriendRequest(Request $request)
    {
        $receiver_id = $request->input('receiver_id');
        $sender = Auth::user();
        broadcast(new FriendRequestSent($sender, $receiver_id, $this->notificationService));
        return response()->json(['message' => 'Friend request sent!']);
    }


    public function removeFriend(Request $request)
    {
        $userId1 = auth()->id();
        $userId2 = $request->input('friend_id');

        $user1 = min($userId1, $userId2);
        $user2 = max($userId1, $userId2);

        Friend::where('user_id', $user1)->where('friend_id', $user2)->delete();

        return response()->json(['message' => 'Friend removed!']);
    }

    public function searchUsers(Request $request)
    {
        $email = $request->input('email');
        $user = Auth::user();
        // id  friends
        $friendIds = Friend::where('user_id', $user->id)
            ->orWhere('friend_id', $user->id)
            ->get()
            ->map(function ($friend) use ($user) {
                // returnează ID-ul prietenului în funcție de dacă 'user_id' sau 'friend_id' este userul logat
                return $friend->user_id == $user->id ? $friend->friend_id : $friend->user_id;
            })
            ->unique()
            ->toArray();

        $users = User::where('email', 'like', "%{$email}%")
            ->where('id', '!=', auth()->id())
            ->whereNotIn('id', $friendIds)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function searchFriendsHangMan(Request $request)
    {
        $search = $request->input('email', '');

        if (empty($search)) {
            return response()->json([]);
        }

        $user = Auth::user();
        
        $friends = $user->allFriendsQuery()
            ->where(function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', '%' . $search . '%');
                })->orWhereHas('friend', function ($q) use ($search) {
                    $q->where('email', 'like', '%' . $search . '%');
                });
            })
            ->get()
            ->map(function ($friend) use ($user) {
                return $friend->user_id === $user->id ? $friend->friend : $friend->user;
            });

        return response()->json($friends);
    }



}
