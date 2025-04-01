<?php

namespace App\Services\Reports;

use App\Helpers\PeriodHelper;
use App\Models\OrderProduct;
use App\Models\Review;
use App\Models\Wishlist;
use Carbon\Carbon;

class ProductsActivityReportService
{
    public function getReportByPeriod(string $period, Carbon $meetingDate): array
    {
        $dateRange = PeriodHelper::getPeriodRange($period, $meetingDate);
        $startDate = $dateRange['start_date'];
        $endDate = $dateRange['end_date'];

        return [
            'period' => $period,
            'startDate' => $dateRange['start_date'],
            'endDate' => $dateRange['end_date'],
            'best_selling_category' => $this->getBestSellingCategory($startDate, $endDate),
            'top_selling_products' => $this->getTopSellingProducts($startDate, $endDate),
            'new_vs_old_products_sales' => $this->getNewVsOldProductsSales($startDate, $endDate),
            'discount_vs_regular_sales' => $this->getDiscountVsRegularSales($startDate, $endDate),
            'best_and_worst_rated_products' => $this->getBestAndWorstRatedProducts($startDate, $endDate),
            'most_wishlisted_products' => $this->getMostWishlistedProducts($startDate, $endDate),
        ];
    }

    /**
     * Returnează categoria de produse cu cele mai mari vânzări.
     * 
     * @return array
     */
    private function getBestSellingCategory($startDate, $endDate): array
    {
        return OrderProduct::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->with('product')
            ->get()
            ->groupBy(fn($orderProduct) => $orderProduct->product->category ?? 'Unknown') // Grupăm după categorie
            ->map(fn($group) => $group->sum('quantity')) // Calculăm totalul vânzărilor per categorie
            ->sortDesc()
            ->take(1)
            ->map(fn($total, $category) => ['category' => $category, 'total_sold' => $total])
            ->values()
            ->first() ?? [];
    }

    /**
     * Returnează produsele cu cele mai mari vânzări.
     * 
     * @return array
     */
    public function getTopSellingProducts($startDate, $endDate): array
    {
        return OrderProduct::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(fn($group) => [
                'product_name' => $group->first()->product->name ?? 'Unknown',
                'total_sold' => $group->sum('quantity'),
                'image' => $group->first()->product->image_url
            ])
            ->sortByDesc('total_sold')
            ->values()
            ->take(10)
            ->toArray();
    }

    /**
     * Compară vânzările produselor noi cu cele vechi (în luna curentă).
     * 
     * @return array
     */
    private function getNewVsOldProductsSales($startDate, $endDate): array
    {
        $threeMonthsAgo = now()->subMonths(3);
        $oneYearAgo = now()->subYear();

        $newProductsSales = OrderProduct::whereHas('product', function ($query) use ($threeMonthsAgo) {
            $query->where('created_at', '>=', $threeMonthsAgo);
        })
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('quantity');

        $oldProductsSales = OrderProduct::whereHas('product', function ($query) use ($oneYearAgo, $threeMonthsAgo) {
            $query->whereBetween('created_at', [$oneYearAgo, $threeMonthsAgo]);
        })
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('quantity');

        return [
            'new_products_sales' => $newProductsSales,
            'old_products_sales' => $oldProductsSales,
            'comparison' => $newProductsSales > $oldProductsSales ? 'New products sell better' : 'Old products sell better'
        ];
    }

    /**
     * Returnează numărul de produse cumpărate cu oferte speciale versus fără oferte.
     * 
     * @return array
     */
    public function getDiscountVsRegularSales($startDate, $endDate): array
    {
        $discountedSales = OrderProduct::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereNotNull('promo_code')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->sum('quantity'); // Produse cumpărate cu ofertă

        $regularSales = OrderProduct::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereNull('promo_code')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->sum('quantity'); // Produse cumpărate fără ofertă

        return [
            'discounted_sales' => $discountedSales,
            'regular_sales' => $regularSales,
            'discount_usage_rate' => $discountedSales + $regularSales > 0
                ? round(($discountedSales / ($discountedSales + $regularSales)) * 100, 2) . '%'
                : 'N/A'
        ];
    }

    /**
     * Returnează produsul cu cel mai bun și cel mai slab rating din recenzii.
     * 
     * @return array
     */
    private function getBestAndWorstRatedProducts($startDate, $endDate): array
    {
        $reviews = Review::with('product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $ratings = $reviews->groupBy('product_id')->map(fn($group) => [
            'product' => $group->first()->product,
            'avg_rating' => $group->avg('rating'),
        ]);

        // Sortam după rating descrescător
        $sorted = $ratings->sortByDesc('avg_rating');

        return [
            'best_rated_product' => $sorted->first()
                ? [
                    'name' => $sorted->first()['product']->name ?? 'N/A',
                    'rating' => round($sorted->first()['avg_rating'], 2)
                ]
                : null,
            'worst_rated_product' => $sorted->last()
                ? [
                    'name' => $sorted->last()['product']->name ?? 'N/A',
                    'rating' => round($sorted->last()['avg_rating'], 2)
                ]
                : null
        ];
    }

    /**
     * Returnează produsele adăugate cel mai des în wishlist de utilizatori.
     * 
     * @return array
     */
    private function getMostWishlistedProducts($startDate, $endDate): array
    {
        return Wishlist::whereBetween('created_at', [$startDate, $endDate])
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(fn($group) => [
                'product_name' => $group->first()->product->name ?? 'Unknown',
                'total_wishlisted' => $group->count()
            ])
            ->sortByDesc('total_wishlisted')
            ->values()
            ->take(10)
            ->toArray();
    }
}
