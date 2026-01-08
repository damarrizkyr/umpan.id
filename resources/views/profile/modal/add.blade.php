<!-- Start Modal Add -->
<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('venues.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">

                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    {{-- <form action="venues" method="POST"> </form> --}}
                </div>

                <div class="modal-body">
                    @csrf
                    {{-- GAMBAR SECTION --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-images me-2"></i>Gambar Venue (Maksimal 5)</h6>
                        </div>

                        <div class="card-body">
                            <div class="alert alert-info d-flex align-items-center mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Upload minimal 2 gambar, maksimal 5 gambar (2MB per file)</small>
                            </div>

                            {{-- WRAPPER UTAMA: Area ini yang mendeteksi Drag & Drop --}}
                            {{-- Kita pindahkan ID drop-area ke wrapper ini --}}
                            <div id="drop-area-wrapper" class="p-2 rounded" style="transition: all 0.3s ease;">

                                {{-- Kotak Visual (Dashed Box) --}}
                                <div id="visual-drop-box"
                                    class="upload-area border-2 border-dashed rounded p-4 text-center mb-3"
                                    style="border-color: #dee2e6; cursor: pointer; background-color: #fff;">
                                    <i class="bi bi-cloud-upload fs-1 text-primary"></i>
                                    <p class="mb-0 mt-2">Klik atau drag & drop gambar di sini</p>
                                    <small class="text-muted">Format: JPG, PNG, JPEG</small>
                                </div>

                                {{-- Input file tersembunyi --}}
                                <input type="file" name="images[]" id="imageInput" multiple accept="image/*"
                                    class="d-none">

                                {{-- Preview Container (Sekarang ada di DALAM wrapper drop area) --}}
                                <div id="imagePreviewContainer" class="row g-3">
                                    {{-- Preview gambar akan muncul di sini --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: INFORMASI VENUE --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Venue
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">
                                        Nama Venue <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Contoh: Mini Soccer Jaya Abadi" value="{{ old('name') }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">
                                        Deskripsi <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                        placeholder="Deskripsikan venue Anda..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">
                                        Fasilitas <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="facilities" class="form-control @error('facilities') is-invalid @enderror" rows="3"
                                        placeholder="Contoh: Parkir luas, Kantin, Toilet bersih, WiFi gratis, Mushola" required>{{ old('facilities') }}</textarea>
                                    @error('facilities')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: LOKASI --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-geo-alt me-2 text-primary"></i>Lokasi
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-semibold">
                                        Alamat Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        placeholder="Jl. Contoh No. 123" value="{{ old('address') }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">
                                        Kota <span class="text-danger">*</span>
                                    </label>
                                    {{-- tehubung ke file city-select.js --}}
                                    <select name="city"
                                        class="form-select city-select @error('city') is-invalid @enderror"
                                        data-selected="{{ old('city') }}" required>
                                    </select>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: JAM OPERASIONAL --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-clock me-2 text-primary"></i>Jam Operasional
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        Jam Buka <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" name="open_time"
                                        class="form-control @error('open_time') is-invalid @enderror"
                                        value="{{ old('open_time') }}" required>
                                    @error('open_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        Jam Tutup <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" name="close_time"
                                        class="form-control @error('close_time') is-invalid @enderror"
                                        value="{{ old('close_time') }}" required>
                                    @error('close_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- SECTION 5: LAPANGAN & JADWAL --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Lapangan & Jadwal
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Jumlah Lapangan <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="1" max="10" id="fieldCount"
                                    class="form-control" placeholder="Berapa jumlah lapangan?" required>
                                <small class="text-muted">Setelah mengisi, form jadwal akan muncul otomatis</small>
                            </div>

                            <div id="fieldsContainer"></div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add -->
