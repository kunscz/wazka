<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Modules\Core\Models\Menu;
use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $dashboardPermission = Permission::create(['name' => 'view dashboard']);
        $userManagementPermission = Permission::create(['name' => 'manage users']);
        $menuPermission = Permission::create(['name' => 'manage menus']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([$dashboardPermission, $userManagementPermission, $menuPermission]);
        $userRole->givePermissionTo([$dashboardPermission]);

        // Create user
        $admin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@example.com']);
        $admin->assignRole($adminRole);

        // Create menus
        $dashboardMenu = Menu::create([
            'label' => 'Dashboard',
            'route_name' => 'dashboard',
            'icon' => 'mdi-view-dashboard',
            'sort_order' => 1,
        ]);

        $userMenu = Menu::create([
            'label' => 'Users',
            'route_name' => 'users.index',
            'icon' => 'mdi-account-multiple',
            'sort_order' => 2,
        ]);

        // Attach permissions to menus
        $dashboardMenu->permissions()->attach($dashboardPermission->id);
        $userMenu->permissions()->attach($userManagementPermission->id);

        $permission = Permission::updateOrCreate(
            ['name' => 'manage users'],
            ['description' => 'Allows full control over user management']
        );
    }
}
