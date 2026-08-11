<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'language_preference',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isManager(): bool { return $this->role === 'manager'; }
    public function isCashier(): bool { return $this->role === 'cashier'; }
    public function isReceptionist(): bool { return $this->role === 'receptionist'; }
    public function isEmployee(): bool { return $this->role === 'employee'; }

    public function canAccessReports(): bool { return in_array($this->role, ['admin', 'manager']); }
    public function canManageProducts(): bool { return in_array($this->role, ['admin', 'manager']); }
    public function canManageUsers(): bool { return $this->role === 'admin'; }
    public function canManageRooms(): bool { return in_array($this->role, ['admin', 'receptionist', 'manager']); }
    public function canCreateOrders(): bool { return in_array($this->role, ['admin', 'manager', 'cashier']); }

    public function orders() { return $this->hasMany(Order::class, 'cashier_id'); }
    public function assignedOrders() { return $this->hasMany(Order::class, 'employee_id'); }
    public function roomRentals() { return $this->hasMany(RoomRental::class, 'receptionist_id'); }
}
