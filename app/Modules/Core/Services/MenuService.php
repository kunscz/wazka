<?php

namespace App\Modules\Core\Services;

use Illuminate\Http\Request;
use App\Modules\Core\Models\Menu;
use App\Modules\Core\Models\Permission;
use Illuminate\Support\Collection;

class MenuService
{
   /**
    * Build nested menu tree with children
   */
   public function getTree(bool $includeInactive = false): Collection
   {
      // return Menu::with('children')
      //    ->whereNull('parent_id')
      //    ->orderBy('sort_order')
      //    ->get();
      // $menus = app(MenuCacheService::class)->get();

      // if (!$includeInactive) {
      //    $menus = $menus->filter(fn($menu) => $menu->is_active);
      // }
      
      // return $menus;
      return app(MenuCacheService::class)->get();
   }

   /**
    * Create menu from validated request input 
   */
   public function createFromRequest(Request $request): Menu
   {
      $data = $request->validate([
         'label' => 'required|string|max:255',
         'route_name' => 'nullable|string',
         'url' => 'nullable|string',
         'icon' => 'nullable|string',
         'sort_order' => 'nullable|integer',
         'parent_id' => 'nullable|integer|exists:menus,id',
         'is_active' => 'boolean',
         'is_manual' => 'boolean',
      ]);

      $data['icon'] = $data['icon'] ?? 'mdi-file';
      $data['sort_order'] = $data['sort_order'] ?? 99;
      app(MenuCacheService::class)->forget();

      return Menu::create($data);
   }

   /**
    * Update menu with validated input
   */
   public function updateFromRequest(Request $request, Menu $menu): Menu
   {
      $data = $request->validate([
         'label' => 'required|string|max:255',
         'route_name' => 'nullable|string',
         'url' => 'nullable|string',
         'icon' => 'nullable|string',
         'sort_order' => 'nullable|integer',
         'parent_id' => 'nullable|integer|exists:menus,id',
         'is_active' => 'boolean',
         'is_manual' => 'boolean',
      ]);

      app(MenuCacheService::class)->forget();

      $menu->update($data);

      return $menu;
   }

   /**
    * Delete menu safely
   */
   public function deleteFromRequest(Menu $menu)
   {
      app(MenuCacheService::class)->forget();
      $menu->delete($menu);
   }

   public function syncPermission(Menu $menu): void
   {
      if (!$menu->route_name) return;

      // Normalize route into permission
      $segments = explode('.', $menu->route_name);
      if (count($segments) < 2) return;

      $action = $segments[array_key_last($segments)];
      $resource = $segments[count($segments) - 2];
      $label = "{$action} {$resource}";

      $permission = Permission::firstOrCreate([
         'name' => $label,
      ], [
         'description' => "Can {$action} {$resource}",
      ]);

      $menu->permissions()->syncWithoutDetaching([$permission->id]);
   }

}