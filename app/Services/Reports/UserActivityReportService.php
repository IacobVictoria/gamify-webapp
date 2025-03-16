<?php

namespace App\Services\Reports;

use App\Models\ClientOrder;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\ReviewLike;
use App\Models\User;
use App\Models\Wishlist;


class UserActivityReportService
{
    public function getMonthlyReport(): array
    {
        return [
            'month' => now()->format('F Y'),
            'new_users_count' => $this->getNewUsersCount(),
            'avg_orders_per_user' => round($this->getAvgOrdersPerUser(), 2),
            'avg_order_value' => round($this->getAvgOrderValue(), 2),
            'top_wishlist_products' => $this->getWishlistInteractions(),
            'avg_reviews_per_user' => round($this->getAvgReviewsPerUser(), 2),
            'avg_likes_per_review' => round($this->getAvgLikesPerReview(), 2),
            'avg_days_to_first_order' => round($this->getAvgDaysToFirstOrder(), 2),
            'orders_with_discount' => $this->getOrdersWithDiscount(),
            'orders_without_discount' => $this->getOrdersWithoutDiscount(),
        ];
    }

    /**
     * Număr de utilizatori noi într-o perioadă selectată (lunar).
     */
    private function getNewUsersCount(): int
    {
        return User::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
    }

    /**
     * Câte comenzi plasează un utilizator în medie.
     */
    private function getAvgOrdersPerUser(): float
    {
        $ordersGroupedByUser = ClientOrder::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->groupBy('user_id')
            ->map(fn($orders) => $orders->count());

        return $ordersGroupedByUser->avg() ?? 0;
    }

    /**
     * Valoarea medie a coșului de cumpărături.
     */
    private function getAvgOrderValue(): float
    {
        $ordersGroupedByUser = ClientOrder::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->groupBy('user_id')
            ->map(fn($orders) => $orders->avg('total_price'));

        return $ordersGroupedByUser->avg() ?? 0;
        // val medie si dupa cat in medie, de aia returnez avg()
    }

    /**
     * Top 3 produse în wishlist în această lună.
     */
    private function getWishlistInteractions(): array
    {
        return Wishlist::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
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
    private function getAvgReviewsPerUser(): float
    {
        // 1️⃣ Obținem recenziile grupate pe utilizator
        $reviews = Review::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('user_id, COUNT(*) as total_reviews')
            ->groupBy('user_id')
            ->get();

        // 2️⃣ Calculăm media recenziilor per utilizator
        return $reviews->avg('total_reviews') ?? 0;
    }

    /**
     * Numărul mediu de like-uri per recenzie în această lună.
     */
    private function getAvgLikesPerReview(): float
    {
        $reviews = Review::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->withCount('reviewLikes')
            ->get();

        return $reviews->count() > 0 ? round($reviews->avg('review_likes_count'), 2) : 0;
    }

    /**
     * Timpul mediu (în zile) între crearea contului și prima achiziție.
     */
    private function getAvgDaysToFirstOrder(): float
    {
        $usersWithOrders = User::whereHas('orders')->with('orders')->get();

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
    private function getOrdersWithDiscount(): int
    {
        return ClientOrder::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereNotNull('promo_code')
            ->count();
    }

    /**
     * Câte comenzi au fost plasate fără un cod de reducere.
     */
    private function getOrdersWithoutDiscount(): int
    {
        return ClientOrder::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereNull('promo_code')
            ->count();
    }
}
