<?php

/**
     * Usages:
     * php artisan sync:menus
     * php artisan sync:menus --dry-run
     * php artisan sync:menus --only-index
     * php artisan sync:menus --dry-run --only-index
     * @var string
     */

namespace App\Console\Commands;

use App\Modules\Core\Models\Menu;
use App\Modules\Core\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SyncMenusAndPermissions extends Command
{
    protected $signature = 'sync:menus {--dry-run : Preview changes without saving} {--only-index : Only generate menus for index/show routes}';

    protected $description = 'Auto-sync menu and permission items from Laravel named routes';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $modeTag = $dryRun ? '[DRY-RUN]' : '[SYNC]';

        $this->info("{$modeTag} Scanning named routes...");

        $permissionMap = [
            'index' => 'view',
            'show' => 'view',
            'view' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];

        $iconMap = [
            'users' => 'mdi-account-multiple',
            'roles' => 'mdi-shield-account',
            'menus' => 'mdi-menu',
            'permissions' => 'mdi-lock',
            'crm' => 'mdi-account-box-outline',
            'callcenter' => 'mdi-phone',
            'dashboard' => 'mdi-view-dashboard',
            'settings' => 'mdi-cog-outline',
        ];

        $ignoredMiddleware = []; // you can add 'guest', 'verified', etc.
        $ignoredPrefixes = ['settings', 'profile', 'password', 'appearance','register','login','logout','home'];

        $routes = collect(Route::getRoutes())->filter(fn ($route) => $route->getName());

        foreach ($routes as $route) {
            $routeName = $route->getName();
            $segments = explode('.', $routeName);
            $segmentCount = count($segments);
            $isFlatRoute = $segmentCount === 1;

            if ($segmentCount === 1) {
                $module = ucfirst($segments[0]);
                $resource = $module;
                $action = 'view'; // fallback action

                $this->line("ℹ️ $modeTag Interpreting single-segment route \"$routeName\" as [$module → view]");
            } elseif ($segmentCount === 2) {
                $module = ucfirst($segments[0]);
                $resource = null;
                $action = $segments[1];
            } else {
                $module = ucfirst($segments[0]);
                $resource = ucfirst($segments[$segmentCount - 2]);
                $action = $segments[$segmentCount - 1];
            }

            $middleware = $route->gatherMiddleware();

            // Ignore routes with specific middleware
            if (collect($middleware)->intersect($ignoredMiddleware)->isNotEmpty()) {
                if ($dryRun) {
                    $this->line("🚫 [SKIP] \"$routeName\" has ignored middleware (" . implode(', ', $middleware) . ")");
                }
                continue;
            }

            // Ignore based on prefix
            if (in_array($segments[0] ?? '', $ignoredPrefixes)) {
                if ($dryRun) {
                    $this->line("🚫 [SKIP] \"$routeName\" matches ignored prefix");
                }
                continue;
            }

            $onlyIndexMode = $this->option('only-index');
            $menuableActions = ['index', 'show', 'view']; // actions that should be shown in menu

            $shouldGenerateMenu = !$onlyIndexMode || in_array($action, $menuableActions);
            $resource = $segmentCount >= 3 ? ucfirst($segments[$segmentCount - 2]) : null;

            $target = $resource ?? $module;
            $verb = $permissionMap[$action] ?? null;

            if (!$verb) {
                if ($dryRun) {
                    $this->line("⚠️ [SKIP] \"$routeName\" uses unsupported action \"$action\"");
                }
                continue;
            }

            $permissionName = "$verb " . strtolower($target);
            $permissionDescription = "Can {$verb} {$target}";
            $icon = $iconMap[strtolower($target)] ?? 'mdi-file';

            if ($dryRun) {
                $this->line("🔹 $modeTag Would create permission: \"$permissionName\" — {$permissionDescription}");
            } else {
                Permission::updateOrCreate(['name' => $permissionName], ['description' => $permissionDescription]);
            }

            // Create module-level menu (top level)
            if ($dryRun) {
                $this->line("📁 $modeTag Would create module menu: \"$module\"");
                if($isFlatRoute) {
                    $this->line("🔗 $modeTag Would link permission \"$permissionName\" to \"$module\"");
                }
            } else {
                $moduleMenu = Menu::firstOrCreate(
                    ['label' => $module, 'parent_id' => null],
                    ['icon' => 'mdi-folder', 'sort_order' => 10, 'is_active' => true],
                );

                if($isFlatRoute) {
                    $permission = Permission::where('name', $permissionName)->first();
                    $moduleMenu->permissions()->syncWithoutDetaching([$permission->id]);

                    $this->line("✅ Synced \"$module\" with \"$permissionName\" (icon: $icon)");
                }
            }

            // Create resource-level menu (if exists)
            if ($resource) {
                if ($dryRun) {
                    $this->line("📄 $modeTag Would create resource menu \"$resource\" under \"$module\"");
                } else {
                    $resourceMenu = Menu::firstOrCreate(
                        ['label' => $resource, 'parent_id' => $moduleMenu->id],
                        ['icon' => $icon]
                    );
                }
            }

            // Create action-level menu
            $menuLabel = $segmentCount === 1 ? ucfirst(string: $routeName) : ucfirst(string: $action);

            if ($shouldGenerateMenu) {
                if(!$isFlatRoute) {
                    if ($dryRun) {
                        $this->line("🧱 $modeTag Would create menu: \"$menuLabel\" under \"" . ($resource ?? $module) . "\"");
                        $this->line("🔗 $modeTag Would link permission \"$permissionName\" to \"$menuLabel\"");
                    } else {
                        $parentId = isset($resourceMenu) ? $resourceMenu->id : $moduleMenu->id;

                        $menu = Menu::firstOrCreate(['route_name' => $routeName], [
                            'label' => $menuLabel,
                            'icon' => $icon,
                            'parent_id' => $parentId,
                            'sort_order' => 99,
                            'is_active' => true,
                        ]);

                        $permission = Permission::where('name', $permissionName)->first();
                        $menu->permissions()->syncWithoutDetaching([$permission->id]);

                        $this->line("✅ Synced \"$menuLabel\" with \"$permissionName\" (icon: $icon)");
                    }
                }
            } else {
                if ($dryRun) {
                    $this->line("📄 $modeTag Skipped menu for \"$routeName\" due to --only-index");
                }
            }

            unset($resourceMenu); // Clean for next loop
        }

        $this->info($dryRun ? '🚧 Dry-run complete. No changes saved.' : '✅ Sync complete.');
        $this->info("✅ {$modeTag} Total routes processed: {$routes->count()}");
    }
}