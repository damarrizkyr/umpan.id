<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="venue-detail-section">

        <div class="container my-5">
            {{-- Gallery --}}
            {{-- Start Gallery Grid Modern --}}
            <div class="row mb-4 gallery-desktop">
                @include('detail-venue.modal-foto')

            </div>
            {{-- End Gallery Grid Modern --}}

            <div class="row">
                <div class="col-lg-9 mb-4">
                    {{-- Venue Name --}}
                    <div class="card info-card mb-4 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-body p-4 p-lg-5">

                            {{-- HEADER SECTION --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                    <div>

                                        <h1 class="fw-bold text-dark mb-2"
                                            style="font-size: 1.5rem; letter-spacing: -1px;">
                                            {{ $venue->name }}
                                        </h1>
                                        <div class="d-flex align-items-center text-secondary">
                                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                            <span class="fs-">{{ $venue->address }},
                                                {{ ucfirst($venue->city) }}</span>
                                        </div>
                                    </div>

                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $venue->user->phone) }}"
                                        target="_blank"
                                        class="btn btn-success rounded-circle d-flex align-items-center justify-content-center text-white p-0 shadow-sm"
                                        style="width: 42px; height: 42px; transition: all 0.2s ease;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                        <i class="bi bi-whatsapp fs-5"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <h5 class="fw-bold mb-3 text-dark">
                                    <i class="bi bi-stars text-warning me-2"></i>Fasilitas Unggulan
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @php
                                        // Memecah string fasilitas menjadi array berdasarkan koma
                                        $facilities = explode(',', $venue->facilities);
                                    @endphp

                                    @foreach ($facilities as $facility)
                                        <span
                                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold"
                                            style="font-size: 0.9rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> {{ trim($facility) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- DESKRIPSI (Dengan Background tipis agar terpisah fokusnya) --}}
                            <div class="bg-light bg-opacity-50 p-4 rounded-4 border border-light-subtle">
                                <h5 class="fw-bold mb-3 text-dark">
                                    <i class="bi bi-info-circle-fill text-primary me-2"></i>Tentang Venue Ini
                                </h5>
                                <p class="text-secondary mb-0" style="line-height: 1.8; font-size: 1.05rem;">
                                    {{ $venue->description }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Venue Info --}}
                <div class="col-lg-3 mb-4">
                    <div class="card info-card" style="top: 20px;">
                        <div class="card-body">

                            <div class="mb-3">
                                <h6 class="fw-semibold mb-2">
                                    <i class="bi bi-clock-fill text-primary me-2"></i>Jam Operasional
                                </h6>
                                <p class="text-muted">
                                    {{ \Carbon\Carbon::parse($venue->open_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($venue->close_time)->format('H:i') }}
                                </p>
                            </div>

                            <div class="text-center">
                                <div class="mb-2">
                                    <small class="text-muted">Harga Mulai Dari</small>
                                </div>
                                <h2 class="text-success fw-bold">
                                    Rp {{ number_format($venue->minPrice() ?? 0, 0, ',', '.') }}
                                </h2>
                                <p class="small text-muted mb-3">per jam</p>

                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <small>
                                        <strong>Cara Booking:</strong><br>
                                        1. Klik pada sesi waktu yang tersedia<br>
                                        2. Isi form booking<br>
                                        3. Bayar & cetak struk
                                    </small>
                                    @auth
                                        <hr class="my-2">
                                        <small class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Nama Anda akan otomatis terisi
                                        </small>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-star-fill text-warning me-2"></i>Ulasan Pengunjung
                                <span class="text-muted">({{ $venue->review_count }} ulasan)</span>
                            </h3>

                            <div class="row">
                                {{-- KOLOM KIRI: Daftar Review (Bisa dilihat semua orang) --}}
                                <div class="col-md-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            @forelse($venue->reviews as $review)
                                                <div class="mb-3 border-bottom pb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div class="d-flex align-items-center">
                                                            {{-- Avatar User Default --}}
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                style="width: 40px; height: 40px; font-weight: bold;">
                                                                {{ substr($review->user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 fw-bold">{{ $review->user->name }}
                                                                </h6>
                                                                <small
                                                                    class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                            </div>
                                                        </div>
                                                        {{-- Tampilan Bintang --}}
                                                        <div class="text-warning">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="text-muted mb-0">{{ $review->comment }}</p>
                                                </div>
                                            @empty
                                                <div class="text-center py-4">
                                                    <i class="bi bi-chat-square-text text-muted fs-1"></i>
                                                    <p class="text-muted mt-2">Belum ada ulasan untuk venue ini.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                {{-- KOLOM KANAN: Form Input (Hanya User Login & Role 'user') --}}
                                <div class="col-md-4">
                                    @auth
                                        @if (Auth::user()->role === 'user')
                                            <div class="card border-0 shadow-sm bg-light">
                                                <div class="card-body">
                                                    <h5 class="fw-bold mb-3">Tulis Ulasan</h5>
                                                    <form action="{{ route('reviews.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="venue_id" value="{{ $venue->id }}">

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Rating</label>
                                                            <select name="rating" class="form-select" required>
                                                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                                                                <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                                                <option value="2">⭐⭐ (Kurang)</option>
                                                                <option value="1">⭐ (Sangat Buruk)</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Komentar</label>
                                                            <textarea name="comment" class="form-control" rows="3" placeholder="Bagaimana pengalaman Anda main di sini?"
                                                                required></textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="bi bi-send me-1"></i> Kirim Ulasan
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Jika Login tapi Role Admin --}}
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-1"></i> Untuk memberikan ulasan, silakan
                                                login sebagai user dan akun ada sudah pernah booking.
                                            </div>
                                        @endif
                                    @else
                                        {{-- Jika Belum Login --}}
                                        <div class="card border-0 shadow-sm bg-light text-center">
                                            <div class="card-body">
                                                <i class="bi bi-lock-fill fs-1 text-secondary"></i>
                                                <h6 class="mt-3">Ingin memberikan ulasan?</h6>
                                                <p class="small text-muted">Silakan login sebagai penyewa untuk menulis
                                                    ulasan.</p>
                                                <a href="{{ route('login') }}"
                                                    class="btn btn-outline-primary btn-sm">Login
                                                    Sekarang</a>
                                            </div>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Schedules & Booking --}}
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-calendar2-week text-primary me-2"></i>Jadwal & Booking
                    </h3>

                    @php

                    \Carbon\Carbon::setLocale('id');
                    date_default_timezone_set('Asia/Jakarta');

                        $today = \Carbon\Carbon::now();
                        $dates = collect();

                        // Generate 5 hari ke depan (termasuk hari ini)
                        for ($i = 0; $i < 5; $i++) {
                            $date = $today->copy()->addDays($i);

                            $dates->push([
                                'date' => $date,
                                'day_name' => $date->translatedFormat('l'), // Nama hari dalam bahasa Indonesia
                                'date_formatted' => $date->translatedFormat('d F Y'), // Format tanggal
                                'day_key' => strtolower($date->translatedFormat('l')), // Key untuk matching dengan database
                            ]);
                        }

                        // Mapping hari Indonesia ke Inggris untuk Carbon
                        $dayMapping = [
                            'senin' => 'Monday',
                            'selasa' => 'Tuesday',
                            'rabu' => 'Wednesday',
                            'kamis' => 'Thursday',
                            'jumat' => 'Friday',
                            'sabtu' => 'Saturday',
                            'minggu' => 'Sunday',
                        ];
                    @endphp

                    {{-- Date Navigation --}}
                    <div class="date-navigation mb-4">
                        <div class="row g-2">
                            @foreach ($dates as $index => $dateInfo)
                                <div class="col">
                                    <button class="btn btn-date-selector w-100 {{ $index === 0 ? 'active' : '' }}"
                                        data-date="{{ $dateInfo['date']->format('Y-m-d') }}"
                                        data-day="{{ $dateInfo['day_key'] }}"
                                        onclick="filterByDate(this)">
                                        <div class="date-day">{{ $dateInfo['day_name'] }}</div>
                                        <div class="date-month">{{ $dateInfo['date']->format('d') }}
                                            {{ $dateInfo['date']->translatedFormat('M') }}</div>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>



                    {{-- Fields & Schedules Container --}}
                    <div id="schedulesContainer">
                        @foreach ($dates as $dateIndex => $dateInfo)
                            <div class="date-schedules {{ $dateIndex === 0 ? '' : 'd-none' }}"
                                data-date="{{ $dateInfo['date']->format('Y-m-d') }}"
                                data-day="{{ $dateInfo['day_key'] }}">

                                @php
                                    $hasSchedules = false;
                                @endphp

                                @foreach ($venue->fields as $field)
                                    @php
                                        // Get schedules untuk hari ini
                                        $daySchedules = $field->schedules->filter(function ($schedule) use ($dateInfo) {
                                            return strtolower($schedule->day) === $dateInfo['day_key'];
                                        });
                                    @endphp

                                    @if ($daySchedules->count() > 0)
                                        @php
                                            $hasSchedules = true;
                                        @endphp

                                        <div class="card info-card mb-4">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="fw-bold text-primary mb-0">
                                                        <i class="bi bi-grid-3x3-gap me-2"></i>{{ $field->name }}
                                                    </h5>
                                                    <span class="badge bg-info">
                                                        {{ $daySchedules->count() }} Sesi Tersedia
                                                    </span>
                                                </div>

                                                <div class="row g-3">
                                                    @foreach ($daySchedules->sortBy('time_slot') as $schedule)
                                                        @php
                                                            // 1. Ambil Jam Sekarang
                                                            $currentDateTime = \Carbon\Carbon::now();

                                                            // 2. BERSIHKAN FORMAT JAM
                                                            // Jika format di database "12:00 - 13:00", kita pecah dan ambil "12:00" saja
                                                            // explode akan memisahkan berdasarkan tanda "-"
                                                            $timeParts = explode('-', $schedule->time_slot);
                                                            $startTime = trim($timeParts[0]); // Ambil bagian pertama dan hapus spasi: "12:00"

                                                            // 3. Gabungkan Tanggal Loop + Jam Awal Saja
                                                            $bookingDateTime = \Carbon\Carbon::createFromFormat(
                                                                'Y-m-d H:i',
                                                                $dateInfo['date']->format('Y-m-d') . ' ' . $startTime,
                                                                'Asia/Jakarta',
                                                            );

                                                            // 4. Cek Booking Database (Tetap sama)
                                                            $isBooked = $schedule
                                                                ->bookings()
                                                                ->where(
                                                                    'booking_date',
                                                                    $dateInfo['date']->format('Y-m-d'),
                                                                )
                                                                ->where('status', '!=', 'cancelled')
                                                                ->exists();

                                                            // 5. LOGIKA BARU: Cek Lewat Waktu
                                                            // Jika hari ini ADALAH hari yang dipilih, DAN jam sekarang LEBIH BESAR dari jam sesi
                                                            $isPast =
                                                                $dateInfo['date']->isToday() &&
                                                                $currentDateTime->greaterThan($bookingDateTime);

                                                            // Gabungkan status
                                                            $isDisabled = $isBooked || $isPast;
                                                        @endphp

                                                        {{-- Render Tombol --}}
                                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                                            <div class="time-slot-wrapper">
                                                                <button type="button" {{-- Tambahkan class is-past/is-booked untuk styling CSS --}}
                                                                    class="btn btn-time-slot w-100 {{ $isBooked ? 'is-booked' : ($isPast ? 'is-past' : '') }}"
                                                                    {{-- Data attributes --}}
                                                                    data-schedule-id="{{ $schedule->id }}"
                                                                    data-field-id="{{ $field->id }}"
                                                                    data-field-name="{{ $field->name }}"
                                                                    data-time-slot="{{ $schedule->time_slot }}"
                                                                    data-price="{{ $schedule->price }}"
                                                                    data-day="{{ $dateInfo['day_name'] }}"
                                                                    data-booking-date="{{ $dateInfo['date']->format('Y-m-d') }}"
                                                                    {{-- PENTING: Panggil fungsi JS hanya jika TIDAK disabled --}}
                                                                    onclick="if(!this.disabled) openBookingModal(this)"
                                                                    {{-- Atribut Disabled HTML --}}
                                                                    {{ $isDisabled ? 'disabled' : '' }}>

                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <span><i
                                                                                class="bi bi-clock me-1"></i>{{ $schedule->time_slot }}</span>
                                                                        <span class="fw-bold">Rp
                                                                            {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                                                    </div>
                                                                </button>

                                                                {{-- Badge Status --}}
                                                                @if ($isBooked)
                                                                    <span class="booked-badge bg-danger">BOOKED</span>
                                                                @elseif($isPast)
                                                                    <span
                                                                        class="booked-badge bg-secondary">LEWAT</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                @if (!$hasSchedules)
                                    <div class="alert alert-warning text-center">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Tidak ada jadwal tersedia untuk tanggal ini
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // --- NOTIFIKASI SUKSES ---
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif

                // --- NOTIFIKASI ERROR UMUM ---
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                    });
                @endif

                // --- NOTIFIKASI ERROR VALIDASI (FORM) ---
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        html: `
                        <ul style="text-align: left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    });
                @endif

                // --- KHUSUS: SUDAH PERNAH REVIEW ---
                @if (session('already_reviewed'))
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ups, Tunggu Dulu!',
                        text: "{{ session('already_reviewed') }}",
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#ffc107'
                    });
                @endif
            });
        </script>


</x-app-layout>

@include('detail-venue.modal-bookings.modal-booking')
