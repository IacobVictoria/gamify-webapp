<?php

namespace App\Services\Reports;

use App\Models\InventoryTransaction;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesStockReportService
{
    public function getMonthlyReport(): array
    {
        return [
            'month' => now()->format('F Y'),
            'top_10_sold_products' => $this->getTopSoldProducts(),
            'least_sold_products' => $this->getLeastSoldProducts(),
            'stock_fluctuations' => $this->getStockFluctuations(),
            'daily_sales_avg' => round($this->getDailySalesAvg(), 2),
            'weekly_sales_avg' => round($this->getWeeklySalesAvg(), 2),
            'avg_days_to_out_of_stock' => round($this->getAvgDaysToOutOfStock(), 2),
        ];
    }

    /**
     * Top 10 produse vândute lunar
     */
    private function getTopSoldProducts(): array
    {
        return OrderProduct::select('product_id', Db::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) {
                $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->with('product:id,name')
            ->get()
            ->map(fn($item) => [
                'name' => $item->product->name ?? 'N/A',
                'total_sold' => $item->total_sold
            ])
            ->toArray();
    }

    /**
     * Cele mai puțin vândute produse (lunar)
     */
    private function getLeastSoldProducts(): array
    {
        return OrderProduct::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) {
                $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
            })
            ->groupBy('product_id')
            ->orderBy('total_sold')
            ->limit(10)
            ->with('product:id,name')
            ->get()
            ->map(fn($item) => [
                'name' => $item->product->name ?? 'N/A',
                'total_sold' => $item->total_sold
            ])
            ->toArray();
    }

    /**
     * Produse cu cele mai mari variații de stoc
     * Identifici dacă ai produse cu re-aprovizionare frecventă sau produse care stagnează.
     */

    private function getStockFluctuations(): array
    {
        return InventoryTransaction::select(
            'product_id',
            DB::raw('SUM(CASE WHEN transaction_type = "IN" THEN quantity ELSE 0 END) as total_in'),
            DB::raw('SUM(CASE WHEN transaction_type = "OUT" THEN quantity ELSE 0 END) as total_out'),
            DB::raw('ABS(SUM(CASE WHEN transaction_type = "IN" THEN quantity ELSE 0 END) - 
                     SUM(CASE WHEN transaction_type = "OUT" THEN quantity ELSE 0 END)) as stock_variation')
        )
            ->whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->groupBy('product_id')
            ->orderByDesc(DB::raw('ABS(SUM(CASE WHEN transaction_type = "IN" THEN quantity ELSE 0 END) - 
                                SUM(CASE WHEN transaction_type = "OUT" THEN quantity ELSE 0 END))'))
            ->limit(10)
            ->with('product:id,name')
            ->get()
            ->map(fn($transaction) => [
                'name' => $transaction->product->name ?? 'N/A',
                'stock_in' => $transaction->total_in,
                'stock_out' => $transaction->total_out,
                'stock_variation' => $transaction->stock_variation,
                'recommendation' => $this->getStockRecommendation($transaction->total_in, $transaction->total_out)
            ])
            ->toArray();
    }

    private function getStockRecommendation(int $stockIn, int $stockOut): string
    {
        if ($stockIn > $stockOut) {
            return "**Stoc prea mare**: Vanzarile sunt mai mici decat reaprovizionarea. 
        Recomandari: Analizeaza cererea, aplica reduceri sau promotii, si evita comenzi excesive.";
        } elseif ($stockOut > $stockIn) {
            return "**Stoc se epuizeaza rapid**: Se vinde mai repede decat reaprovizionarea. 
        Recomandari: Comanda mai multe unitati, optimizeaza aprovizionarea si monitorizeaza trendurile de vanzare.";
        } else {
            return "**Stoc echilibrat**: Intrarile si iesirile sunt aproape egale. 
        Recomandari: Mentine aceeasi strategie de aprovizionare.";
        }
    }

    /**
     * Media zilnică a vânzărilor în luna curentă
     */
    private function getDailySalesAvg(): float
    {
        $totalSales = OrderProduct::whereHas('order', function ($query) {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        })
            ->sum('quantity');

        return round($totalSales / now()->day);
    }

    /**
     * Media săptămânală a vânzărilor în luna curentă
     */
    private function getWeeklySalesAvg(): float
    {
        $totalSales = OrderProduct::whereHas('order', function ($query) {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        })
            ->sum('quantity');

        return round($totalSales / (now()->weekOfMonth ?: 1));
    }

    /**
     * Timp mediu până la epuizarea unui produs
     * Calculează numărul mediu de zile până când produsele rămân fără stoc, 
     * pe baza ratei zilnice de vânzare din ultima lună.
     */
    private function getAvgDaysToOutOfStock(): float
    {
        $products = Product::where('stock', '>', 0)->get();

        if ($products->isEmpty()) {
            return 0;
        }

        $daysToOutOfStock = $products->map(function ($product) {
            // Calculăm numărul total de unități vândute în ultima lună
            $totalSold = OrderProduct::where('product_id', $product->id)
                ->whereHas('order', function ($query) {
                    $query->whereBetween('created_at', [now()->subMonth(), now()]);
                })
                ->sum('quantity');

            // Calculăm rata zilnică de vânzare (vânzări totale / 30 de zile)
            $dailySalesRate = $totalSold / 30;

            return $dailySalesRate > 0 ? round($product->stock / $dailySalesRate, 2) : null;
        })->filter();

        return round($daysToOutOfStock->avg() ?? 0);
    }


}