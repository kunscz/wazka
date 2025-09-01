<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        // $roles = Role::with('permissions')->get();

        // return response()->json($roles);
        return Inertia::render('Core/Roles/RolePage', [
                'roles' => Role::with('permissions')->get()->map(fn ($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                ]),
                // 'roles' => $roles,
                'permissions' => \App\Modules\Core\Models\Permission::all()->map(function ($perm) {
                    return [
                        'id' => $perm->id,
                        'name' => $perm->name,
                        'label' => ucwords(str_replace('.', ' ', $perm->name))
                    ];
                }),
            ]
        );
    }

    public function getRolesPermissions()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'guard_name' => 'string',
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => $data['guard_name'] ?? 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        return response()->json(['message' => 'Role created', 'role' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return response()->json(['message' => 'Role updated', 'role' => $role]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'numeric|exists:permissions,id',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return response()->json(['message' => 'Permissions synced', 'role' => $role]);
    }
}
