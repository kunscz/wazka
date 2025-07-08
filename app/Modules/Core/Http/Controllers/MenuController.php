<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('permissions', 'children')->whereNull('parent_id')->get();
        return response()->json($menus);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string',
            'icon' => 'nullable|string',
            'route_name' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'permission_ids' => 'array',
        ]);

        $menu = Menu::create($data);
        $menu->permissions()->sync($data['permission_ids'] ?? []);

        return response()->json(['message' => 'Menu created', 'menu' => $menu]);
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'label' => 'required|string',
            'icon' => 'nullable|string',
            'route_name' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'permission_ids' => 'array',
        ]);

        $menu->update($data);
        $menu->permissions()->sync($data['permission_ids'] ?? []);

        return response()->json(['message' => 'Menu updated', 'menu' => $menu]);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu deleted']);
    }

}
