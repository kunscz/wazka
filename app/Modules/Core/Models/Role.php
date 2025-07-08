<?php

namespace App\Modules\Core\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function menus()
    {
        // Optional: if roles need direct access to menus
        return $this->hasManyThrough(Menu::class, Permission::class, 'id', 'id', 'id', 'id')
                    ->with('menus');
    }

}
