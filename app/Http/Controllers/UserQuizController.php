<?php

namespace App\Http\Controllers;

use App\Events\UserMadeLeaderboardEvent;
use App\Models\User;
use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use App\Models\UserQuizResponse;
use App\Models\UserQuizResult;
use App\Services\BadgeService;
use App\Services\NotificationService;
use App\Services\UserQuizService;
use App\Services\UserScoreService;
use App\Services\UserService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class UserQuizController extends Controller
{
    protected $userScoreService;
    protected $badgeService;
    protected $quizService, $notificationService;

    public function __construct(UserScoreService $userScoreService, BadgeService $badgeService, UserQuizService $quizService, NotificationService $notificationService)
    {
        $this->userScoreService = $userScoreService;
        $this->badgeService = $badgeService;
        $this->quizService = $quizService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user = Auth::user();
        $quizzes = UserQuiz::all();

        $groupedQuizzes = $quizzes->groupBy('difficulty')->map(function ($quizzes) use ($user) {
            return $quizzes->map(function ($quiz) use ($user) {

                $result = UserQuizResult::where('user_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->first();
                return [
                    'quizData' => $quiz,
                    'is_locked' => $result ? $result->is_locked : false,
                ];
            });
        });

        $leaderBoard = Cache::get('weekly_leaderboard', []);

        return Inertia::render('User/Quizzes/Index', [
            'quizzes' => $groupedQuizzes,
            'leaderboard' => $leaderBoard
        ]);
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
    public function show(string $quizId)
    {
        $user = Auth::user();

        $quiz = UserQuiz::with([
            'questions.answers' => function ($query) {
                $query->orderByRaw('RAND()');
            }
        ])->findOrFail($quizId);

        $quiz->questions = $quiz->questions->shuffle();

        $nr_attempts = $user->quizResults()->where('quiz_id', $quizId)->max('attempt_number') ?? 0;

        return Inertia::render('User/Quizzes/Show', [
            'quiz' => $quiz,
            'nr_attempts' => $nr_attempts,
        ]);
    }


    public function retryQuiz(Request $request)
    {
        $this->quizService->retryQuiz($request);
    }

    public function lockQuiz(Request $request)
    {
        $this->quizService->lockQuiz($request);
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
