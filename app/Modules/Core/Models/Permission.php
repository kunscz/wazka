<?php

namespace App\Modules\Core\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_permission');
    }
}
