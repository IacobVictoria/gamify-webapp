<?php

namespace App\Http\Controllers;

use App\Models\SupplierOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSupplierOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);

        $ordersQuery = SupplierOrder::with(['supplier', 'products']);

        if (isset($filters['name'])) {
            $ordersQuery->whereHas('supplier', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }
        $orders = $ordersQuery->paginate(10)->through(function ($order) {
            $count = $order->products->count();
            $total = $order->total_price;
            return [
                'id' => $order->id,
                'name' => $order->supplier->name,
                'id_person' => $order->supplier->id,
                'date' => $order->created_at->format('j M Y'),
                'total_price' => $total,
                'extra' => [
                    'total_products' => $count,
                    'total_price' => $total
                ],
                'details' => $order->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'price' => $product->pivot->price,
                        'quantity' => $product->pivot->quantity,
                    ];
                }),
            ];
        });

        return Inertia::render('Admin/Supplier_Orders/List', [
            'orders' => $orders,
            'prevFilters' => $filters
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
