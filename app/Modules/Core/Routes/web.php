<?php

use App\Modules\Core\Http\Controllers\UserController;

Route::prefix('core')->name('core.')->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
});