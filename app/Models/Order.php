<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'cashier_id', 'employee_id', 'payment_method',
        'subtotal', 'tax', 'total', 'note', 'order_date',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'order_date' => 'date',
        ];
    }

    public function cashier() { return $this->belongsTo(User::class, 'cashier_id'); }
    public function employee() { return $this->belongsTo(User::class, 'employee_id'); }
    public function items() { return $this->hasMany(OrderItem::class); }

    public static function generateOrderNumber(): string
    {
        $today = now()->format('Ymd');
        $count = static::whereDate('order_date', today())->count() + 1;
        return 'ORD-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getPaymentLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => __('m.cash'),
            'bank_transfer' => __('m.bank_transfer'),
            'telebirr' => __('m.telebirr'),
            'cbe_birr' => __('m.cbe_birr'),
            'credit' => __('m.credit'),
            default => $this->payment_method,
        };
    }
}
