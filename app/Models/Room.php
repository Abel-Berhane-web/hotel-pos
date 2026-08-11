<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'name_en', 'name_am', 'price_per_night', 'status'];

    protected function casts(): array
    {
        return ['price_per_night' => 'decimal:2'];
    }

    public function rentals() { return $this->hasMany(RoomRental::class); }
    public function currentRental() { return $this->hasOne(RoomRental::class)->where('status', 'active')->latest(); }

    public function getNameAttribute()
    {
        $name = app()->getLocale() === 'am' ? $this->name_am : $this->name_en;
        return $name ?: $this->room_number;
    }

    public function isAvailable(): bool { return $this->status === 'available'; }
    public function isOccupied(): bool { return $this->status === 'occupied'; }
}
