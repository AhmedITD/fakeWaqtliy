<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocalAuth extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'phone_number',
        'password_hash',
        'is_phone_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_phone_verified' => 'boolean',
        ];
    }

    protected $hidden = [
        'password_hash',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
