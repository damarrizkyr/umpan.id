<x-app-layout>
    <div id="carouselExampleDark" class="carousel carousel-dark slide">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="10000">
                <img src="{{ asset('images/download ronaldo.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-block">
                    <h4 class="fs-4 fw-bold">Main Bersama Teman</h4>
                    <p>Wujudkan pengalaman olahraga yang menyenangkan.</p>
                </div>
            </div>
        </div>
    </div>
    <div  id="venue">
        {{-- Search Section --}}
        <div class="search-section">
            <div class="container">
                <div class="search-box">
                    <form action="/" method="GET">
                        <div class="row g-2 search-input-group align-items-center">
                            <div class="col-md-7">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari nama lapangan..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="city" class="form-select">
                                    <option value="">Semua Kota</option>
                                    <option value="medan" {{ request('city') == 'medan' ? 'selected' : '' }}>Medan</option>
                                    <option value="aceh" {{ request('city') == 'aceh' ? 'selected' : '' }}>Aceh</option>
                                    <option value="jakarta" {{ request('city') == 'jakarta' ? 'selected' : '' }}>Jakarta
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-search w-100">
                                    <i class="bi bi-search me-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Venues Section --}}

        <div class="venues-section">
            <div class="container my-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">
                        <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
                        Lapangan Terbaru
                    </h2>
                    <span class="badge bg-red-900 fs-6">{{ $venues->count() }} Lapangan</span>
                </div>

                <div class="row g-4">
                    @forelse ($venues as $venue)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card venue-card h-100">
                                <a href="{{ route('venues.show', $venue->slug) }}">
                                    <div class="position-relative">
                                        <img src="{{ $venue->images->first() ? asset('storage/' . $venue->images->first()->image_path) : 'https://via.placeholder.com/400x250?text=No+Image' }}"
                                            class="card-img-top venue-image" alt="{{ $venue->name }}">
                                    </div>
                                    <div class="venue-info">
                                        <p class="venue-title-dashboard">{{ $venue->name }}</p>

                                        <div class="venue-meta">
                                            <span>
                                                <i class="bi bi-geo-alt-fill text-primary"></i>
                                                {{ ucfirst($venue->city) }}
                                            </span>
                                            <span>
                                                <i class="bi bi-grid-3x3-gap-fill text-info"></i>
                                                {{ $venue->fields->count() }} Lapangan
                                            </span>
                                            <span>
                                                <i class="bi bi-star-fill text-warning"></i>
                                                {{ $venue->average_rating }}
                                                <small class="text-muted">({{ $venue->review_count }})</small>
                                            </span>
                                        </div>

                                        <div class="venue-description-dashboard">
                                            <span>
                                                Deskripsi
                                            </span> <br>
                                            <p class="text-muted small mb-2">
                                                {{ Str::limit($venue->description, 80) }}
                                            </p>
                                        </div>


                                        <div class="d-grid">
                                            <div class="price-tag">
                                                <small>Mulai dari </small>
                                                <strong>Rp
                                                    {{ number_format($venue->minPrice() ?? 0, 0, ',', '.') }}</strong>
                                                <small>/Sesi</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="no-venues">
                                <i class="bi bi-inbox"></i>
                                <h4 class="text-muted">Belum ada lapangan tersedia</h4>
                                <p class="text-muted">Coba lagi nanti atau ubah filter pencarian</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($venues->hasPages())
                    <div class="d-flex justify-content-center my-5">
                        {{ $venues->links() }}
                    </div>
                @endif

                <div class="container">
                    <div class="m-10">
                        <div id="faq">
                            <h2 class="fw-bold mb-4 text-center">Frequently Asked Questions</h2>
                            <hr>
                            <div class="accordion my-3" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne">
                                            Bagaimana cara memesan lapangan?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Untuk memesan lapangan, cukup cari lapangan yang Anda inginkan, pilih tanggal
                                            dan waktu, lalu ikuti petunjuk pemesanan di halaman lapangan tersebut.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion my-3" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo">
                                            Apakah saya harus login untuk memesan?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Tidak, Anda dapat memesan lapangan tanpa login. Namun, jika Anda login,
                                            data Anda akan otomatis terisi dan Anda dapat melihat riwayat booking.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion my-3" id="faqAccordion4">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                            Apakah saya bisa membatalkan atau mengubah jadwal (Reschedule)?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse"
                                        aria-labelledby="headingFour" data-bs-parent="#faqAccordion4">
                                        <div class="accordion-body">
                                            Pembatalan atau perubahan jadwal hanya dapat dilakukan maksimal <strong>24
                                                jam sebelum jam main</strong> diriwyatbooking. Silakan hubungi Admin
                                            melalui WhatsApp dengan menyertakan Kode Booking Anda untuk proses lebih
                                            lanjut.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
