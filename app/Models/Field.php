<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Booking;
use App\Models\FieldSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'price_per_hour',
        'status',
    ];

    public function venue():BelongsTo{
       return $this->belongsTo(Venue::class);
    }

    public function schedules():HasMany
    {
        return $this->hasMany(FieldSchedule::class);
    }

    public function bookings():HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
