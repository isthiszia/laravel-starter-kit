<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('access.index', compact('roles', 'permissions'));
    }

    public function permissionStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $permissions = array_filter(
            array_map('trim', explode(',', $request->name))
        );

        $created = [];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);

            $created[] = $permission;
        }

        return response()->json([
            'status' => true,
            'message' => count($created).' permission(s) created successfully.',
            'permissions' => $created,
        ]);
    }

    public function permissionDestroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'status' => true,
            'message' => 'Permission deleted successfully.',
        ]);
    }

    public function permissionUpdate(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);

        if ($role->hasPermissionTo($permission->name)) {
            $role->revokePermissionTo($permission->name);
            $action = 'removed';
        } else {
            $role->givePermissionTo($permission->name);
            $action = 'assigned';
        }

        return response()->json([
            'status' => true,
            'message' => "Permission {$action} successfully.",
        ]);
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $roles = array_filter(
            array_map('trim', explode(',', $request->name))
        );
        $created = [];
        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
            $created[] = $role;
        }

        return response()->json([
            'status' => true,
            'message' => count($created).' role(s) created successfully.',
            'roles' => $created,
        ]);
    }

    public function roleDestroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return response()->json([
                'status' => false,
                'message' => 'Super Admin role cannot be deleted.',
            ], 422);
        }
        $role->delete();

        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully.',
        ]);
    }
}
