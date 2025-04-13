<?php

namespace App\Http\Controllers;

use App\Events\UserRemarkedOnQuizEvent;
use App\Models\UserQuiz;
use App\Models\UserQuizRemark;
use App\Services\NotificationService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserQuizRemarkController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request, $quizId)
    {
        $validatedData = $request->validate([
            'feedback' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $userQuizRemark = UserQuizRemark::create([
            'id' => Uuid::uuid(),
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'description' => $validatedData['feedback']
        ]);
        $quiz = UserQuiz::find($quizId);
        broadcast(new UserRemarkedOnQuizEvent($user, $quiz, $userQuizRemark, $this->notificationService));
        return redirect()->route('user.quizzes.index');
    }
}
