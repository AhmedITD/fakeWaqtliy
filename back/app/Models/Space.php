<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'size',
        'capacity',
        'floor',
        'price_per_hour',
        'thumbnail',
        'category_id',
        'organization_id',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'capacity' => 'integer',
            'price_per_hour' => 'integer',
        ];
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(SpaceLocation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(SpaceImage::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(SpaceService::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function ratingReviews(): HasMany
    {
        return $this->hasMany(RatingReview::class);
    }
}
