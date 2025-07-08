<?php

namespace App\Services\DashboardRolesType;

use App\Models\Activity;
use App\Models\Badge;
use App\Models\Participant;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserMedal;
use App\Models\UserQuizResult;
use App\Services\Reports\GamesActivityReportService;
use App\Services\Reports\RewardsActivityReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminGamificationDashboardService
{
    protected $gamesActivityReportService, $rewardsActivityReportService;

    public function __construct(GamesActivityReportService $gamesActivityReportService, RewardsActivityReportService $rewardsActivityReportService)
    {
        $this->gamesActivityReportService = $gamesActivityReportService;
        $this->rewardsActivityReportService = $rewardsActivityReportService;
    }

    public function getDashboardData()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();


        $popularQuizzes = UserQuizResult::with('quiz:id,title')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->groupBy('quiz_id')
            ->select('quiz_id', DB::raw('COUNT(*) as appearances'))
            ->orderByDesc('appearances')
            ->take(10)
            ->get()
            ->map(fn($q) => [
                'title' => $q->quiz->title ?? 'Unknown',
                'image' => asset('images/landing/fun_1.png'),
                'appearances' => $q->appearances
            ])
            ->toArray();

        $progressBarStats = $this->getStatsGamificationProgressBar();
        $variousStats = $this->getVariousGamificationStats();
        $globalStats = $this->getGamificationGlobalInsights();
        $activitiesInsights = $this->getActivitiesStatsForDashboard();

        return [
            'toggleAdminGamification' => true,
            'weeklyBadges' => UserBadge::whereBetween('awarded_at', [$startOfMonth, $endOfMonth])->count(),
            'weeklyMedals' => UserMedal::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'avgQuizScore' => UserQuizResult::whereBetween('date', [$startOfMonth, $endOfMonth])->avg('percentage_score') ?? 0,
            'topQuizzes' => $popularQuizzes,
            'progressBarStats' => $progressBarStats,
            'variousStats' => $variousStats,
            'globalStats' => $globalStats,
            'activitiesInsights' => $activitiesInsights
        ];
    }

    public function getStatsGamificationProgressBar()
    {
        // Media numărului de insigne pe utilizator
        $totalUsers = User::count();
        $totalBadges = Badge::count();

        $averageBadgesPerUser = $totalUsers > 0 ? (User::withCount('badges')->get()->avg('badges_count')) : 0;
        $badgesPercentage = $totalBadges > 0 ? ($averageBadgesPerUser / $totalBadges) * 100 : 0;

        // Progres badge-uri per categorie (medie generală)
        $badgesPerCategory = $this->rewardsActivityReportService
            ->getAverageBadgesPerCategory(now()->subMonths(3), now());

        $categoryBadgeProgress = count($badgesPerCategory) > 0
            ? round(collect($badgesPerCategory)->avg(), 2) * 10 // aducem într-un format de %
            : 0;

        // Rata de completare Hangman
        $hangmanRate = $this->gamesActivityReportService
            ->getHangmanCompletionRate(now()->subMonths(3), now());

        // Rata medie de reîncercare quizuri – cu interpretare inversă
        $avgRetries = $this->gamesActivityReportService
            ->getAverageQuizRetries(now()->subMonths(3), now());
        $quizRetryRate = $avgRetries > 0 ? (100 - min($avgRetries * 10, 100)) : 100; // invers: mai puține retry-uri = progres mai mare

        // Rata de succes la quizuri (peste 80%)
        $quizSuccess = $this->gamesActivityReportService
            ->getQuizSuccessRate(now()->subMonths(3), now());
        $totalSuccessfulAttempts = collect($quizSuccess)->sum('total_successful_attempts');
        $quizSuccessRate = $totalUsers > 0 ? min(($totalSuccessfulAttempts / $totalUsers) * 10, 100) : 0;

        return [
            'averageBadgesPerUser' => round($averageBadgesPerUser, 2),
            'badgesPercentage' => round($badgesPercentage, 2),

            // Badge-uri obținute în medie per categorie (scor generalizat)
            'categoryBadgeProgress' => round($categoryBadgeProgress, 2),

            // Rata de completare a jocurilor Hangman
            'hangmanCompletionRate' => round($hangmanRate, 2),

            // Rata de retry la quizuri (cu scor invers – mai mic = mai bine)
            'quizRetryRate' => round($quizRetryRate, 2),

            // Rata generală de succes la quizuri (peste 80%)
            'quizSuccessRate' => round($quizSuccessRate, 2),
        ];
    }

    public function getVariousGamificationStats()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();


        // Cele mai frecvente insigne acordate în săptămâna curentă
        $topBadges = UserBadge::whereBetween('awarded_at', [$startOfMonth, $endOfMonth])
            ->with('badge:id,name,image_path')
            ->select('badge_id', DB::raw('COUNT(*) as awarded_count'))
            ->groupBy('badge_id')
            ->orderByDesc('awarded_count')
            ->take(5)
            ->get()
            ->map(fn($item) => [
                'name' => $item->badge->name ?? 'N/A',
                'image' => $item->badge->image_path,
                'count' => $item->awarded_count
            ]);

        // Quiz-uri cu cel mai mare scor mediu
        $topRatedQuizzes = UserQuizResult::with('quiz:id,title')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->groupBy('quiz_id')
            ->select('quiz_id', DB::raw('AVG(percentage_score) as avg_score'))
            ->orderByDesc('avg_score')
            ->take(5)
            ->get()
            ->map(fn($q) => [
                'title' => $q->quiz->title ?? 'N/A',
                'avg_score' => round($q->avg_score, 2)
            ]);

        // Numărul total de insigne și medalii acordate săptămâna aceasta
        $weeklyBadges = UserBadge::whereBetween('awarded_at', [$startOfMonth, $endOfMonth])->count();
        $weeklyMedals = UserMedal::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        return [
            'topBadges' => $topBadges,
            'topRatedQuizzes' => $topRatedQuizzes,
            'weeklyBadgesCount' => $weeklyBadges,
            'weeklyMedalsCount' => $weeklyMedals,
        ];
    }

    public function getGamificationGlobalInsights()
    {
        $startDate = Carbon::now()->subYears(100);
        $endDate = Carbon::now();
        return [
            'avgQuizzesCompleted' => $this->gamesActivityReportService->
                getAverageQuizzesCompleted($startDate, $endDate),
            'hangmanCompletionRate' => $this->gamesActivityReportService->getHangmanCompletionRate($startDate, $endDate),
            'quizSuccessRates' => $this->gamesActivityReportService->getQuizSuccessRate($startDate, $endDate),
            'avgQuizRetries' => $this->gamesActivityReportService->getAverageQuizRetries($startDate, $endDate),
            'mostPopularDifficulties' => $this->gamesActivityReportService->getMostPopularDifficulties($startDate, $endDate),
        ];
    }
    public function getActivitiesStatsForDashboard()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $totalActivities = Activity::count();

        $totalParticipantsThisWeek = Participant::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        $averageScorePerActivity = Activity::avg('score') ?? 0;

        $mostPopularActivity = Activity::withCount('participants')
            ->orderByDesc('participants_count')
            ->first();

        $mostPopularType = Participant::with('activity')
            ->get()
            ->groupBy(fn($p) => $p->activity->type ?? 'unknown')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->keys()
            ->first();

        $totalFavorites = Participant::where('is_favorite', 1)->count();

        return [
            'totalActivities' => $totalActivities,
            'totalParticipantsThisWeek' => $totalParticipantsThisWeek,
            'averageScorePerActivity' => round($averageScorePerActivity, 2),
            'mostPopularActivity' => $mostPopularActivity
                ? [
                    'title' => $mostPopularActivity->title,
                    'type' => $mostPopularActivity->type,
                    'participants' => $mostPopularActivity->participants_count,
                    'score' => $mostPopularActivity->score,
                ] : null,
            'mostPopularType' => ucfirst($mostPopularType ?? 'N/A'),
            'totalFavorites' => $totalFavorites,
        ];
    }
}