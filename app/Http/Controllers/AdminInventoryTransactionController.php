<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminInventoryTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryTransaction::query()->with('product');

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [
                date('Y-m-d 00:00:00', strtotime($request->start_date)),
                date('Y-m-d 23:59:59', strtotime($request->end_date))
            ]);
        }

        $products = Product::select('id', 'name')->get();

        // produs selectat, calculez statisticile pentru el
        $selectedProductStats = null;
        if ($request->filled('product_id')) {
            $selectedProductStats = $this->getProductStatistics($request->product_id);
        }

        // calculez totalurile statisticile mari stock IN/OUT
        $totalInStock = InventoryTransaction::where('transaction_type', 'in')->sum('quantity');
        $totalOutStock = InventoryTransaction::where('transaction_type', 'out')->sum('quantity');

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(10);

        return Inertia::render('Admin/InventoryTransaction/Index', [
            'transactions' => $transactions,
            'total_in' => $totalInStock,
            'total_out' => $totalOutStock,
            'products' => $products,
            'selectedProductStats' => $selectedProductStats,
            'filters' => [
                'transaction_type' => $request->transaction_type ?? '',
                'product_id' => $request->product_id ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
            ],
        ]);
    }

    private function getProductStatistics($productId)
    {
        $transactions = InventoryTransaction::where('product_id', $productId)->get();
        $product = Product::findOrFail($productId);
        if ($transactions->isEmpty()) {
            return [
                'name' => Product::find($productId)?->name ?? 'Produs Necunoscut',
                'totalInStock' => 0,
                'totalOutStock' => 0,
                'currentStock' => 0,
                'firstTransaction' => 'N/A',
                'lastTransaction' => 'N/A',
                'totalTransactions' => 0,
            ];
        }

        $totalIn = $transactions->where('transaction_type', 'in')->sum('quantity');
        $totalOut = $transactions->where('transaction_type', 'out')->sum('quantity');
        $currentStock = $product->stock;

        $firstTransaction = $transactions->min('transaction_date');
        $lastTransaction = $transactions->max('transaction_date');

        return [
            'name' => Product::find($productId)->name,
            'totalInStock' => $totalIn,
            'totalOutStock' => $totalOut,
            'currentStock' => $currentStock,
            'firstTransaction' => date('Y-m-d', strtotime($firstTransaction)),
            'lastTransaction' => date('Y-m-d', strtotime($lastTransaction)),
            'totalTransactions' => $transactions->count(),
        ];
    }
    
}
