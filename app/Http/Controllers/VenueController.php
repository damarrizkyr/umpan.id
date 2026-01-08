<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\VenueImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FieldSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $venues = Venue::with(['images'])->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(10);
        // variable $venues isinya berupa data dari models venue beeserta relasinya dengan images namun hanaya user yang sedang login yang bisa melihat data tersebut, lalu diurutkan berdasarkan id secara descending dan ditampilkan 10 data per halaman
        return view('profile.page-profile',
        // mengirim data $venues ke view profile.page-profile
        // [
        //     'venues' => $venues
        // ]
        compact('venues')
        );

    }

    public function indexPublic(Request $request)
    {
        // 1. Inisialisasi Query dengan Eager Loading (supaya query ringan)
        $venues = Venue::with(['images', 'fields.schedules', 'reviews']);

        // 2. Filter berdasarkan nama lapangan (Search)
        if ($request->has('search') && $request->search != null) {
            $venues->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 3. Filter berdasarkan kota (City)
        if ($request->has('city') && $request->city != null) {
            $venues->where('city', $request->city);
        }

        $venues = $venues->withCount('reviews')->orderBy('reviews_count', 'desc')->paginate(10)->withQueryString();

        return view("dashboard", compact('venues'));
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'facilities' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|in:medan,aceh,jakarta',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'name.required' => 'Nama venue wajib diisi.',
            'description.required' => 'Deskripsi venue wajib diisi.',
            'facilities.required' => 'Fasilitas venue wajib diisi.',
            'address.required' => 'Alamat venue wajib diisi.',
            'city.required' => 'Kota venue wajib diisi.',
            'open_time.required' => 'Waktu buka venue wajib diisi.',
            'close_time.required' => 'Waktu tutup venue wajib diisi.',
            'close_time.after' => 'Waktu tutup harus setelah waktu buka.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($request->name) . '-' . time();

        // PENTING: Hapus 'images' dari $data jika ada
        unset($data['images']);

        $venue = Venue::create($data);

        // Handle image uploads
        //jika ada banyak file gambar yang diupload
        //('images') isinya dari input name="images[]" yang ada di file modal/add.blade.php
        if ($request->hasFile('images')) {
            //lakukan perulangan untuk setiap gambar yang diupload lalu setiap gambarnya diberi nomor index lalu disimpan divariable $image
            foreach ($request->file('images') as $index => $image) {
                //variable $path sebagai alamat atau temapt penyimpanan gambar yang diupload kedalam folder 'venues/images' di storage disk 'public'
                $path = $image->store('venues/images', 'public');
                //memanggil relasi images dari model Venue untuk menyimpan data gambar ke dalam tabel venue_images dengan venue_id, image_path dari $path, dan is_primary dari index (jika index 0 maka is_primary true)
                $venue->images()->create([
                    //kolom image_path diisi dengan $path
                    'image_path' => $path,
                    //kolom is_primary diisi dengan kondisi jika index sama dengan 0 maka is_primary true
                    'is_primary' => $index === 0, // Set the first image as primary
                ]);
            }
        }

        // Create fields based on field_count
        //jika ada inputan harga dari form dengan name="prices" di file modal/add.blade.php yang ada di folder resources/js/add-field-schedule.js
        if ($request->has('prices')) {
            //lakukan perulangan untuk setiap prices yang diinputkan, ambil Angka Index (dari file JS ${fieldIndex}) simpan ke variabel $fieldIndex. Sedangkan Seluruh Isi Datanya simpan ke variabel $days.
            foreach ($request->prices as $fieldIndex => $days) {
                //membuat field baru di tabel fields (dari models Filed) dengan venue_id dari $venue->id dan name dari "Lapangan {$fieldIndex}"
                $field = Field::create([
                    'venue_id' => $venue->id,
                    'name'     => "Lapangan {$fieldIndex}",
                ]);

                //lakukan perulangan lagi isi dari variabel $days, ambil nama hari simpan ke variabel $day dan seluruh isi datanya simpan ke variabel $slots
                foreach ($days as $day => $slots) {
                    //lakukan perulangan lagi isi dari variabel $slots, ambil waktu simpan ke variabel $time dan harga simpan ke variabel $price
                    foreach ($slots as $slot => $price) {

                        if (!$price) continue;

                        FieldSchedule::create([
                            'field_id'  => $field->id,
                            'day'       => $day,
                            'time_slot' => $slot,
                            'price'     => $price,
                        ]);
                    }
                }
            }
        }



        return redirect()->route('profile.venues')
            ->with('success', 'Venue berhasil ditambahkan! Silakan atur jadwal lapangan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue)
    {
        //
        $venue->load('fields.schedules');

    return view('detail-venue.show-detail', compact('venue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venue $venue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        //
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'facilities' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|in:medan,aceh,jakarta',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'name.required' => 'Nama venue wajib diisi.',
            'description.required' => 'Deskripsi venue wajib diisi.',
            'facilities.required' => 'Fasilitas venue wajib diisi.',
            'address.required' => 'Alamat venue wajib diisi.',
            'city.required' => 'Kota venue wajib diisi.',
            'open_time.required' => 'Waktu buka venue wajib diisi.',
            'close_time.required' => 'Waktu tutup venue wajib diisi.',
            'close_time.after' => 'Waktu tutup harus setelah waktu buka.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Find venue
        $venue = Venue::with('fields.schedules')->findOrFail($id);

        // Cek authorization
        if ($venue->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus images dari data sebelum update
        unset($data['images']);

        // Update venue
        $venue->update($data);

        // Upload additional images jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $imagePath = $image->store('venues/images', 'public');

                VenueImage::create([
                    'venue_id' => $venue->id,
                    'image_path' => $imagePath,
                    'is_primary' => VenueImage::where('venue_id', $venue->id)->count() === 0,
                ]);
            }
        }

        // Handle Field Baru
        if ($request->has('new_fields')) {
            foreach ($request->new_fields as $newFieldIndex => $fieldData) {
                $newField = Field::create([
                    'venue_id' => $venue->id,
                    'name' => "Lapangan " . ($venue->fields->count() + 1),
                ]);

                // Create schedules untuk field baru
                if (isset($fieldData['schedules'])) {
                    foreach ($fieldData['schedules'] as $day => $slots) {
                        foreach ($slots as $scheduleData) {
                            if (isset($scheduleData['time']) && isset($scheduleData['price']) && $scheduleData['price']) {
                                FieldSchedule::create([
                                    'field_id' => $newField->id,
                                    'day' => $day,
                                    'time_slot' => $scheduleData['time'],
                                    'price' => $scheduleData['price'],
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // Update field schedules yang sudah ada
        if ($request->has('prices')) {
            foreach ($request->prices as $fieldId => $days) {
                // Pastikan field milik venue ini
                $field = Field::where('id', $fieldId)
                    ->where('venue_id', $venue->id)
                    ->first();

                if (!$field) continue;

                foreach ($days as $day => $slots) {
                    foreach ($slots as $scheduleId => $scheduleData) {
                        // Validasi data schedule
                        if (!isset($scheduleData['time']) || !isset($scheduleData['price'])) {
                            continue;
                        }

                        // Cek apakah ini schedule baru atau update
                        if (str_starts_with($scheduleId, 'new')) {
                            // Create schedule baru
                            FieldSchedule::create([
                                'field_id' => $fieldId,
                                'day' => $day,
                                'time_slot' => $scheduleData['time'],
                                'price' => $scheduleData['price'],
                            ]);
                        } else {
                            // Update schedule yang sudah ada
                            FieldSchedule::where('id', $scheduleId)
                                ->where('field_id', $fieldId)
                                ->update([
                                    'time_slot' => $scheduleData['time'],
                                    'price' => $scheduleData['price'],
                                ]);
                        }
                    }
                }
            }
        }

        // 1. Hapus Sesi / Jam Spesifik (NEW)
        if ($request->has('deleted_schedules')) {
            // Hapus data schedule berdasarkan ID-nya
            FieldSchedule::destroy($request->deleted_schedules);
        }

        // 2. Hapus Satu Hari Penuh
        if ($request->has('deleted_days')) {
            foreach ($request->deleted_days as $fieldId => $days) {
                FieldSchedule::where('field_id', $fieldId)
                    ->whereIn('day', $days) // Pastikan nama hari di DB lowercase (senin, selasa)
                    ->delete();
            }
        }

        // 3. Hapus Lapangan
        if ($request->has('deleted_fields')) {
            Field::whereIn('id', $request->deleted_fields)
                ->where('venue_id', $venue->id)
                ->delete();
        }

        return redirect()->route('profile.venues')->with('success', 'Venue berhasil diperbarui!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
    $venue = Venue::with('images')->findOrFail($id);

        // Cek authorization
        if ($venue->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus semua gambar dari storage
        foreach ($venue->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // Delete venue (akan cascade ke images, fields, dan schedules)
        $venue->delete();

        return redirect()->route('profile.venues')
            ->with('success', 'Data venue dan gambar berhasil dihapus');

    }

    public function deleteImage(Request $request, $imageId)
    {
        $image = VenueImage::findOrFail($imageId);

        // Cek apakah venue milik user
        $venue = Venue::findOrFail($image->venue_id);
        if ($venue->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus file dari storage
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Hapus record dari database
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus'
        ]);
    }

    public function bookings(Request $request, $venueId)
    {
        $venue = Venue::with('fields')->findOrFail($venueId);

        // Cek authorization
        if ($venue->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Query builder dengan filter
        $query = Booking::whereHas('field', function($q) use ($venueId) {
            $q->where('venue_id', $venueId);
        })
        ->with(['field', 'schedule', 'user']);

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('booking_code', 'like', '%' . $request->search . '%')
                ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan lapangan
        if ($request->filled('field')) {
            $query->where('field_id', $request->field);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // Ambil bookings dengan pagination
        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('profile.venues.detail-pembooking', compact('venue', 'bookings'));
    }
}
