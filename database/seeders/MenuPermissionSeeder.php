<?php

// php artisan db:seed --class=MenuPermissionSeeder

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Modules\Core\Models\Menu as ModelsMenu;

class MenuPermissionSeeder extends Seeder
{
    public function run()
    {
        $menus = ModelsMenu::all(); // or fetch your tree structure

        foreach ($menus as $menu) {
            $base = strtolower(str_replace(' ', '_', $menu->label));

            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate([
                    'name' => "{$base}.{$action}"
                ]);
            }
        }
    }
}