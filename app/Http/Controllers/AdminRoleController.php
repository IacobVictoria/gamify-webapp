<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminRoleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);

        $rolesQuery = Role::query();

        if (isset($filters['searchName'])) {
            $rolesQuery->where('name', 'like', '%' . $filters['searchName'] . '%');
        }

        $roles = $rolesQuery->orderBy('created_at', 'desc')->paginate(10)->through(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'created_at' => $role->created_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('SuperAdmin/Roles/Index', [
            'roles' => $roles,
            'prevFilters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('SuperAdmin/Roles/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'id' => Uuid::uuid(),
            'name' => $validated['name'],
        ]);

        return redirect()->route('super-admin.roles.index')->with('success', 'Role created successfully!');
    }

    public function edit(string $roleId)
    {
        $role = Role::findOrFail($roleId);

        return Inertia::render('SuperAdmin/Roles/Edit', [
            'role' => $role,
        ]);
    }

    public function update(Request $request, string $roleId)
    {
        $role = Role::findOrFail($roleId);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $roleId,
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('super-admin.roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy(string $roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();

        return redirect()->route('super-admin.roles.index')->with('success', 'Role deleted successfully!');
    }
}
