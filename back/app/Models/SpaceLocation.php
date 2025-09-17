<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpaceLocation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'space_id',
        'latitude',
        'longitude',
        'location_written',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    // Relationships
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }
}
