<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action', 'model_type', 'model_id', 'details'];

    public function user() { return $this->belongsTo(User::class); }

    public static function log(string $action, ?string $modelType = null, ?int $modelId = null, ?string $details = null): void
    {
        static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'details' => $details,
        ]);
    }
}
