<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::with('permissions')->get();
        $users = User::with('roles')->get();
        return view('admin.roles_permissions', compact('permissions', 'roles', 'users'));
    }

    // Permission CRUD
    public function storePermission(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        Permission::create(['name' => $request->name]);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Permission created successfully.');
    }
    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $request->name]);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Permission updated successfully.');
    }
    public function deletePermission(Permission $permission)
    {
        $permission->delete();
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Permission deleted successfully.');
    }

    // Role CRUD
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Role created successfully.');
    }
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Role updated successfully.');
    }
    public function deleteRole(Role $role)
    {
        $role->delete();
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Role deleted successfully.');
    }

    // Assign roles to user
    public function updateUserRoles(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'User roles updated successfully.');
    }
} 