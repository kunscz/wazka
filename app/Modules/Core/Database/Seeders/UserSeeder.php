<?php

namespace App\Modules\Core\Database\Seeders;

use App\Modules\Core\Models\Menu;
use App\Modules\Core\Models\Permission;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get permissions
        $permissions = [
            'view_dashboard',
            'view_users',
            'edit_users',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        // Dashboard menu
        $dashboard = Menu::updateOrCreate(
            ['label' => 'Dashboard'],
            [
                'icon' => 'LayoutGrid',
                'route_name' => 'dashboard',
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );
        $dashboard->permissions()->sync(Permission::whereIn('name', ['view_dashboard'])->pluck('id'));

        // Users Group
        $usersGroup = Menu::updateOrCreate(
            ['label' => 'Users'],
            [
                'icon' => 'Folder',
                'route_name' => null,
                'parent_id' => null,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $usersIndex = Menu::updateOrCreate(
            ['label' => 'All Users'],
            [
                'icon' => 'BookOpen',
                'route_name' => 'core.users.index',
                'parent_id' => $usersGroup->id,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );
        $usersIndex->permissions()->sync(Permission::whereIn('name', ['view_users'])->pluck('id'));

        $usersEdit = Menu::updateOrCreate(
            ['label' => 'Edit Users'],
            [
                'icon' => 'BookOpen',
                'route_name' => 'core.users.edit',
                'parent_id' => $usersGroup->id,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );
        $usersEdit->permissions()->sync(Permission::whereIn('name', ['edit_users'])->pluck('id'));
    }
}