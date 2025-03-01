<?php

namespace App\Http\Controllers;

use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShoppingCenterController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

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

        $activeOrders = clone $ordersQuery;
        $archivedOrders = clone $ordersQuery;

        $archivedOrders = $archivedOrders->where('is_archived', 1)->paginate(3)->through(function ($order) {
            return [
                'id' => $order->id,
                'total' => $order->total_price,
                'date' => $order->created_at->format('j M Y'),
                'invoice_url' => $order->invoice_url,
                'details' => $order->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'price' => $product->pivot->price,
                        'quantity' => $product->pivot->quantity,
                    ];
                }),
            ];
        });
        $activeOrders = $activeOrders->where('is_archived', 0)->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'total' => $order->total_price,
                'date' => $order->created_at->format('j M Y'),
                'invoice_url' => $order->invoice_url,
                'status' => $order->status, 
                'details' => $order->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'price' => $product->pivot->price,
                        'quantity' => $product->pivot->quantity,
                    ];
                }),
            ];
        })->toArray();

        $discounts= $this->discountService->getAvailableDiscounts($user);
        return Inertia::render('User/UserDashboard/ShoppingCenter/Index', [
            'orders' => $archivedOrders,
            'activeOrders' => $activeOrders,
            'prevFilters' => $filters,
            'discounts'=>$discounts
        ]);
    }
}
