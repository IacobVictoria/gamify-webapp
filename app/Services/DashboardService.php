<?php

namespace App\Services;
use App\Models\Badge;
use App\Models\ClientOrder;
use App\Models\Event;
use App\Models\Permission;
use App\Models\Product;
use App\Models\QrCodeScan;
use App\Models\Role;
use App\Models\Survey;
use App\Models\User;
use App\Services\Reports\GamesActivityReportService;
use App\Services\Reports\ProductsActivityReportService;
use App\Services\Reports\UserActivityReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use function Aws\map;

class DashboardService
{
    protected $npsService, $productsActivityReportService, $userActivityReportService, $gamesActivityReportService;

    public function __construct(NpsService $npsService, ProductsActivityReportService $productsActivityReportService, UserActivityReportService $userActivityReportService, GamesActivityReportService $gamesActivityReportService)
    {
        $this->npsService = $npsService;
        $this->productsActivityReportService = $productsActivityReportService;
        $this->userActivityReportService = $userActivityReportService;
        $this->gamesActivityReportService = $gamesActivityReportService;
    }

    public function getUserDashboardData()
    {
        $user = Auth::user();
        $userScore = $user->score;
        $yourPositionInTop = User::where('score', '>', $userScore)->count() + 1;

        return [
            'account' => [
                'name' => $user->name,
                'score' => $userScore,
                'nr_badges' => $user->badges()->count(),
                'position_leaderboard' => $yourPositionInTop,
                'gender' => $user->gender
            ]
        ];
    }

    public function getAdminDashboardData()
    {
        // Totalul comenzilor din ultima săptămână
        $weeklySales = ClientOrder::where('created_at', '>=', Carbon::now()->subWeek())->sum('total_price');

        // Numărul total de comenzi din ultima săptămână
        $weeklyOrders = ClientOrder::where('created_at', '>=', Carbon::now()->subWeek())->count();

        // Număr de utilizatori noi înregistrați în ultima săptămână
        $newVisitors = User::where('created_at', '>=', Carbon::now()->subWeek())->count();

        // Previous Week Sales
        $previousWeekSales = ClientOrder::whereBetween('created_at', [Carbon::now()->subWeeks(2), Carbon::now()->subWeek()])->sum('total_price');
        $salesChange = $previousWeekSales > 0 ? (($weeklySales - $previousWeekSales) / $previousWeekSales) * 100 : 0;

        // Previous Week Orders
        $previousWeekOrders = ClientOrder::whereBetween('created_at', [Carbon::now()->subWeeks(2), Carbon::now()->subWeek()])->count();
        $ordersChange = $previousWeekOrders > 0 ? (($weeklyOrders - $previousWeekOrders) / $previousWeekOrders) * 100 : 0;

        // Previous Week New Visitors
        $previousWeekVisitors = User::whereBetween('created_at', [Carbon::now()->subWeeks(2), Carbon::now()->subWeek()])->count();
        $newVisitorsChange = $previousWeekVisitors > 0 ? (($newVisitors - $previousWeekVisitors) / $previousWeekVisitors) * 100 : 0;

        $topProductsWeekly = $this->productsActivityReportService->getTopSellingProducts(now()->subWeek(), now());

        $progressBarStats = $this->getStatsProgressBar();
        $currentDiscounts = $this->getCurrentDiscounts();

        $variousStats = $this->getVariousStats();
        $globalStats = $this->getGlobalInsights();
        $survey = Survey::where('is_published', true)->first();
        // Verifică dacă există un survey publicat
        $nps = $survey ? $this->npsService->calculateNps($survey->id)['nps'] : null;
        $monthlyNpsData = $survey ? $this->npsService->calculateMonthlyNps($survey->id) : [];

        return [
            'nps' => $nps,
            'monthlyNpsData' => $monthlyNpsData,
            'weeklySales' => number_format($weeklySales, 2, '.', ','),
            'weeklyOrders' => $weeklyOrders,
            'newVisitors' => $newVisitors,
            'salesChange' => round($salesChange, 2),
            'ordersChange' => round($ordersChange, 2),
            'newVisitorsChange' => round($newVisitorsChange, 2),
            'topProductsWeekly' => $topProductsWeekly,
            'progressBarStats' => $progressBarStats,
            'currentDiscounts' => $currentDiscounts,
            'variousStats' => $variousStats,
            'globalStats' => $globalStats,
            'toggleSuperAdmin' => false,

        ];
    }

    public function getSuperAdminDashboardData(){
        return [
            'toggleSuperAdmin' => true
        ];
    }

    public function getAdminGamificationDashboardData(){
        return [
            'toggleAdminGamification' => true
        ];
    }

    public function getCurrentDiscounts()
    {
        $now = Carbon::now();

        return Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('start', '<=', $now)
            ->where('end', '>=', $now)
            ->where('is_published', true)
            ->get()
            ->map(function ($discount) use ($now) {
                $details = json_decode($discount->details, true);
                $totalDuration = Carbon::parse($discount->end)->diffInSeconds(Carbon::parse($discount->start));
                $elapsed = Carbon::parse($discount->start)->diffInSeconds($now);
                return [
                    'title' => $discount->title,
                    'percentage' => $details['discount'] ?? 0,
                    'applyTo' => $details['applyTo'] ?? 'all',
                    'category' => $details['applyTo'] === 'categories' ? ($details['category'] ?? null) : null,
                    'expire_date' => Carbon::parse($discount->end)->format('d-m-Y H:i'),
                    'progress' => $totalDuration > 0 ? ($elapsed / $totalDuration) * 100 : 0
                ];
            })->toArray();
    }
    public function getStatsProgressBar()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Numărul total de comenzi plasate cu promo codes săptămâna aceasta
        $totalOrdersWithPromo = ClientOrder::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereNotNull('promo_code')
            ->count();

        $totalOrders = ClientOrder::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        $promoOrdersPercentage = $totalOrders > 0 ? ($totalOrdersWithPromo / $totalOrders) * 100 : 0;

        // Media numărului de insigne pe utilizator
        $totalUsers = User::count();
        $totalBadges = Badge::count();

        $averageBadgesPerUser = $totalUsers > 0 ? (User::withCount('badges')->get()->avg('badges_count')) : 0;
        $badgesPercentage = $totalBadges > 0 ? ($averageBadgesPerUser / $totalBadges) * 100 : 0;

        return [
            'promoOrdersPercentage' => round($promoOrdersPercentage, 2),
            'averageBadgesPerUser' => round($averageBadgesPerUser, 2),
            'badgesPercentage' => round($badgesPercentage, 2),
        ];
    }

    public function getVariousStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $topUsers = User::orderByDesc('score')->take(5)->get(['name', 'score']);

        $newUsers = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $newUsersWithOrders = User::whereHas('orders', function ($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        })->count();

        $conversionRate = $newUsers > 0 ? ($newUsersWithOrders / $newUsers) * 100 : 0;

        $topWishlistProducts = Product::withCount('wishlists')->where('is_published', true)->orderByDesc('wishlists_count')->take(5)->get(['name', 'wishlists_count']);

        $qrScans = QrCodeScan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        return [
            'topUsers' => $topUsers,
            'conversionRate' => round($conversionRate, 2),
            'topWishlistProducts' => $topWishlistProducts,
            'weeklyQrScans' => $qrScans
        ];
    }
    public function getGlobalInsights()
    {
        $startDate = Carbon::now()->subYears(100);
        $endDate = Carbon::now();
        return [
            'avgQuizzesCompleted' => $this->gamesActivityReportService->
                getAverageQuizzesCompleted($startDate, $endDate),
            'hangmanCompletionRate' => $this->gamesActivityReportService->getHangmanCompletionRate($startDate, $endDate),
            'quizSuccessRates' => $this->gamesActivityReportService->getQuizSuccessRate($startDate, $endDate),
            'avgReviewsPerUser' => $this->userActivityReportService->getAvgReviewsPerUser($startDate, $endDate),
            'ordersWithDiscount' => $this->userActivityReportService->getOrdersWithDiscount($startDate, $endDate),
            'ordersWithoutDiscount' => $this->userActivityReportService->getOrdersWithoutDiscount($startDate, $endDate),
            'usersWithAllDiscountsUsed' => User::whereNotNull('used_discounts')->get()
                ->filter(function ($user) {
                    $discounts = json_decode($user->used_discounts, true);
                    return collect($discounts)->every(fn($item) => $item['used'] === true);
                })->count(),
        ];
    }
}
