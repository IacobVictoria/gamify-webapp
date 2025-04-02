<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuperAdminAccountUpdateRequest;
use App\Http\Requests\SuperAdminCreateAccountRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AdminAccountController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);

        $usersQuery = User::query()->with('roles'); //incarci in acelasi timp si rolurile userilor

        if (isset($filters['searchName'])) {
            $usersQuery->where('name', 'like', '%' . $filters['searchName'] . '%');
        }

        if (isset($filters['searchEmail'])) {
            $usersQuery->where('email', 'like', '%' . $filters['searchEmail'] . '%');
        }

        if (isset($filters['searchRole'])) {
            $usersQuery->whereHas('roles', function ($roleQuery) use ($filters) {
                $roleQuery->where('name', $filters['searchRole']);
            });
        }

        $users = $usersQuery->paginate(10)->through(function ($user) {
            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'roles' => $user->roles->pluck('name')->implode(', '),
                'created_at' => $user->created_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('Admin/Accounts/List', [
            'accounts' => $users,
            'roles' => Role::all(),
            'prevFilters' => $filters,
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Admin/Accounts/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(SuperAdminCreateAccountRequest $request)
    {
        $validated = $request->validated();
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->save();
        $user->roles()->attach($validated['role_ids']);

        return redirect()->route('super-admin.accounts.index')->with('success', 'User created successfully!');

    }

    public function edit(string $accountId)
    {
        $account = User::with('roles')->findOrFail($accountId);

        $roles = Role::all();

        $userRoles = $account->roles->pluck('id')->toArray();

        $userDetails = [
            'id' => $account->id,
            'name' => $account->name,
            'email' => $account->email,
            'role_ids' => $userRoles,
        ];

        return Inertia::render('Admin/Accounts/Edit', [
            'account' => $userDetails,
            'roles' => $roles,
        ]);
    }

    public function update(SuperAdminAccountUpdateRequest $request, string $accountId)
    {
        $validated = $request->validated();

        $user = User::findOrFail($accountId);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->roles()->sync($validated['role_ids']);

        return redirect()->route('super-admin.accounts.index')->with('success', 'User updated successfully!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('super-admin.accounts.index')
            ->with('success', 'Account deleted successfully!');
    }


}
