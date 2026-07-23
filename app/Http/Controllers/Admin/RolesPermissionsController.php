<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermissionsController extends Controller
{
    /**
     * Helper to check if current user is Super Admin
     */
    protected function isSuperAdmin()
    {
        $user = auth()->user();
        if (!$user) return false;

        if (!empty($user->is_super_admin) || $user->id == 1 || in_array($user->type ?? '', ['super_admin', 'super admin']) || in_array($user->role ?? '', ['super_admin', 'super admin'])) {
            return true;
        }

        if (method_exists($user, 'hasAnyRole')) {
            if ($user->hasAnyRole(['super_admin', 'super admin', 'Super Admin', 'super-admin'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Helper to enforce page access permissions for non-Super-Admins
     */
    protected function checkPageAccess()
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Check if user has permission to manage or view roles & permissions
        $hasAccess = false;
        if (method_exists($user, 'can')) {
            $hasAccess = $user->can('roles.manage') || $user->can('roles_permissions.view') || $user->can('roles.view');
        }

        if (!$hasAccess) {
            abort(403, 'You do not have permission to view or manage Roles & Permissions.');
        }
    }

    /**
     * Helper to get allowed role names for current authenticated user.
     */
    protected function getAllowedRoleNames()
    {
        $allRoles = Role::pluck('name')->toArray();

        // Super Admin can manage ALL roles
        if ($this->isSuperAdmin()) {
            return $allRoles;
        }

        // Standard Admin can ONLY manage sub-roles (exclude Super Admin & Admin)
        $restrictedRoles = ['super admin', 'super_admin', 'Super Admin', 'super-admin', 'admin', 'Admin'];
        
        return array_values(array_filter($allRoles, function ($roleName) use ($restrictedRoles) {
            return !in_array(strtolower(trim($roleName)), array_map('strtolower', $restrictedRoles));
        }));
    }

    public function index()
    {
        $this->checkPageAccess();

        $isSuperAdmin = $this->isSuperAdmin();
        $allowedRoleNames = $this->getAllowedRoleNames();

        $permissions = Permission::all();

        // Roles visible & manageable by the logged in user
        $roles = Role::whereIn('name', $allowedRoleNames)->with('permissions')->get();

        // Users visible & manageable by the logged in user
        if ($isSuperAdmin) {
            $users = User::with(['roles.permissions', 'permissions'])->get();
        } else {
            // Hide Super Admins and Admins from standard Admin view
            $restrictedRoles = ['super admin', 'super_admin', 'Super Admin', 'super-admin', 'admin', 'Admin'];
            $users = User::whereHas('roles', function ($q) use ($allowedRoleNames) {
                $q->whereIn('name', $allowedRoleNames);
            })->orWhereDoesntHave('roles')->with(['roles.permissions', 'permissions'])->get();

            // Filter out any user who has any restricted role
            $users = $users->reject(function ($u) use ($restrictedRoles) {
                if ($u->id == 1 || !empty($u->is_super_admin)) return true;
                return $u->roles->contains(function ($r) use ($restrictedRoles) {
                    return in_array(strtolower(trim($r->name)), array_map('strtolower', $restrictedRoles));
                });
            });
        }

        return view('admin.roles_permissions', compact('permissions', 'roles', 'users', 'allowedRoleNames', 'isSuperAdmin'));
    }

    // Permission CRUD
    public function storePermission(Request $request)
    {
        $this->checkPageAccess();

        $request->validate(['name' => 'required|unique:permissions,name']);
        Permission::create(['name' => $request->name]);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Permission created successfully.');
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $this->checkPageAccess();

        $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $request->name]);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Permission updated successfully.');
    }

    public function deletePermission(Permission $permission)
    {
        $this->checkPageAccess();

        $permission->delete();
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Permission deleted successfully.');
    }

    // Role CRUD
    public function storeRole(Request $request)
    {
        $this->checkPageAccess();

        $allowedRoles = $this->getAllowedRoleNames();
        if (!$this->isSuperAdmin()) {
            $restrictedRoles = ['super admin', 'super_admin', 'Super Admin', 'super-admin', 'admin', 'Admin'];
            if (in_array(strtolower(trim($request->name)), array_map('strtolower', $restrictedRoles))) {
                return back()->with('error', 'You are not authorized to create or manage the "' . $request->name . '" role.');
            }
        }

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
        $this->checkPageAccess();

        if (!$this->isSuperAdmin()) {
            $restrictedRoles = ['super admin', 'super_admin', 'Super Admin', 'super-admin', 'admin', 'Admin'];
            if (in_array(strtolower(trim($role->name)), array_map('strtolower', $restrictedRoles))) {
                return back()->with('error', 'You do not have permission to modify the "' . $role->name . '" role.');
            }
        }

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
        $this->checkPageAccess();

        if (!$this->isSuperAdmin()) {
            $restrictedRoles = ['super admin', 'super_admin', 'Super Admin', 'super-admin', 'admin', 'Admin'];
            if (in_array(strtolower(trim($role->name)), array_map('strtolower', $restrictedRoles))) {
                return back()->with('error', 'You do not have permission to delete the "' . $role->name . '" role.');
            }
        }

        $role->delete();
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Role deleted successfully.');
    }

    // Assign roles to user
    public function updateUserRoles(Request $request, User $user)
    {
        $this->checkPageAccess();

        $targetRoles = $request->roles ?? [];

        if (!$this->isSuperAdmin()) {
            $restrictedRoles = ['super admin', 'super_admin', 'Super Admin', 'super-admin', 'admin', 'Admin'];
            foreach ($targetRoles as $roleName) {
                if (in_array(strtolower(trim($roleName)), array_map('strtolower', $restrictedRoles))) {
                    return back()->with('error', 'You cannot assign restricted role "' . $roleName . '".');
                }
            }
        }

        $user->syncRoles($targetRoles);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'User roles updated successfully.');
    }

    // Assign custom direct permissions to user
    public function updateUserPermissions(Request $request, User $user)
    {
        $this->checkPageAccess();

        if (!$this->isSuperAdmin()) {
            $restrictedRoles = ['super admin', 'super_admin', 'Super Admin', 'super-admin', 'admin', 'Admin'];
            $userHasRestricted = $user->roles->contains(function ($r) use ($restrictedRoles) {
                return in_array(strtolower(trim($r->name)), array_map('strtolower', $restrictedRoles));
            });
            if ($userHasRestricted || $user->id == 1) {
                return back()->with('error', 'You cannot modify custom permissions for this user.');
            }
        }

        $user->syncPermissions($request->permissions ?? []);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('success', 'Direct custom permissions for user updated successfully.');
    }
}