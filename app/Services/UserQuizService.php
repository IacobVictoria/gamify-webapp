<?php

namespace App\Services;

use App\Interfaces\UserQuizInterface;
use App\Models\User;
use App\Models\UserQuizAnswer;
use App\Models\UserQuizResponse;
use App\Models\UserQuizResult;
use App\Services\Badges\QuizBadgeService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserQuizService implements UserQuizInterface
{
    protected QuizBadgeService $badgeService;
    protected UserScoreService $userScoreService;
    protected NotificationService $notificationService;

    public function __construct(
        QuizBadgeService $badgeService,
        UserScoreService $userScoreService,
        NotificationService $notificationService
    ) {
        $this->badgeService = $badgeService;
        $this->userScoreService = $userScoreService;
        $this->notificationService = $notificationService;
    }

    public function retryQuiz(Request $request)
    {
        $this->processQuiz($request, false);
    }

    public function lockQuiz(Request $request)
    {
        $this->processQuiz($request, true);
    }

    private function processQuiz(Request $request, bool $forceLock)
    {
        $user = Auth::user();

        $quizId = $request->input('quiz_id');
        $userId = $request->input('user_id');
        $score = $request->input('score');
        $responses = $request->input('responses');
        $percentage = $request->input('percentage');

        $quizResult = UserQuizResult::firstOrNew([
            'user_id' => $userId,
            'quiz_id' => $quizId,
        ], [
            'id' => Uuid::uuid(),
            'attempt_number' => 0,
        ]);

        $quizResult->attempt_number += 1;
        $quizResult->total_score = $score;
        $quizResult->percentage_score = $percentage;
        $quizResult->is_locked = $forceLock || $quizResult->attempt_number >= 3;

        $quizResult->save();

        foreach ($responses as $response) {
            $isCorrect = UserQuizAnswer::find($response['answerId'])->is_correct;

            $existing = UserQuizResponse::where([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'question_id' => $response['questionId'],
            ])->first();

            if ($existing) {
                $existing->update([
                    'answer_id' => $response['answerId'],
                    'is_correct' => $isCorrect,
                ]);
            } else {
                UserQuizResponse::create([
                    'id' => Uuid::uuid(),
                    'user_id' => $userId,
                    'quiz_id' => $quizId,
                    'question_id' => $response['questionId'],
                    'answer_id' => $response['answerId'],
                    'is_correct' => $isCorrect,
                ]);
            }

        }

        $this->badgeService->checkAndAssignBadges($user);

        if ($forceLock) {
            $user = User::find($userId);
            $this->userScoreService->quizAttemptScore($user, $quizResult->attempt_number, $quizResult->total_score);
        }
    }
}
