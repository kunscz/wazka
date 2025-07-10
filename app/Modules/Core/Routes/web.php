<?php

use App\Modules\Core\Http\Controllers\UserController;
use App\Modules\Core\Http\Controllers\MenuController;
use App\Modules\Core\Models\Menu;
use App\Modules\Core\Models\Permission;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('core')->name('core.')->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
});

Route::get('/api/menus', [MenuController::class, 'tree'])->name('api.menus');
Route::get('/menus', [MenuController::class, 'index'])->name('menus.index'); // Inertia
Route::get('/api/menus/tree', [MenuController::class, 'tree']);
Route::post('/api/menus', [MenuController::class, 'store']);
Route::put('/api/menus/{menu}', [MenuController::class, 'update']);
Route::delete('/api/menus/{menu}', [MenuController::class, 'destroy']);

Route::get('/api/permissions', fn () => Permission::pluck('name'));
Route::post('/api/menus/{menu}/attach-permission', function (Request $request, Menu $menu) {
    $permission = Permission::firstOrCreate(['name' => $request->input('permission')]);
    $menu->permissions()->syncWithoutDetaching([$permission->id]);
    return response()->json(['status' => 'attached']);
});