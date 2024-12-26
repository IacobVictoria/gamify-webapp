<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\DiscordService;
use Inertia\Inertia;


class FeedbackController extends Controller
{
    protected $discordService;

    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    public function index()
    {
        return Inertia::render('Feedback');
    }
    public function sendFeedback(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string|max:500',
        ]);

        $message = "New feedback from {$request->user()->name}: {$request->feedback}";
        $this->discordService->sendMessage($message);

        return response()->json(['message' => 'Feedback sent to Discord!']);
    }
}
