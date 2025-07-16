<?php

namespace App\Modules\Core\Database\Seeders;

use App\Modules\Core\Models\Menu;
use App\Modules\Core\Models\Permission;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
   public function run(): void
   {
      // php artisan db:seed --class="Modules\\Core\\Database\\Seeders\\RoleSeeder"
      $perm = Permission::firstOrCreate(['name' => 'manage_menus']);

      $manager = Menu::updateOrCreate(
         ['label' => 'Menu Manager'],
         [
            'icon' => 'LayoutGrid',
            'route_name' => 'core.menus.index',
            'parent_id' => null,
            'sort_order' => 99,
            'is_active' => true,
         ]
      );

      $manager->permissions()->syncWithoutDetaching([$perm->id]);
   }
}