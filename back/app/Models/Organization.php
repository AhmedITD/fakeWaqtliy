<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'owner_id',
        'description',
        'email',
        'image',
    ];

    // Relationships
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(OrganizationLocation::class);
    }

    public function spaces(): HasMany
    {
        return $this->hasMany(Space::class);
    }
}
