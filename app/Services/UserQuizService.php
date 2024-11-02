<?php

namespace App\Services;
use App\Models\UserQuizResult;
use App\Models\UserQuizResponse;
use App\Models\UserQuizAnswer;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserQuizInterface;

class UserQuizService implements UserQuizInterface
{
    protected $badgeService;
    protected $userScoreService;

    public function __construct(BadgeService $badgeService, UserScoreService $userScoreService)
    {
        $this->badgeService = $badgeService;
        $this->userScoreService = $userScoreService;
    }

    public function retryQuiz(Request $request)
    {
        $user = Auth::user();

        $quizId = $request->input('quiz_id');
        $userId = $request->input('user_id');
        $score = $request->input('score');
        $responses = $request->input('responses');
        $percentage = $request->input('percentage');

        // Verifică dacă există deja un rezultat pentru acest quiz și user
        $quizResult = UserQuizResult::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->first();

        if (!$quizResult) {
            // Dacă nu există un rezultat anterior, creează unul nou cu attempt_number = 1
            $quizResult = new UserQuizResult([
                'id' => Uuid::uuid(),
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'attempt_number' => 1,
                'total_score' => $score,
                'is_locked' => false,
                'percentage_score' => $percentage
            ]);
        } else {
            // Dacă există deja un rezultat, incrementează attempt_number
            $quizResult->attempt_number += 1;
            $quizResult->total_score = $score;
            $quizResult->percentage_score = $percentage;
        }

        // Blochează quiz-ul dacă numărul de încercări depășește 3
        if ($quizResult->attempt_number >= 3) {
            $quizResult->is_locked = true;
        }

        $quizResult->save();

        // Salvează răspunsurile utilizatorului
        foreach ($responses as $response) {
            // Caută dacă există deja un răspuns
            $existingResponse = UserQuizResponse::where([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'question_id' => $response['questionId']
            ])->first();

            if ($existingResponse) {
                // Actualizează răspunsul existent
                $existingResponse->update([
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            } else {
                // Creează un nou răspuns
                UserQuizResponse::create([
                    'id' => Uuid::uuid(),
                    'user_id' => $userId,
                    'quiz_id' => $quizId,
                    'question_id' => $response['questionId'],
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            }
        }

        $this->badgeService->quizBadges($user);
    }

    public function lockQuiz(Request $request)
    {
        $user = Auth::user();

        $quizId = $request->input('quiz_id');
        $userId = $request->input('user_id');
        $score = $request->input('score');
        $responses = $request->input('responses');
        $percentage = $request->input('percentage');

        // Verifică dacă există deja un rezultat pentru acest quiz și user
        $quizResult = UserQuizResult::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->first();

        if (!$quizResult) {
            // Dacă nu există un rezultat anterior, creează unul nou cu attempt_number = 1
            $quizResult = new UserQuizResult([
                'id' => Uuid::uuid(),
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'attempt_number' => 1,
                'total_score' => $score,
                'is_locked' => true,
                'percentage_score' => $percentage
            ]);
        } else {
            // Dacă există deja un rezultat, setează attempt_number și blochează quiz-ul
            $quizResult->attempt_number += 1;
            $quizResult->total_score = $score;
            $quizResult->is_locked = true;
            $quizResult->percentage_score = $percentage;
        }
//dd($quizResult);
        $quizResult->save();
        // dd($quizResult->is_locked);

        // Salvează răspunsurile utilizatorului
        foreach ($responses as $response) {
            // Caută dacă există deja un răspuns
            $existingResponse = UserQuizResponse::where([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'question_id' => $response['questionId']
            ])->first();

            if ($existingResponse) {
                // Actualizează răspunsul existent
                $existingResponse->update([
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            } else {
                // Creează un nou răspuns
                UserQuizResponse::create([
                    'id' => Uuid::uuid(), // Generează un UUID nou
                    'user_id' => $userId,
                    'quiz_id' => $quizId,
                    'question_id' => $response['questionId'],
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            }
        }


        $this->badgeService->quizBadges($user);

        //UserScoreService -> multiplier score for quizzes
        $user = User::find($userId);
        $this->userScoreService->quizAttemptScore($user, $quizResult->attempt_number, $quizResult->total_score);

        // return redirect()->route('user.quizzes.index');
    }

}