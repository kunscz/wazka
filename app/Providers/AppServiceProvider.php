<?php

namespace App\Providers;

use App\Modules\Core\Services\MenuCacheService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(MenuCacheService $cache): void
    {
        $coreRoutes = base_path('app/Modules/Core/Routes/web.php');
        $coreApiRoutes = base_path('app/Modules/Core/Routes/api.php');

        if (file_exists($coreRoutes)) {
            Route::middleware(['web', 'auth', 'verified'])
                ->group($coreRoutes);
        }
        if (file_exists($coreApiRoutes)) {
            Route::middleware(['api'])->prefix('api')
                ->group($coreApiRoutes);
        }

        if (!$cache->exists()) {
            $cache->warm();
        }

    }
}
