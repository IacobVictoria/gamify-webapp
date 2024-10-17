<?php

namespace App\Services;
use App\Models\Badge;
use App\Models\Permission;
use App\Models\Product;
use App\Models\QrCodeScan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getUserDashboardData()
    {
        $user = Auth::user();

        $numberOfOrders = $user->orders()->count();

        $orderProducts = $user->orders()
            ->with('products')
            ->get();

        $topProducts = $orderProducts
            ->flatMap(function ($order) {
                return $order->products;
            })
            ->groupBy('id')
            ->map(function ($group) {
                return [
                    'name' => $group->first()->name,
                    'quantity' => $group->sum('pivot.quantity'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(3)
            ->values();

        $chartData = [
            'labels' => $topProducts->pluck('name')->toArray(),
            'data' => $topProducts->pluck('quantity')->toArray(),
        ];

        //evolution with scores and scans

        $scans = QrCodeScan::where('user_id', $user->id)
            ->with('product')
            ->get();
        $scores = $scans->groupBy(function ($scan) {
            return \Carbon\Carbon::parse($scan->scanned_at)->format('Y-m-d');
        })
            ->map(function ($scans) {
                return $scans->sum(function ($scan) {
                    return $scan->product->score;
                });
            });

        $scoreEvolution = $scores->map(function ($score, $date) {
            return [
                'date' => $date,
                'score' => $score,
            ];
        })->values();

        return [
            'numberOfOrders' => $numberOfOrders,
            'topProducts' => $topProducts,
            'chartData' => $chartData,
            'scoreEvolution' => $scoreEvolution

        ];
    }

    public function getAdminDashboardData()
    {
        return [
            'accounts' => User::orderBy('created_at', 'desc')->take(5)->get(),
            'accountsNumber' => User::all()->count(),
            'roles' => Role::take(5)->get(),
            'rolesNumber' => Role::all()->count(),
            'permissions' => Permission::orderBy('id', 'desc')->take(5)->get(),
            'permissionsNumber' => Permission::all()->count(),
            'products' => Product::orderBy('created_at', 'desc')->take(5)->get(),
            'productsNumber' => Product::all()->count(),
            'badges' => Badge::all(),
            'badgesNumber' => Badge::all()->count(),
        ];
    }
}
