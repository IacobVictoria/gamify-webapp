<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserOrderHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $ordersQuery = $user->orders()->with('products');

        $filters = $request->input('filters', []);
   
        // Sortare
        if (isset($filters['sortTotal'])) {
            $ordersQuery->orderBy('total_price', $filters['sortTotal'] === 'asc' ? 'asc' : 'desc');
        }

        if (isset($filters['sortDate'])) {
            $ordersQuery->orderBy('created_at', $filters['sortDate'] === 'asc' ? 'asc' : 'desc');
        }

        $orders = $ordersQuery->paginate(10)->through(function ($order) {
            return [
                'id' => $order->id,
                'total' => $order->total_price,
                'date' => $order->created_at->format('j M Y'),
                'details' => $order->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'price' => $product->pivot->price, 
                        'quantity' => $product->pivot->quantity, 
                    ];
                }),
            ];
        });

        return Inertia::render('User/Orders/HistoryOrder', [
            'orders' => $orders,
            'prevFilters' => $filters,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
