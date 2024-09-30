<?php

namespace App\Http\Controllers;

use App\Enums\CityRomania;
use App\Http\Requests\CreateAccountRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AdminAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
                $roleQuery->where('name', 'like', '%' . $filters['searchRole'] . '%');
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Admin/Accounts/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAccountRequest $request)
    {
        $validated = $request->validated();
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->save();
        $user->roles()->attach($validated['role_id']);

        return redirect()->route('admin.accounts.index')->with('success', 'User created successfully!');

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
    public function edit(string $accountId)
    {
        $account = User::with('roles')->findOrFail($accountId);
        $roles = Role::all();
    
        $userRoles = $account->roles->pluck('id')->toArray(); 
    
        $userDetails = [
            'id' => $account->id,
            'name' => $account->name,
            'email' => $account->email,
            'role_id' => $userRoles[0], 
        ];
    
        return Inertia::render('Admin/Accounts/Edit', [
            'user' => $userDetails, 
            'roles' => $roles,
            'userRole' => $userRoles[0], 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request  $request, string $accountId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($accountId), 
            ],
        ]);

        $user = User::findOrFail($accountId);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->save();

        $user->roles()->sync($validated['role_id']); 

        return redirect()->route('admin.accounts.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account deleted successfully!');
    }
    
}
