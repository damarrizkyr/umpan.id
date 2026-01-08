{{-- start modal edit --}}
@foreach ($venues as $venue)
    <div class="modal fade" id="modal-edit{{ $venue->id }}" tabindex="-1"
        aria-labelledby="modalEditLabel{{ $venue->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditLabel{{ $venue->id }}">Edit Venue</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('venues.update', $venue->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        {{-- GAMBAR --}}
                        <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-images me-2"></i>Gambar Venue (Maksimal 5)</h6>
                        </div>


                        <div class="card-body">
                            {{-- Preview gambar existing --}}
                            @if($venue->images->count() > 0)
                                <div class="mb-2">
                                    <small class="text-muted">Gambar saat ini:</small>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach($venue->images as $img)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                                     class="img-thumbnail"
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-image-btn"
                                                        data-image-id="{{ $img->id }}"
                                                        style="padding: 2px 6px; font-size: 10px;">
                                                    ✕
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
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

                        {{-- NAMA LAPANGAN --}}
                        <div class="mb-3">
                            <label for="name{{ $venue->id }}" class="form-label">Nama Lapangan</label>
                            <input type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name{{ $venue->id }}"
                                value="{{ old('name', $venue->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="mb-3">
                            <label for="description{{ $venue->id }}" class="form-label">Deskripsi</label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                id="description{{ $venue->id }}"
                                rows="3" required>{{ old('description', $venue->description) }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- FASILITAS --}}
                        <div class="mb-3">
                            <label for="facilities{{ $venue->id }}" class="form-label">Fasilitas</label>
                            <textarea name="facilities"
                                class="form-control @error('facilities') is-invalid @enderror"
                                id="facilities{{ $venue->id }}"
                                rows="3"
                                required>{{ old('facilities', $venue->facilities) }}</textarea>
                            <small class="text-muted">Contoh: Parkir luas, Kantin, Toilet bersih, WiFi</small>
                            @error('facilities')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- ALAMAT --}}
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address', $venue->address) }}" required>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- KOTA --}}
                        <div class="mb-3">
                            <label class="form-label">Kota</label>
                            <select name="city"
                                class="form-control city-select @error('city') is-invalid @enderror"
                                data-selected="{{ old('city', $venue->city) }}"
                                required>
                            </select>
                            @error('city')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- JAM BUKA & TUTUP --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jam Buka</label>
                                <input type="time" name="open_time"
                                    class="form-control @error('open_time') is-invalid @enderror"
                                    value="{{ old('open_time', \Carbon\Carbon::parse($venue->open_time)->format('H:i')) }}"
                                    required>
                                @error('open_time')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jam Tutup</label>
                                <input type="time" name="close_time"
                                    class="form-control @error('close_time') is-invalid @enderror"
                                    value="{{ old('close_time', \Carbon\Carbon::parse($venue->close_time)->format('H:i')) }}"
                                    required>
                                @error('close_time')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Jadwal & Harga Lapangan</h4>
                            <button type="button" class="btn btn-success btn-sm add-new-field" data-venue-id="{{ $venue->id }}">
                                <i class="bi bi-plus-circle"></i> Tambah Lapangan Baru
                            </button>
                        </div>

                        {{-- Container untuk existing fields --}}
                        <div id="existing-fields-{{ $venue->id }}">
                            @foreach ($venue->fields as $field)
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="text-primary mb-0">{{ $field->name }}</h5>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-field"
                                                    data-field-id="{{ $field->id }}">
                                                <i class="bi bi-trash"></i> Hapus Lapangan
                                            </button>
                                        </div>

                                        <div class="schedule-wrapper" data-field="{{ $field->id }}">

                                            @php
                                                $grouped = $field->schedules->groupBy('day');
                                            @endphp

                                            @foreach ($grouped as $day => $schedules)
                                                <div class="day-block border rounded p-3 mt-3">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <strong>{{ ucfirst($day) }}</strong>
                                                        <button type="button" class="btn btn-sm btn-danger remove-day">
                                                            Hapus Hari
                                                        </button>
                                                    </div>

                                                    @foreach ($schedules as $schedule)
                                                        <div class="row mt-2 align-items-center schedule-row">
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                    name="prices[{{ $field->id }}][{{ $day }}][{{ $schedule->id }}][time]"
                                                                    value="{{ $schedule->time_slot }}"
                                                                    placeholder="08:00-09:00"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number"
                                                                    name="prices[{{ $field->id }}][{{ $day }}][{{ $schedule->id }}][price]"
                                                                    value="{{ $schedule->price }}"
                                                                    placeholder="Harga"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger remove-slot">
                                                                    ✕
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary mt-2 add-slot">
                                                        <i class="bi bi-plus-circle"></i> Tambah Jam
                                                    </button>
                                                </div>
                                            @endforeach

                                        </div>

                                        <button type="button" class="btn btn-sm btn-success mt-3 add-day"
                                            data-field="{{ $field->id }}">
                                            + Tambah Hari
                                        </button>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Container untuk new fields --}}
                        <div id="new-fields-container-{{ $venue->id }}"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
{{-- end modal edit --}}
