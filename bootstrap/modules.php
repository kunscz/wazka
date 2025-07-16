<?php

use Illuminate\Support\Facades\Route;

foreach (glob(base_path('app/Modules/*/Routes/web.php')) as $file) {
    Route::middleware('web')->group($file);
}

foreach (glob(base_path('app/Modules/*/Routes/api.php')) as $file) {
    Route::middleware('api')->prefix('api')->group($file);
}