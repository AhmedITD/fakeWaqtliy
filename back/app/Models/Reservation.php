<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\ReservationStatus;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'space_id',
        'status',
        'total_price',
        'date',
        'start_time',
        'end_time',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReservationStatus::class,
            'total_price' => 'integer',
            'date' => 'date',
            'start_time' => 'datetime:H:i:s',
            'end_time' => 'datetime:H:i:s',
            'cancelled_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReservationDetail::class);
    }
}
