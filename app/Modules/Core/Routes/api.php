<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Core\Http\Controllers\MenuController;
use App\Modules\Core\Http\Controllers\RoleController;
use App\Modules\Core\Http\Controllers\UserController;
use App\Modules\Core\Models\Permission;

// Grouped under 'core' prefix and 'core.' route names
Route::middleware(['auth:sanctum'])->prefix('core')->name('core.')->group(function () {
    // 📂 Menu Routes
    Route::get('/menus', [MenuController::class, 'tree'])->name('menus.tree');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    // Route::post('/menus', function() {
    //     return response()->json(['message' => 'Menu creation is disabled.'], 403);
    // })->name('menus.store');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::get('/permissions', function () {
        return Permission::orderBy('name')->get()->map( function ($perm) {
            return [
                'id' => $perm->id,
                'name' => $perm->name,
                'label' => ucwords(str_replace('.', ' ', $perm->name))
            ];
        });
    });

    // 📂 Role Routes
    Route::get('/roles', [RoleController::class, 'getRolesPermissions'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('/roles/{role}/sync-permissions')->name('roles.sync');

    // 📂 User Routes
    Route::get('/users', [UserController::class, 'getUsers'])->name('users.get');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});