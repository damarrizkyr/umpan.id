<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Home page - Public (tampilan utama untuk semua user)
Route::get('/', [VenueController::class, 'indexPublic'])->name('dashboard');

// Booking - Public (siapa saja bisa booking tanpa login)
Route::post('/bookings', [BookingController::class, 'store'])
    ->name('bookings.store');

// Show venue detail - Public
Route::get('/venues/{venue:slug}', [VenueController::class, 'show'])->name('venues.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route pemilik lapangan
    Route::get('/profile/venues', [VenueController::class, 'index'])->name('profile.venues');

    // Route resource untuk CRUD venues
    Route::resource('venues', VenueController::class)->except(['index', 'show']);

    // Delete venue image
    Route::delete('/venues/images/{imageId}', [VenueController::class, 'deleteImage'])
        ->name('venues.images.delete');

    // Bookings
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])
        ->name('bookings.my');
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');

    // Venue Bookings - untuk owner venue
    Route::get('/venues/{venue}/bookings', [VenueController::class, 'bookings'])
        ->name('venues.bookings');
    Route::patch('/bookings/{id}/cancel-by-owner', [BookingController::class, 'cancelByOwner'])
        ->name('bookings.cancelByOwner');

        // --- TAMBAHKAN DISINI (Agar rapi sesama fitur Owner) ---
    Route::patch('/bookings/{id}/confirm', [BookingController::class, 'confirm'])
        ->name('bookings.confirm');

    // Delete booking (hapus dari database) - TAMBAHKAN INI
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])
        ->name('bookings.destroy');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

require __DIR__.'/auth.php';
