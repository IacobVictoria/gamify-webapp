<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminClientOrderController extends Controller
{
 
    public function index(Request $request)
    {
        $ordersQuery = ClientOrder::with(['user', 'products', 'report']);
        $orderBy = $request->input('orderBy', 'created_at');
        $orderDirection = $request->input('orderDirection', 'desc');

        $filters = $request->input('filters', []);

        if (isset($filters['name'])) {
            $ordersQuery->whereHas('user', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }

        if (in_array($orderBy, ['created_at', 'total_price'])) {
            $orderDirection = in_array($orderDirection, ['asc', 'desc']) ? $orderDirection : 'asc';
            $ordersQuery->orderBy($orderBy, $orderDirection);
        }

        $orders = $ordersQuery->paginate(10)->through(function ($order) {
            $count = $order->products->count();
            $total = $order->total_price;
            return [
                'id' => $order->id,
                'name' => $order->user->name,
                'id_person' => $order->user->id,
                'created_at' => $order->created_at->format('j M Y'),
                'total_price' => $total,
                'invoice_url' => $order->report ? $order->report->s3_path : null,
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


        return Inertia::render('Admin/Client_Orders/List', [
            'orders' => $orders,
            'prevFilters' => $filters,
        ]);
    }

}
