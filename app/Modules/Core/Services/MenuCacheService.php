<?php

/**
 * ftre:
 * building a clean admin page or JSON debug endpoint to inspect cache status, tree size, and version info
 */

namespace App\Modules\Core\Services;

use Illuminate\Support\Facades\Cache;
use App\Modules\Core\Models\Menu;
use Illuminate\Support\Collection;

class MenuCacheService
{
    protected string $cacheKey = 'menu_tree';
    protected int $ttlMinutes = 10;

    /**
     * Get cached menu tree or rebuild it
     */
    public function get(): Collection
    {
        return Cache::remember($this->cacheKey, now()->addMinutes($this->ttlMinutes), function () {
            return Menu::with('children')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Invalidate the menu tree cache
     */
    public function forget(): void
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Warm up the cache manually
     */
    public function warm(): void
    {
        $this->forget();
        $this->get();
    }

    /**
     * Check if cache exists
     */
    public function exists(): bool
    {
        return Cache::has($this->cacheKey);
    }

    /**
     * Get cache timestamp (optional)
     */
    public function version(): int|null
    {
        return Cache::get($this->cacheKey . '_version');
    }

    /**
     * Set cache version (optional)
     */
    public function stamp(): void
    {
        Cache::put($this->cacheKey . '_version', now()->timestamp);
    }
}