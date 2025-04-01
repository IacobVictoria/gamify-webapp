<?php

namespace App\Services\DashboardRolesType;

use App\Models\ClientOrder;
use App\Models\Event;
use App\Models\Product;
use App\Models\QrCodeScan;
use App\Models\Survey;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\NpsService;
use App\Services\Reports\ProductsActivityReportService;
use App\Services\Reports\SalesStockReportService;
use App\Services\Reports\UserActivityReportService;
use Carbon\Carbon;

class AdminDashboardService
{

    protected $npsService, $productsActivityReportService, $userActivityReportService, $salesStockReportService;

    public function __construct(NpsService $npsService, UserActivityReportService $userActivityReportService, ProductsActivityReportService $productsActivityReportService, SalesStockReportService $salesStockReportService)
    {
        $this->npsService = $npsService;
        $this->productsActivityReportService = $productsActivityReportService;
        $this->userActivityReportService = $userActivityReportService;
        $this->salesStockReportService = $salesStockReportService;
    }

    public function getDashboardData()
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

        $progressBarStats = $this->getStatsAdminProgressBar();
        $currentDiscounts = $this->getCurrentDiscounts();

        $variousStats = $this->getVariousAdminStats();
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
            'toggleAdmin' => true

        ];
    }

    public function getStatsAdminProgressBar()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Numărul total de comenzi plasate cu promo codes săptămâna aceasta
        $totalOrdersWithPromo = ClientOrder::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereNotNull('promo_code')
            ->count();

        $totalOrders = ClientOrder::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        $promoOrdersPercentage = $totalOrders > 0 ? ($totalOrdersWithPromo / $totalOrders) * 100 : 0;

        // Procentul produselor vândute cu reducere față de cele vândute fără reducere (din totalul comenzilor)
        $discountStats = app(ProductsActivityReportService::class)->getDiscountVsRegularSales($startOfWeek, $endOfWeek);
        $discountUsageRate = str_replace('%', '', $discountStats['discount_usage_rate']);

        // Procentul produselor care au fost adăugate în wishlist de către utilizatori săptămâna aceasta
        $wishlistedCount = Wishlist::whereBetween('created_at', [$startOfWeek, $endOfWeek])->distinct('product_id')->count();
        $totalProducts = Product::count();
        $wishlistedPercentage = $totalProducts > 0 ? ($wishlistedCount / $totalProducts) * 100 : 0;

        // Procentul produselor care au avut mai multe intrări (reaprovizionare) decât ieșiri (vânzări), indicând restocare sănătoasă
        $stockFluctuations = app(SalesStockReportService::class)->getStockFluctuations($startOfWeek, $endOfWeek);
        $replenished = collect($stockFluctuations)->filter(fn($item) => $item['stock_in'] > $item['stock_out'])->count();
        $replenishmentRate = count($stockFluctuations) > 0 ? ($replenished / count($stockFluctuations)) * 100 : 0;

        return [
            'promoOrdersPercentage' => round($promoOrdersPercentage, 2),
            'discountUsageRate' => round($discountUsageRate, 2),
            'wishlistedPercentage' => round($wishlistedPercentage, 2),
            'replenishmentRate' => round($replenishmentRate, 2),
        ];
    }

    public function getVariousAdminStats()
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
}