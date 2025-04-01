<?php

namespace App\Services\DashboardRolesType;

use App\Models\Role;
use App\Models\User;

class SuperAdminDashboardService
{
    public function getDashboardData()
    {
        $totalAccounts = User::count();
        $totalRoles = Role::count();

        $accountsThisWeek = User::where('created_at', '>=', now()->startOfWeek())->count();
        $accountsLastWeek = User::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();

        $growth = $accountsLastWeek === 0
            ? 100
            : round((($accountsThisWeek - $accountsLastWeek) / $accountsLastWeek) * 100);

        $rolesWithUsers = Role::with([
            'users' => function ($query) {
                $query->latest()->take(5);
            }
        ])->get()->map(fn($role) => [
                'role' => $role->name,
                'users' => $role->users->map(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]),
            ]);

        $latestAccounts = User::latest()->take(10)->get(['id', 'name', 'email', 'created_at']);

        return [
            'toggleSuperAdmin' => true,
            'toggleAdmin' => false,
            'accounts_count' => $totalAccounts,
            'roles_count' => $totalRoles,
            'accounts_this_week' => $accountsThisWeek,
            'accounts_growth_percent' => $growth,
            'roles_with_users' => $rolesWithUsers,
            'latest_accounts' => $latestAccounts,
        ];
    }
}