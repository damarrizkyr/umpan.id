<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\FieldSchedule;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
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
        $data = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'schedule_id' => 'required|exists:field_schedules,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|numeric|digits_between:11,13|starts_with:08',
            'booking_date' => 'required|date|after_or_equal:today',
        ],
        [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'customer_name.string' => 'Nama pelanggan harus berupa teks.',
            'customer_phone.required' => 'Nomor telepon pelanggan wajib diisi.',
            'customer_phone.starts_with' => 'Nomor HP harus diawali 08',
            'customer_phone.digits_between' => 'Nomor HP harus 11-13 digit',
        ]);


        $existingBooking = Booking::where('field_id', $request->field_id)
            ->where('schedule_id', $request->schedule_id)
            ->where('booking_date', $request->booking_date)
            ->first();

            if ($existingBooking) {
                if ($existingBooking->status !== 'cancelled') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maaf, slot ini sudah dibooking oleh orang lain.'
                    ], 422);
                }

            $existingBooking->delete();
            }

        // ambii data shedule untuk harga
        $schedule = FieldSchedule::findOrFail($request->schedule_id);
        $field = Field::with('venue')->findOrFail($request->field_id);

        // Buat booking - user_id nullable (bisa null jika tidak login)
            $booking = Booking::create([
                'user_id' => Auth::check() ? Auth::id() : null, // Nullable jika tidak login
                'field_id' => $request->field_id,
                'schedule_id' => $request->schedule_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'total_price' => $schedule->price,
                'booking_code' => Booking::generateBookingCode(),
                'status' => 'pending',
                'payment_status' => 'paid',
                'expired_at' => Carbon::parse($request->booking_date)->addDay(),
            ]);

        if ($booking) {
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat.',
                'booking_code' => $booking->booking_code,
                'data' => [
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->customer_name,
                    'customer_phone' => $booking->customer_phone,
                    'venue_name' => $field->venue->name,
                    'field_name' => $field->name,
                    'booking_date' => $booking->booking_date->format('Y-m-d'),
                    'time_slot' => $schedule->time_slot,
                    'total_price' => $booking->total_price,
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat booking.'
            ], 500);
        }

    }

    public function myBookings()
    {
        $bookings = Booking::with(['field.venue', 'schedule'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('detail-venue.bookings.booking', compact('bookings'));
    }

    public function confirm($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Validasi: Pastikan user adalah pemilik venue dari booking ini
            // (Sesuaikan logika authorization ini dengan kodemu yang lain)
            if ($booking->field->venue->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
            }

            // Cek jika sudah confirmed
            if ($booking->status === 'confirmed') {
                return response()->json(['success' => false, 'message' => 'Booking sudah dikonfirmasi sebelumnya.']);
            }

            // Update status
            $booking->update(['status' => 'confirmed']);

            return response()->json([
                'success' => true,
                'message' => "Booking {$booking->booking_code} berhasil dikonfirmasi!"
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Cek authorization
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah booking bisa dicancel (minimal H-1)
        $minCancelDate = Carbon::now()->addDay();
        if (Carbon::parse($booking->booking_date)->lessThan($minCancelDate)) {
            return back()->with('error', 'Booking hanya bisa dibatalkan minimal H-1');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan');
    }

    public function cancelByOwner($id)
    {
        try {
            // Load relasi yang dibutuhkan
            $booking->load('field.venue');

            // Cek authorization - pastikan owner venue
            if ($booking->field->venue->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            // Check jika sudah cancelled
            if ($booking->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking sudah dibatalkan sebelumnya.'
                ], 400);
            }

            $booking->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            \Log::error('Cancel booking error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
        try {
            // Load relasi yang dibutuhkan
            $booking->load('field.venue');

            // Cek authorization - pastikan owner venue
            if ($booking->field->venue->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            // Simpan info untuk response
            $bookingCode = $booking->booking_code;

            // Hapus dari database
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => "Booking {$bookingCode} berhasil dihapus dari sistem"
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete booking error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }

    }
}
