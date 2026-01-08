{{-- Jika cuma 1 gambar (Full Width) --}}
                @if ($venue->images->count() == 1)
                    <div class="col-12">
                        <div style="height: 400px;">
                            <img src="{{ asset('storage/' . $venue->images[0]->image_path) }}"
                                class="w-100 h-100 rounded cursor-pointer" style="object-fit: cover;"
                                data-bs-toggle="modal" data-bs-target="#galleryModal">
                        </div>
                    </div>

                    {{-- Jika gambar > 1 (Layout Grid) --}}
                @else
                    {{-- KIRI: Gambar Utama (Besar) --}}
                    <div class="col-12 col-md-8 position-relative">
                        {{-- Wrapper div ini penting untuk mobile responsive --}}
                        <div class="h-100 position-relative">
                            <img src="{{ asset('storage/' . $venue->images[0]->image_path) }}" class="gallery-main-img"
                                data-bs-toggle="modal" data-bs-target="#galleryModal" onclick="bsCarousel.to(0)">

                            {{-- MOBILE ONLY BADGE: Indikator jumlah foto --}}
                            {{-- d-md-none artinya: HILANG di layar medium(desktop) ke atas --}}
                            <div class="d-md-none position-absolute bottom-0 end-0 mb-3 me-3">
                                <button class="btn btn-sm btn-dark bg-opacity-75 rounded-pill px-3"
                                    data-bs-toggle="modal" data-bs-target="#galleryModal" onclick="bsCarousel.to(0)">
                                    <i class="bi bi-grid-3x3-gap me-1"></i>
                                    Lihat {{ $venue->images->count() }} Foto
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: Gambar 2 & 3 (Kecil) --}}
                    <div class="col-md-4 d-none d-md-block">
                        {{-- Container Flexbox dengan tinggi fix 400px (di desktop) --}}
                        <div class="d-flex flex-column justify-content-between" style="height: 400px;">

                            {{-- Gambar ke-2 --}}
                            @if (isset($venue->images[1]))
                                <img src="{{ asset('storage/' . $venue->images[1]->image_path) }}"
                                    class="gallery-side-img" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                    onclick="bsCarousel.to(1)">
                            @endif

                            {{-- Gambar ke-3 --}}
                            @if (isset($venue->images[2]))
                                <div class="position-relative gallery-side-img">
                                    <img src="{{ asset('storage/' . $venue->images[2]->image_path) }}"
                                        class="w-100 h-100 rounded cursor-pointer" style="object-fit: cover;"
                                        data-bs-toggle="modal" data-bs-target="#galleryModal"
                                        onclick="bsCarousel.to(2)">

                                    {{-- OVERLAY TEXT (+X Foto Lainnya) --}}
                                    @if ($venue->images->count() > 3)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center rounded cursor-pointer"
                                            style="z-index: 10;" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                            onclick="bsCarousel.to(0)">
                                            <h5 class="text-white fw-bold m-0">
                                                +{{ $venue->images->count() - 3 }} Foto Lainnya
                                            </h5>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                @endif

{{-- Modal Fullscreen Gallery --}}
        <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-transparent border-0 shadow-none">
                    {{-- Tombol Close --}}
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 z-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="modal-body p-0">
                        <div id="carouselGallery" class="carousel slide">
                            <div class="carousel-inner">
                                @foreach ($venue->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            class="d-block w-100 rounded"
                                            style="max-height: 85vh; object-fit: contain;" alt="Venue Image">
                                    </div>
                                @endforeach
                            </div>

                            {{-- Navigasi Panah --}}
                            @if ($venue->images->count() > 1)
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselGallery" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselGallery" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
