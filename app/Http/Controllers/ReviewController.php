<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        // 1. Validasi Input
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // 2. Cek apakah user login dan role-nya 'user'
        // (Meskipun sudah di-handle middleware/blade, validasi backend tetap wajib)
        if (Auth::user()->role !== 'user') {
            return back()->with('error', 'Hanya penyewa (User) yang bisa memberikan ulasan.');
        }

        // 3. Opsional: Cek apakah user sudah pernah booking venue ini (Best Practice)
        // Jika kamu ingin atturannya "Hanya yang sudah pernah booking yang bisa review",
        // aktifkan kode di bawah ini:
        $hasBooked = Booking::where('user_id', Auth::id())
            ->whereHas('field', function ($query) use ($request) {
                $query->where('venue_id', $request->venue_id);
            })
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasBooked) {
            return back()->with('error', 'Anda harus bermain di lapangan ini (status confirmed) sebelum memberi ulasan.');
        }

        // 4. Cek apakah user sudah pernah review venue ini sebelumnya (Supaya tidak spam)
        $existingReview = Review::where('user_id', Auth::id())
            ->where('venue_id', $request->venue_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk venue ini.');
        }

        // 5. Simpan Review
        Review::create([
            'user_id' => Auth::id(),
            'venue_id' => $request->venue_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
