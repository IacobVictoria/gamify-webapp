<?php

namespace App\Services;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;

class DashboardService
{
    public function getUserDashboardData()
    {
        return [];
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
        ];
    }
}
