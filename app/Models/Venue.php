<?php

namespace App\Models;

use App\Models\User;
use App\Models\Field;
use App\Models\Review;
use App\Models\VenueImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'facilities',
        'address',
        'city',
        'open_time',
        'close_time',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images():HasMany
    {
        return $this->hasMany(VenueImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(VenueImage::class)->where('is_primary', true);
    }

    public function secondaryImages():HasMany
    {
        return $this->hasMany(VenueImage::class)->where('is_primary', false);
    }

    public function fields():HasMany
    {
        return $this->hasMany(Field::class);
    }
    // Helper: Get minimum price
    public function minPrice()
    {
        return $this->fields()
            ->join('field_schedules', 'fields.id', '=', 'field_schedules.field_id')
            ->min('field_schedules.price');
    }

    // Relasi ke Review
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    // Helper: Hitung Rata-rata Rating
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }

    // Helper: Hitung Jumlah Review
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getCoverImageUrlAttribute()
    {
        // Ambil gambar pertama jika ada
        $firstImage = $this->images->first();

        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }

        // Jika tidak ada gambar, kembalikan gambar default/placeholder
        return 'https://via.placeholder.com/400x250?text=No+Image';
    }

    // 2. Logika Format Harga Terendah
    public function getMinPriceFormattedAttribute()
    {
        $price = $this->minPrice() ?? 0;
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}
