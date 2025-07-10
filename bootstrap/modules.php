<?php

use Illuminate\Support\Facades\Route;

// Load web routes from modules
foreach (glob(base_path('app/Modules/*/Routes/web.php')) as $file) {
    Route::middleware('web')->group($file);
}

// Load API routes from modules
foreach (glob(base_path('app/Modules/*/Routes/api.php')) as $file) {
    Route::middleware('api')->prefix('api')->group($file);
}