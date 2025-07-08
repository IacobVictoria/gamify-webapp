<?php

namespace App\Services\Reports;

use App\Helpers\PeriodHelper;
use App\Models\HangmanSession;
use App\Models\Participant;
use App\Models\UserQuizResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GamesActivityReportService
{
    public function getReportByPeriod(string $period, Carbon $meetingDate): array
    {
        $dateRange = PeriodHelper::getPeriodRange($period, $meetingDate);
        $startDate = $dateRange['start_date'];
        $endDate = $dateRange['end_date'];

        return [
            'period' => $period,
            'startDate' => Carbon::parse($dateRange['start_date'])->format('d.m.Y'),
            'endDate' => Carbon::parse($dateRange['end_date'])->format('d.m.Y'),
            'most_popular_difficulties' => $this->getMostPopularDifficulties($startDate, $endDate),
            'most_attempted_quizzes' => $this->getMostAttemptedQuizzes($startDate, $endDate),
            'average_quizzes_completed' => round($this->getAverageQuizzesCompleted($startDate, $endDate), 2),
            'quiz_success_rate' => $this->getQuizSuccessRate($startDate, $endDate),
            'hangman_completion_rate' => $this->getHangmanCompletionRate($startDate, $endDate) . '%',
            'average_quiz_retries' => round($this->getAverageQuizRetries($startDate, $endDate), 2),
            'activity_stats' => [
                'total_saved' => $this->getTotalSavedActivities($startDate, $endDate),
                'average_score' => round($this->getAverageActivityScore($startDate, $endDate), 2),
                'most_appreciated_type' => $this->getMostAppreciatedActivityType($startDate, $endDate),
                'most_saved_activities' => $this->getMostSavedActivities($startDate, $endDate),
            ],
        ];
    }


    /**
     * Most Popular Quiz Difficulty Levels
     */
    public function getMostPopularDifficulties($startDate, $endDate): array
    {
        return UserQuizResult::with('quiz:id,difficulty')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($result) => $result->quiz->difficulty ?? 'Unknown')
            ->map(fn($group, $difficulty) => [
                'difficulty' => ucfirst($difficulty),
                'total_attempts' => $group->count()
            ])
            ->sortByDesc('total_attempts')
            ->values()
            ->toArray();
    }

    /**
     *  Most Attempted Quizzes
     */
    private function getMostAttemptedQuizzes($startDate, $endDate): array
    {
        return UserQuizResult::select('quiz_id', DB::raw('SUM(attempt_number) as total_attempts'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('quiz_id')
            ->orderByDesc('total_attempts')
            ->limit(10)
            ->with('quiz:id,title')
            ->get()
            ->map(fn($quiz) => [
                'title' => $quiz->quiz->title ?? 'N/A',
                'total_attempts' => $quiz->total_attempts
            ])
            ->toArray();
    }

    // Dacă vrei să vezi dacă utilizatorii abandonează jocurile, compară jocurile finalizate cu cele începute.

    public function getHangmanCompletionRate($startDate, $endDate): float
    {
        $totalGames = HangmanSession::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedGames = HangmanSession::whereBetween('created_at', [$startDate, $endDate])
            ->where('completed', true)
            ->count();

        return $totalGames > 0 ? round(($completedGames / $totalGames) * 100, 2) : 0;
    }

    /**
     * Average Quizzes Completed Per User
     */
    public function getAverageQuizzesCompleted($startDate, $endDate): float
    {
        $userQuizCounts = UserQuizResult::select('user_id', DB::raw('COUNT(quiz_id) as quizzes_completed'))
            ->whereBetween('date', [$startDate, $endDate])
            ->where('is_locked', true)
            ->groupBy('user_id')
            ->get();

        return $userQuizCounts->avg('quizzes_completed') ?? 0;
    }

    //Dacă vrei să vezi câți utilizatori termină quiz-ul cu un scor de peste 80%
    public function getQuizSuccessRate($startDate, $endDate): array
    {
        return UserQuizResult::whereBetween('date', [$startDate, $endDate])
            ->where('percentage_score', '>=', 80)
            ->with('quiz:id,title')
            ->get()
            ->groupBy('quiz_id')
            ->map(fn($group) => [
                'quiz_title' => $group->first()->quiz->title ?? 'N/A',
                'total_successful_attempts' => $group->count(),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Average Quiz Retakes Per User
     */
    public function getAverageQuizRetries($startDate, $endDate): float
    {
        $quizAttempts = UserQuizResult::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->avg('attempt_number') ?? 0;

        return round($quizAttempts, 2);
    }

    //Câte activități sunt salvate în total
    public function getTotalSavedActivities($startDate, $endDate): int
    {
        return Participant::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_favorite', true)
            ->count();
    }

    //Media punctajelor activităților 
    public function getAverageActivityScore($startDate, $endDate): float
    {
        return Participant::whereBetween('created_at', [$startDate, $endDate])
            ->with('activity:id,score')
            ->get()
            ->avg(fn($p) => $p->activity->score ?? 0);
    }

    //Cel mai apreciat tip de activitate
    public function getMostAppreciatedActivityType($startDate, $endDate): array
    {
        return Participant::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_favorite', true)
            ->with('activity:id,type')
            ->get()
            ->groupBy(fn($p) => $p->activity->type ?? 'unknown')
            ->map(fn($group, $type) => [
                'type' => ucfirst($type),
                'total_saves' => $group->count()
            ])
            ->sortByDesc('total_saves')
            ->values()
            ->toArray();
    }
    public function getMostSavedActivities($startDate, $endDate, $limit = 5): array
    {
        return Participant::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_favorite', true)
            ->with('activity:id,title')
            ->get()
            ->groupBy('activity_id')
            ->map(function ($group) {
                return [
                    'title' => optional($group->first()->activity)->title ?? 'N/A',
                    'total_saves' => $group->count(),
                ];
            })
            ->sortByDesc('total_saves')
            ->take($limit)
            ->values()
            ->toArray();
    }

}