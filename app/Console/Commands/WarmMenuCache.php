<?php

namespace App\Console\Commands;

use App\Modules\Core\Services\MenuCacheService;
use Illuminate\Console\Command;

class WarmMenuCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm the menu tree cache';

    /**
     * Execute the console command.
     */
    public function handle(MenuCacheService $cache)
    {
        $cache->warm();
        $this->info('Menu cache warmed!');
    }
}
