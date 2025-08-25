<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        "label",
        "route_name",
        "url",
        "icon",
        "sort_order",
        "parent_id",
        "is_active",
        "is_manual"
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'menu_permission');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function scopeWithFullTree($query)
    {
        return $query->with([
            'permissions',
            'children.permissions',
        ]);
    }
}
