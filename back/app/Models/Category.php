<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'image',
    ];

    // Relationships
    public function spaces(): HasMany
    {
        return $this->hasMany(Space::class);
    }
}
