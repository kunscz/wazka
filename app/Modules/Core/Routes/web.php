<?php

use App\Modules\Core\Http\Controllers\UserController;
use App\Modules\Core\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::prefix('core')->name('core.')->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
});

Route::get('/menus', [MenuController::class, 'index'])->name('menus.index'); // Inertia
Route::get('/api/menus/tree', [MenuController::class, 'tree']);
Route::post('/api/menus', [MenuController::class, 'store']);
Route::put('/api/menus/{menu}', [MenuController::class, 'update']);
Route::delete('/api/menus/{menu}', [MenuController::class, 'destroy']);