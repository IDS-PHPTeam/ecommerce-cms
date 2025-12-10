<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Traits\LogsAudit;

class RolePermissionController extends Controller
{
    use LogsAudit;
    /**
     * Display roles and permissions management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        
        return view('admins.roles-permissions', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($validated);

        $this->logAudit('created', $role, "Role created: {$role->name}");

        return redirect()->route('roles-permissions.index')
            ->with('success', __('cms.role_created_successfully'));
    }

    /**
     * Update the specified role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $oldValues = $this->getOldValues($role, ['name', 'slug', 'description']);
        $role->update($validated);
        $newValues = $this->getNewValues($validated, ['name', 'slug', 'description']);

        $this->logAudit('updated', $role, "Role updated: {$role->name}", $oldValues, $newValues);

        return redirect()->route('roles-permissions.index')
            ->with('success', __('cms.role_updated_successfully'));
    }

    /**
     * Remove the specified role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyRole(Role $role)
    {
        $roleName = $role->name;

        $this->logAudit('deleted', $role, "Role deleted: {$roleName}");

        $role->users()->detach();
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('roles-permissions.index')
            ->with('success', __('cms.role_deleted_successfully'));
    }

    /**
     * Store a newly created permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'description' => 'nullable|string',
            'group' => 'nullable|string|max:255',
        ]);

        $permission = Permission::create($validated);

        $this->logAudit('created', $permission, "Permission created: {$permission->name}");

        return redirect()->route('roles-permissions.index')
            ->with('success', __('cms.permission_created_successfully'));
    }

    /**
     * Update the specified permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string',
            'group' => 'nullable|string|max:255',
        ]);

        $oldValues = $this->getOldValues($permission, ['name', 'slug', 'description', 'group']);
        $permission->update($validated);
        $newValues = $this->getNewValues($validated, ['name', 'slug', 'description', 'group']);

        $this->logAudit('updated', $permission, "Permission updated: {$permission->name}", $oldValues, $newValues);

        return redirect()->route('roles-permissions.index')
            ->with('success', __('cms.permission_updated_successfully'));
    }

    /**
     * Remove the specified permission.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPermission(Permission $permission)
    {
        $permissionName = $permission->name;

        $this->logAudit('deleted', $permission, "Permission deleted: {$permissionName}");

        $permission->roles()->detach();
        $permission->delete();

        return redirect()->route('roles-permissions.index')
            ->with('success', __('cms.permission_deleted_successfully'));
    }

    /**
     * Update permissions for a role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $oldPermissionIds = $role->permissions->pluck('id')->toArray();
        $role->permissions()->sync($request->permissions ?? []);
        $newPermissionIds = $request->permissions ?? [];

        $this->logAudit('updated', $role, "Role permissions updated for: {$role->name}", 
            ['permissions' => $oldPermissionIds], 
            ['permissions' => $newPermissionIds]);

        return redirect()->route('roles-permissions.index')
            ->with('success', __('cms.role_permissions_updated_successfully'));
    }
}

