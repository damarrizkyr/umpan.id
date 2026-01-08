<?php

namespace App\Models;

use App\Models\Field;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldSchedule extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'field_id',
        'day',
        'time_slot',
        'price',
    ];

    public function field():BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    // Helper: Get day name in Indonesian
    public function getDayNameAttribute()
    {
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
            'everyday' => 'Setiap Hari'
        ];

        return $days[$this->day] ?? $this->day;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }
    

}
