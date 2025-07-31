<?php

// app/Modules/Core/Routes/web.php

use App\Modules\Core\Http\Controllers\MenuController;
use App\Modules\Core\Http\Controllers\RoleController;
use App\Modules\Core\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('core')->name('core.')->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index'); // Menu Manager view
    Route::get('/menus/tree', [MenuController::class, 'tree'])->name('menus.tree');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('role.index'); // Menu Manager view
});