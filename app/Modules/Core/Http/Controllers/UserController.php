<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::with('roles', 'permissions')->get();
        // return response()->json($users);
        // $usersComplete = [
        //         'users' => $users,
        //         'roles' => \App\Modules\Core\Models\Role::all()->map(function ($role) {
        //             return [
        //                 'id' => $role->id,
        //                 'name' => $role->name,
        //             ];
        //         }),
        //         'permissions' => \App\Modules\Core\Models\Permission::all()->map(function ($perm) {
        //             return [
        //                 'id' => $perm->id,
        //                 'name' => $perm->name,
        //                 'label' => ucwords(str_replace('.', ' ', $perm->name))
        //             ];
        //         }),
        //     ];
        // dd($usersComplete);
        return Inertia::render('Core/Users/UserPage');
    }

    public function getUsers()
    {
        $users = User::with('roles', 'permissions')->get();
        $usersComplete = [
            'users' => $users,
            'roles' => \App\Modules\Core\Models\Role::all()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }),
            'permissions' => \App\Modules\Core\Models\Permission::all()->map(function ($perm) {
                return [
                    'id' => $perm->id,
                    'name' => $perm->name,
                    'label' => ucwords(str_replace('.', ' ', $perm->name))
                ];
            }),
        ];
        return response()->json($usersComplete);
    }

    public function show(User $user)
    {
        return response()->json($user->load('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        return response()->json(['message' => 'User created', 'user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
        $user->syncRoles($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}