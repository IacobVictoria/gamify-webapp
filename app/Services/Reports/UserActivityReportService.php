<?php

namespace App\Services\Reports;

use App\Helpers\PeriodHelper;
use App\Models\ClientOrder;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Carbon\Carbon;


class UserActivityReportService
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
            'new_users_count' => $this->getNewUsersCount($startDate, $endDate),
            'avg_orders_per_user' => round($this->getAvgOrdersPerUser($startDate, $endDate), 2),
            'avg_order_value' => round($this->getAvgOrderValue($startDate, $endDate), 2),
            'top_wishlist_products' => $this->getWishlistInteractions($startDate, $endDate),
            'avg_reviews_per_user' => round($this->getAvgReviewsPerUser($startDate, $endDate), 2),
            'avg_likes_per_review' => round($this->getAvgLikesPerReview($startDate, $endDate), 2),
            'avg_days_to_first_order' => round($this->getAvgDaysToFirstOrder($startDate, $endDate), 2),
            'orders_with_discount' => $this->getOrdersWithDiscount($startDate, $endDate),
            'orders_without_discount' => $this->getOrdersWithoutDiscount($startDate, $endDate),
        ];
    }

    /**
     * Număr de utilizatori noi într-o perioadă selectată (lunar).
     */
    private function getNewUsersCount($startDate, $endDate): int
    {
        return User::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    /**
     * Câte comenzi plasează un utilizator în medie.
     */
    public function getAvgOrdersPerUser($startDate, $endDate): float
    {
        $ordersGroupedByUser = ClientOrder::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('user_id')
            ->map(fn($orders) => $orders->count());

        return $ordersGroupedByUser->avg() ?? 0;
    }

    /**
     * Valoarea medie a coșului de cumpărături.
     */
    public function getAvgOrderValue($startDate, $endDate): float
    {
        $ordersGroupedByUser = ClientOrder::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('user_id')
            ->map(fn($orders) => $orders->avg('total_price'));

        return $ordersGroupedByUser->avg() ?? 0;
        // val medie si dupa cat in medie, de aia returnez avg()
    }

    /**
     * Top 3 produse în wishlist în această lună.
     */
    private function getWishlistInteractions($startDate, $endDate): array
    {
        return Wishlist::whereBetween('created_at', [$startDate, $endDate])
            ->select('product_id')
            ->groupBy('product_id')
            ->selectRaw('product_id, COUNT(*) as total_wishlisted')
            ->orderByDesc('total_wishlisted')
            ->limit(3)
            ->with('product:id,name') // Luăm doar id și numele produsului pentru optimizare
            ->get()
            ->map(fn($wishlist) => [
                'product_id' => $wishlist->product_id,
                'name' => $wishlist->product->name ?? 'N/A',
                'wishlist_count' => $wishlist->total_wishlisted
            ])
            ->toArray();

    }

    /**
     * Numărul mediu de recenzii per utilizator în această lună.
     */
    public function getAvgReviewsPerUser($startDate, $endDate): float
    {
        // 1️⃣ Obținem recenziile grupate pe utilizator
        $reviews = Review::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('user_id, COUNT(*) as total_reviews')
            ->groupBy('user_id')
            ->get();

        // 2️⃣ Calculăm media recenziilor per utilizator
        return $reviews->avg('total_reviews') ?? 0;
    }

    /**
     * Numărul mediu de like-uri per recenzie în această lună.
     */
    private function getAvgLikesPerReview($startDate, $endDate): float
    {
        $reviews = Review::whereBetween('created_at', [$startDate, $endDate])
            ->withCount('reviewLikes')
            ->get();

        return $reviews->count() > 0 ? round($reviews->avg('review_likes_count'), 2) : 0;
    }

    /**
     * Timpul mediu (în zile) între crearea contului și prima achiziție într-o perioadă selectată.
     */
    private function getAvgDaysToFirstOrder($startDate, $endDate): float
    {
        $usersWithOrders = User::whereHas('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->with([
                'orders' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate])->oldest();
                }
            ])
            ->get();

        if ($usersWithOrders->isEmpty()) {
            return 0;
        }

        $daysToFirstOrder = $usersWithOrders->map(function ($user) {
            $firstOrderDate = $user->orders->first()?->created_at;
            return $firstOrderDate ? $user->created_at->diffInDays($firstOrderDate) : 0;
        });

        return round($daysToFirstOrder->avg(), 2);
    }


    /**
     * Câte comenzi au fost plasate cu un cod de reducere.
     */
    public function getOrdersWithDiscount($startDate, $endDate): int
    {
        return ClientOrder::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('promo_code')
            ->count();
    }

    /**
     * Câte comenzi au fost plasate fără un cod de reducere.
     */
    public function getOrdersWithoutDiscount($startDate, $endDate): int
    {
        return ClientOrder::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('promo_code')
            ->count();
    }
}
