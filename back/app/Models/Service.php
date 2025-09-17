<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    // Relationships
    public function spaceServices(): HasMany
    {
        return $this->hasMany(SpaceService::class);
    }
}
