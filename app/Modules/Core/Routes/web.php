<?php

// app/Modules/Core/Routes/web.php

use App\Modules\Core\Http\Controllers\MenuController;
use App\Modules\Core\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('core')->name('core.')->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index'); // Menu Manager view
});