<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'reservation_id',
        'space_service_id',
    ];

    // Relationships
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function spaceService(): BelongsTo
    {
        return $this->belongsTo(SpaceService::class);
    }
}
