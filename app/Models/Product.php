<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name_en', 'name_am', 'selling_price', 'cost_price',
        'unit', 'track_stock', 'stock_quantity', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'track_stock' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function stockAdjustments() { return $this->hasMany(StockAdjustment::class); }

    public function getNameAttribute()
    {
        return app()->getLocale() === 'am' ? $this->name_am : $this->name_en;
    }

    public function isLowStock(): bool
    {
        $threshold = BusinessSetting::get('low_stock_threshold', 5);
        return $this->track_stock && $this->stock_quantity <= $threshold;
    }
}
