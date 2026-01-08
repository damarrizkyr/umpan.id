<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Field;
use App\Models\FieldSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'field_id',
        'schedule_id',
        'customer_name',
        'customer_phone',
        'booking_date',
        'total_price',
        'booking_code',
        'status',
        'payment_status',
        'expired_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'expired_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(FieldSchedule::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Generate booking code
    public static function generateBookingCode()
    {
        do {
            $code = 'BKG' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    public function getFormattedPriceAttribute() {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedDateAttribute() {
        return Carbon::parse($this->booking_date)->locale('id')->translatedFormat('l, d F Y');
    }

    
}
