{{-- Modal Delete dengan Detail Enhanced --}}
@foreach ($venues as $venue)
    <div class="modal fade" id="modal-delete{{ $venue->id }}" tabindex="-1"
         aria-labelledby="modalDeleteLabel{{ $venue->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

                {{-- Header dengan icon warning --}}
                <div class="modal-header bg-gradient-danger text-white border-0" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <h5 class="modal-title d-flex align-items-center" id="modalDeleteLabel{{ $venue->id }}">
                        <i class="bi bi-exclamation-triangle-fill fs-3 me-2 animate__animated animate__wobble"></i>
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Body dengan detail venue --}}
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-trash text-danger" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>

                    <div class="alert alert-warning border-warning border-start border-4 bg-warning bg-opacity-10" role="alert">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill text-warning me-2 fs-5"></i>
                            <div>
                                <strong class="text-warning">Peringatan!</strong>
                                <p class="mb-0 text-muted small">Tindakan ini bersifat permanen dan tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-center mb-3 fw-semibold">
                        Anda yakin ingin menghapus venue berikut?
                    </p>

                    {{-- Venue Info Card --}}
                    <div class="card border-danger border-2 mb-3 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                {{-- Gambar --}}
                                <div class="position-relative me-3">
                                    <img src="{{ $venue->images->first() ? asset('storage/' . $venue->images->first()->image_path) : asset('images/default.jpg') }}"
                                         class="rounded shadow-sm"
                                         style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #dc3545;"
                                         alt="{{ $venue->name }}">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <i class="bi bi-x-lg"></i>
                                    </span>
                                </div>

                                {{-- Detail --}}
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 text-danger fw-bold">
                                        <i class="bi bi-building me-1"></i>{{ $venue->name }}
                                    </h6>

                                    <div class="small">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                                                <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($venue->city) }}
                                            </span>
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="bi bi-tag-fill me-1"></i>{{ str_replace('_', ' ', ucfirst($venue->type)) }}
                                            </span>
                                        </div>
                                        <div class="text-muted">
                                            <i class="bi bi-grid-3x3-gap-fill text-info me-1"></i>
                                            <strong>{{ $venue->fields->count() }}</strong> Lapangan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Warning List --}}
                    <div class="bg-light rounded-3 p-3 border">
                        <p class="mb-2 fw-semibold text-danger d-flex align-items-center">
                            <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                            Yang akan dihapus secara permanen:
                        </p>
                        <ul class="mb-0 small list-unstyled ms-4">
                            <li class="mb-2">
                                <i class="bi bi-check2-circle text-muted me-2"></i>
                                Semua data venue
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check2-circle text-muted me-2"></i>
                                <strong class="text-danger">{{ $venue->images->count() }}</strong> gambar yang tersimpan
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check2-circle text-muted me-2"></i>
                                <strong class="text-danger">{{ $venue->fields->count() }}</strong> lapangan beserta jadwalnya
                            </li>
                            @php
                                $totalSchedules = $venue->fields->sum(function($field) {
                                    return $field->schedules->count();
                                });
                            @endphp
                            <li class="mb-0">
                                <i class="bi bi-check2-circle text-muted me-2"></i>
                                <strong class="text-danger">{{ $totalSchedules }}</strong> jadwal booking
                            </li>
                        </ul>
                    </div>

                    <div class="alert alert-danger border-0 mt-3 mb-0" role="alert">
                        <small class="d-flex align-items-center">
                            <i class="bi bi-shield-exclamation me-2 fs-5"></i>
                            <span>Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>. Pastikan Anda benar-benar yakin sebelum melanjutkan.</span>
                        </small>
                    </div>
                </div>

                {{-- Footer dengan tombol --}}
                <div class="modal-footer border-0 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-left me-1"></i>Tidak, Batalkan
                    </button>
                    <form action="{{ route('venues.destroy', $venue->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-trash me-1"></i>Ya, Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
