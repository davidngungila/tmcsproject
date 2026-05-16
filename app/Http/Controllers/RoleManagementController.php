<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleManagementController extends Controller
{
    /**
     * Display Roles and Permissions.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');
        return view('settings.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Create a new role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => strtolower($request->name)]);
        
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return back()->with('success', 'Role created successfully.');
    }

    /**
     * Update role permissions.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return back()->with('success', "Permissions for {$role->name} updated.");
    }

    /**
     * Clone an existing role.
     */
    public function clone(Role $role)
    {
        $newRole = Role::create(['name' => $role->name . '_copy_' . time()]);
        $newRole->permissions()->attach($role->permissions->pluck('id'));

        return back()->with('success', "Role {$role->name} cloned successfully.");
    }

    /**
     * Delete a role.
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role assigned to users.');
        }

        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }
}
