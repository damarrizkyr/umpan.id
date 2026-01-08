<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function getFormattedPriceAttribute() {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Cek apakah jadwal ini SUDAH DIBOOKING pada tanggal tertentu
    public function isBookedOn($date)
    {
        return $this->bookings()
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }

    // Cek apakah jadwal ini SUDAH LEWAT waktu sekarang
    public function isPastOn($date)
    {
        $now = Carbon::now('Asia/Jakarta');

        // Ambil jam awal saja. Contoh "12:00-13:00" -> ambil "12:00"
        $startTime = trim(explode('-', $this->time_slot)[0]);

        // Gabungkan tanggal target + jam jadwal
        $scheduleTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $startTime, 'Asia/Jakarta');

        // Kembalikan true jika waktu sekarang lebih besar dari jadwal
        return $now->greaterThan($scheduleTime);
    }

    // Helper untuk status tombol (digunakan di Blade)
    public function getStatusOn($date)
    {
        if ($this->isBookedOn($date)) {
            return 'booked';
        }
        if ($this->isPastOn($date)) {
            return 'past';
        }
        return 'available';
    }
    // Method bantu untuk cek apakah slot sudah lewat jamnya
    public function isPast($targetDate)
    {
        // Ambil jam awal dari slot (misal "12:00-13:00" jadi "12:00")
        $startTime = trim(explode('-', $this->time_slot)[0]);

        // Gabungkan tanggal target dengan jam slot
        $slotTime = Carbon::parse($targetDate . ' ' . $startTime);

        // Cek apakah sekarang sudah melewati waktu tersebut
        return Carbon::now()->greaterThan($slotTime);
    }

    // Method bantu untuk cek apakah slot sudah dibooking
    public function isBooked($targetDate)
    {
        return $this->bookings()
            ->where('booking_date', $targetDate)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }

}
