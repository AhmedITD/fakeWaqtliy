<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $fillable = [
        'user_id',
        'revoked',
        'user_agent',
        'refresh_token_hash',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'revoked' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    protected $hidden = [
        'refresh_token_hash',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
