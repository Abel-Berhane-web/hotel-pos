<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name_en', 'name_am', 'type', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function products() { return $this->hasMany(Product::class); }

    public function getNameAttribute()
    {
        return app()->getLocale() === 'am' ? $this->name_am : $this->name_en;
    }
}
