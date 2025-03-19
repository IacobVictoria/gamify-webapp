<?php

namespace App\Http\Controllers;

use App\Models\SupplierOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSupplierOrderController extends Controller
{

    public function index(Request $request)
    {
        $filters = $request->input('filters', []);

        $ordersQuery = SupplierOrder::with(['supplier', 'products', 'report']);

        if (isset($filters['name'])) {
            $ordersQuery->whereHas('supplier', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }

        if (!empty($filters['sortDate'])) {
            $ordersQuery->orderBy('order_date', $filters['sortDate'] === 'asc' ? 'asc' : 'desc');
        } else {
            $ordersQuery->orderByDesc('order_date');
        }


        $orders = $ordersQuery->paginate(10)->through(function ($order) {
            $count = $order->products->count();
            $total = $order->total_price;
            return [
                'id' => $order->id,
                'name' => $order->supplier->name,
                'id_person' => $order->supplier->id,
                'order_date' => Carbon::parse($order->order_date)->format('j M Y'),
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

        return Inertia::render('Admin/Supplier_Orders/List', [
            'orders' => $orders,
            'prevFilters' => $filters
        ]);
    }
}
