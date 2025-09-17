<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'space_service_id',
    ];

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function spaceService(): BelongsTo
    {
        return $this->belongsTo(SpaceService::class);
    }
}
