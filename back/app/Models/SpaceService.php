<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpaceService extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'space_id',
        'service_id',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }

    // Relationships
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function bookingServices(): HasMany
    {
        return $this->hasMany(BookingService::class);
    }

    public function reservationDetails(): HasMany
    {
        return $this->hasMany(ReservationDetail::class);
    }
}
