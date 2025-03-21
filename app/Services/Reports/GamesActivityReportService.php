<?php

namespace App\Services\Reports;

use App\Helpers\PeriodHelper;
use App\Models\HangmanSession;
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
            'startDate' => $startDate,
            'endDate' => $endDate,
            'most_popular_difficulties' => $this->getMostPopularDifficulties($startDate, $endDate),
            'most_attempted_quizzes' => $this->getMostAttemptedQuizzes($startDate, $endDate),
            'average_quizzes_completed' => round($this->getAverageQuizzesCompleted($startDate, $endDate), 2),
            'quiz_success_rate' => $this->getQuizSuccessRate($startDate, $endDate),
            'hangman_completion_rate' => $this->getHangmanCompletionRate($startDate, $endDate) . '%',
            'average_quiz_retries' => round($this->getAverageQuizRetries($startDate, $endDate), 2),
        ];
    }


    /**
     * Most Popular Quiz Difficulty Levels
     */
    private function getMostPopularDifficulties($startDate, $endDate): array
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
    private function getAverageQuizRetries($startDate, $endDate): float
    {
        $quizAttempts = UserQuizResult::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->avg('attempt_number') ?? 0;

        return round($quizAttempts, 2);
    }
}