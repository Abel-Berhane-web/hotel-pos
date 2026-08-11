<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRental extends Model
{
    protected $fillable = [
        'room_id', 'status', 'guest_name', 'guest_phone', 'check_in', 'check_out',
        'nights', 'original_price', 'discount_type', 'discount_value',
        'discount_amount', 'total_price', 'payment_method', 'payment_status',
        'receptionist_id', 'note',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'datetime',
            'check_out' => 'datetime',
            'original_price' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function room() { return $this->belongsTo(Room::class); }
    public function receptionist() { return $this->belongsTo(User::class, 'receptionist_id'); }

    public function isActive(): bool { return is_null($this->check_out); }

    public static function calculateDiscount(float $originalPrice, string $type, float $value): array
    {
        $discountAmount = 0;
        if ($type === 'percentage') {
            $discountAmount = round($originalPrice * $value / 100, 2);
        } elseif ($type === 'fixed') {
            $discountAmount = min($value, $originalPrice);
        }
        return [
            'discount_amount' => $discountAmount,
            'total_price' => $originalPrice - $discountAmount,
        ];
    }
}
