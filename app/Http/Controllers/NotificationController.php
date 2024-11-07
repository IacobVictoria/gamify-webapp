<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
