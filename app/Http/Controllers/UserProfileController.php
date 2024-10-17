<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
        $user = User::find($id);

        $medals = $user->medals()->get();
        $medals = $medals->map(function ($medal) {
            return [
                'tier' => $medal->tier,
                'created_at' => $medal->created_at->format('Y-m-d'),
            ];
        });

        $badges = $user->badges()->get();

        $badges = $badges->map(function ($badge) {

            return [
                'name' => $badge->name,
                'awarded_at' => Carbon::parse($badge->pivot->awarded_at)->format('Y-m-d'),
            ];
        });

        $user = [
            'medals' => $medals,
            'badges' => $badges,
            'name' => $user->name,
            'created_at' => $user->created_at->format('Y-m-d'),
        ];

        return Inertia::render('User/UserProfile', [
            'user' => $user,
        ]);
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
