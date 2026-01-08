<?php

namespace App\Models;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenueImage extends Model
{
    //
    protected $fillable = [
        'venue_id',
        'image_path',
        'is_primary',
    ];

    public function venue():BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
