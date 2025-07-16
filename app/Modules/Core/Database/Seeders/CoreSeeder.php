<?php

namespace App\Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Modules\Core\Models\Menu;
use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;

class CoreSeeder extends Seeder
{
    public function run(): void
    {
        // 🛡 Create permissions
        $viewUsers = Permission::firstOrCreate(['name' => 'view users']);
        $viewRoles = Permission::firstOrCreate(['name' => 'view roles']);
        $manageMenus = Permission::firstOrCreate(['name' => 'manage menus']);

        // 🧩 Create role and attach permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([$viewUsers, $viewRoles, $manageMenus]);

        // 👤 Create user and assign role
        $user = User::firstOrCreate([
            'email' => 'admin@wazka.test',
        ], [
            'name' => 'Wazka Admin',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($admin);
        $user->syncPermissions([$viewUsers, $viewRoles, $manageMenus]);

        // Dashboard
         $dashboardMenu = Menu::create([
            'label' => 'Dashboard',
            'route_name' => 'dashboard',
            'icon' => 'LayoutGrid',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => null,
        ]);

        // 🌲 Create root menu
        $coreMenu = Menu::create([
            'label' => 'Core',
            'route_name' => null,
            'icon' => 'Settings',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => null,
        ]);

        // 🔄 Child menus
        $children = [
            [
                'label' => 'Users',
                'route_name' => 'core.users.page',
                'icon' => 'User',
                'sort_order' => 1,
                'is_active' => true,
                'permissions' => [$viewUsers->id],
            ],
            [
                'label' => 'Roles',
                'route_name' => 'core.roles.page',
                'icon' => 'Key',
                'sort_order' => 2,
                'is_active' => true,
                'permissions' => [$viewRoles->id],
            ],
            [
                'label' => 'Menus',
                'route_name' => 'core.menus.manage',
                'icon' => 'List',
                'sort_order' => 3,
                'is_active' => true,
                'permissions' => [$manageMenus->id],
                'children' => [
                    [
                        'label' => 'Menu Debug',
                        'route_name' => 'core.menus.debug',
                        'icon' => 'Bug',
                        'sort_order' => 1,
                        'is_active' => true,
                        'permissions' => [$manageMenus->id],
                    ],
                ],
            ],
        ];

        foreach ($children as $data) {
            $menu = Menu::create([
                'label' => $data['label'],
                'route_name' => $data['route_name'],
                'icon' => $data['icon'],
                'sort_order' => $data['sort_order'],
                'is_active' => $data['is_active'],
                'parent_id' => $coreMenu->id,
            ]);

            $menu->permissions()->sync($data['permissions']);

            if (!empty($data['children'])) {
                foreach ($data['children'] as $child) {
                    $subMenu = Menu::create([
                        'label' => $child['label'],
                        'route_name' => $child['route_name'],
                        'icon' => $child['icon'],
                        'sort_order' => $child['sort_order'],
                        'is_active' => $child['is_active'],
                        'parent_id' => $menu->id,
                    ]);

                    $subMenu->permissions()->sync($child['permissions']);
                }
            }
        }
    }
}