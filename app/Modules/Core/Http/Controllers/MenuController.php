<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Menu;
use App\Modules\Core\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function __construct(protected MenuService $menus) {}

    public function index()
    {
        return Inertia::render('Core/Menus/Index');
    }

    public function tree()
    {
        return response()->json($this->menus->getTree());
    }

    public function store(Request $request)
    {
        $menu = $this->menus->createFromRequest($request);
        $this->menus->syncPermission($menu);

        return response()->json($menu->load('permissions'));
    }

    public function update(Request $request, Menu $menu)
    {
        $menu = $this->menus->updateFromRequest($request, $menu);
        $this->menus->syncPermission($menu);

        return response()->json($menu->load('permissions'));
    }

    public function destroy(Menu $menu)
    {
        $this->menus->delete($menu);
        return response()->json(['message' => 'Menu deleted']);
    }
}