<?php

namespace App\Services\Reports;

use App\Helpers\PeriodHelper;
use App\Models\InventoryTransaction;
use App\Models\OrderProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesStockReportService
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
            'top_10_sold_products' => $this->getTopSoldProducts($startDate, $endDate),
            'least_sold_products' => $this->getLeastSoldProducts($startDate, $endDate),
            'stock_fluctuations' => $this->getStockFluctuations($startDate, $endDate),
            'daily_sales_avg' => round($this->getDailySalesAvg($startDate, $endDate), 2),
            'weekly_sales_avg' => round($this->getWeeklySalesAvg($startDate, $endDate), 2),
            'avg_days_to_out_of_stock' => round($this->getAvgDaysToOutOfStock($startDate, $endDate), 2),
        ];
    }

    /**
     * Top 10 produse vândute lunar
     */
    private function getTopSoldProducts($startDate, $endDate): array
    {
        return OrderProduct::select('product_id', Db::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) use($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
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
    private function getLeastSoldProducts($startDate, $endDate): array
    {
        return OrderProduct::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) use($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
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

    private function getStockFluctuations($startDate, $endDate): array
    {
        return InventoryTransaction::select(
            'product_id',
            DB::raw('SUM(CASE WHEN transaction_type = "IN" THEN quantity ELSE 0 END) as total_in'),
            DB::raw('SUM(CASE WHEN transaction_type = "OUT" THEN quantity ELSE 0 END) as total_out'),
            DB::raw('ABS(SUM(CASE WHEN transaction_type = "IN" THEN quantity ELSE 0 END) - 
                     SUM(CASE WHEN transaction_type = "OUT" THEN quantity ELSE 0 END)) as stock_variation')
        )
            ->whereBetween('transaction_date', [$startDate, $endDate])
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
    private function getDailySalesAvg($startDate, $endDate): float
    {
        $totalSales = OrderProduct::whereHas('order', function ($query) use($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->sum('quantity');

        return round($totalSales / now()->day);
    }

    /**
     * Media săptămânală a vânzărilor în luna curentă
     */
    private function getWeeklySalesAvg($startDate, $endDate): float
    {
        $totalSales = OrderProduct::whereHas('order', function ($query) use($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->sum('quantity');

        return round($totalSales / (now()->weekOfMonth ?: 1));
    }

    /**
     * Timp mediu până la epuizarea unui produs
     * Calculează numărul mediu de zile până când produsele rămân fără stoc, 
     * pe baza ratei zilnice de vânzare din ultima lună.
     */
    private function getAvgDaysToOutOfStock($startDate, $endDate): float
    {
        $products = Product::where('stock', '>', 0)->get();

        if ($products->isEmpty()) {
            return 0;
        }

        $daysToOutOfStock = $products->map(function ($product) use($startDate, $endDate) {
            // Calculăm numărul total de unități vândute în ultima lună
            $totalSold = OrderProduct::where('product_id', $product->id)
                ->whereHas('order', function ($query) use($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->sum('quantity');

            // Calculăm rata zilnică de vânzare (vânzări totale / 30 de zile)
            $dailySalesRate = $totalSold / 30;

            return $dailySalesRate > 0 ? round($product->stock / $dailySalesRate, 2) : null;
        })->filter();

        return round($daysToOutOfStock->avg() ?? 0);
    }


}