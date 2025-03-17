<?php

namespace App\Services\Reports;

use App\Models\HangmanSession;
use App\Models\UserQuizResult;
use Illuminate\Support\Facades\DB;

class GamesActivityReportService
{
    public function getMonthlyReport(): array
    {
        return [
            'month' => now()->format('F Y'),
            'most_popular_difficulties' => $this->getMostPopularDifficulties(),
            'most_attempted_quizzes' => $this->getMostAttemptedQuizzes(),
            'average_quizzes_completed' => round($this->getAverageQuizzesCompleted(), 2),
            'quiz_success_rate' => $this->getQuizSuccessRate(),
            'hangman_completion_rate' => $this->getHangmanCompletionRate() . '%',
            'average_quiz_retries' => round($this->getAverageQuizRetries(), 2),
        ];
    }


    /**
     * Most Popular Quiz Difficulty Levels
     */
    private function getMostPopularDifficulties(): array
    {
        return UserQuizResult::with('quiz:id,difficulty')
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
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
    private function getMostAttemptedQuizzes(): array
    {
        return UserQuizResult::select('quiz_id', DB::raw('SUM(attempt_number) as total_attempts'))
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
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

    private function getHangmanCompletionRate(): float
    {
        $totalGames = HangmanSession::count();
        $completedGames = HangmanSession::where('completed', true)->count();

        return $totalGames > 0 ? round(($completedGames / $totalGames) * 100, 2) : 0;
    }

    /**
     * Average Quizzes Completed Per User
     */
    private function getAverageQuizzesCompleted(): float
    {
        $userQuizCounts = UserQuizResult::select('user_id', DB::raw('COUNT(quiz_id) as quizzes_completed'))
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('is_locked', true)
            ->groupBy('user_id')
            ->get();

        return $userQuizCounts->avg('quizzes_completed') ?? 0;
    }

    //Dacă vrei să vezi câți utilizatori termină quiz-ul cu un scor de peste 80%
    private function getQuizSuccessRate(): array
    {
        return UserQuizResult::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
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
    private function getAverageQuizRetries(): float
    {
        $quizAttempts = UserQuizResult::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->avg('attempt_number') ?? 0;

        return round($quizAttempts, 2);
    }
}