<?php

namespace App\Services\Reports;

use App\Models\OrderProduct;
use App\Models\Review;
use App\Models\Wishlist;

class ProductsActivityReportService
{
    public function getMonthlyReport(): array
    {
        return [
            'month' => now()->format('F Y'),
            'best_selling_category' => $this->getBestSellingCategory(),
            'top_selling_products' => $this->getTopSellingProducts(),
            'new_vs_old_products_sales' => $this->getNewVsOldProductsSales(),
            'discount_vs_regular_sales' => $this->getDiscountVsRegularSales(),
            'best_and_worst_rated_products' => $this->getBestAndWorstRatedProducts(),
            'most_wishlisted_products' => $this->getMostWishlistedProducts(),
        ];
    }

    /**
     * Returnează categoria de produse cu cele mai mari vânzări.
     * 
     * @return array
     */
    private function getBestSellingCategory(): array
    {
        return OrderProduct::whereHas('order', function ($query) {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
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
    private function getTopSellingProducts(): array
    {
        return OrderProduct::whereHas('order', function ($query) {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        })
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(fn($group) => [
                'product_name' => $group->first()->product->name ?? 'Unknown',
                'total_sold' => $group->sum('quantity')
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
    private function getNewVsOldProductsSales(): array
    {
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $threeMonthsAgo = now()->subMonths(3);
        $oneYearAgo = now()->subYear();

        // Vânzări produse noi (doar cele create în ultimele 3 luni)
        $newProductsSales = OrderProduct::whereHas('product', function ($query) use ($threeMonthsAgo) {
            $query->where('created_at', '>=', $threeMonthsAgo);
        })
            ->whereHas('order', function ($query) use ($currentMonthStart, $currentMonthEnd) {
                $query->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd]);
            })
            ->sum('quantity');

        // Vânzări produse vechi (cele create între 3 și 12 luni în urmă)
        $oldProductsSales = OrderProduct::whereHas('product', function ($query) use ($oneYearAgo, $threeMonthsAgo) {
            $query->whereBetween('created_at', [$oneYearAgo, $threeMonthsAgo]);
        })
            ->whereHas('order', function ($query) use ($currentMonthStart, $currentMonthEnd) {
                $query->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd]);
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
    private function getDiscountVsRegularSales(): array
    {
        $discountedSales = OrderProduct::whereHas('order', function ($query) {
            $query->whereNotNull('promo_code')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        })
            ->sum('quantity'); // Produse cumpărate cu ofertă

        $regularSales = OrderProduct::whereDoesntHave('order', function ($query) {
            $query->whereNotNull('promo_code')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
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
    private function getBestAndWorstRatedProducts(): array
    {
        $reviews = Review::with('product')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
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
    private function getMostWishlistedProducts(): array
    {
        return Wishlist::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
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
