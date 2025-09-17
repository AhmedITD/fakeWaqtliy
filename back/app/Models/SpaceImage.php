<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpaceImage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'space_id',
        'image',
        'low_res_image',
    ];

    // Relationships
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }
}
