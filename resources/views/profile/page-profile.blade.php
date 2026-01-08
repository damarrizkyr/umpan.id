<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- nampilin profile user --}}
            <div class="bg-white shadow sm:rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold"> {{ Auth::user()->name }} </h2>
                <p class="text-sm text-gray-500"> {{ Auth::user()->email }} </p>
            </div>

            {{-- Nampilin data lapangan user --}}
            @if (Auth::user()->role === 'admin')
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Lapangan Saya</h3>
                        @if (Auth::user()->role === 'admin')
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add">
                                <i class="bi bi-plus-circle"></i> Tambah Lapangan
                            </button>

                        @endif
                    </div>
                    <div class="row g-4">
                        @forelse ($venues as $venue)
                            <div class="col-md-4 col-sm-6">
                                <div class="card h-100 shadow-sm border-0">
                                    {{-- Gambar dengan ukuran tetap --}}
                                    <div class="position-relative overflow-hidden" style="height: 200px;">
                                        <img src="{{ $venue->images->first() ? asset('storage/' . $venue->images->first()->image_path) : asset('images/default.jpg') }}"
                                            class="card-img-top w-100 h-100"
                                            style="object-fit: cover; object-position: center;" alt="{{ $venue->name }}">
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        {{-- Header Card --}}
                                        <div class="mb-3">
                                            <h5 class="card-title fw-bold mb-3">
                                                {{ $venue->name }}
                                            </h5>
                                            <div class="card d-flex flex-column mb-2 p-2">

                                                <p class="card-text small">Deskripsi</p>
                                                <p class="card-text text-muted small" style="height: 30px; overflow: hidden;">
                                                    {{ Str::limit($venue->description, 100) }}
                                                </p>
                                                <p class="card-text small">Fasilitas</p>
                                                <p class="card-text text-muted small" style="height: 30px; overflow: hidden;">
                                                    {{ Str::limit($venue->facilities, 100) }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Info Venue --}}
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center text-muted small mb-1">
                                                <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                                                <span>{{ $venue->address }}, {{ ucfirst($venue->city) }}</span>
                                            </div>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="bi bi-clock-fill me-2 text-primary"></i>
                                                <span>{{ \Carbon\Carbon::parse($venue->open_time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($venue->close_time)->format('H:i') }}</span>
                                            </div>
                                        </div>

                                        {{-- Jadwal Lapangan - Scrollable --}}
                                        <div class="border rounded p-2 mb-3" style="max-height: 200px; overflow-y: auto;">
                                            @forelse ($venue->fields as $field)
                                                <div class="mb-2">
                                                    <h6 class="fw-bold text-primary mb-1" style="font-size: 0.9rem;">
                                                        <i class="bi bi-grid-3x3-gap-fill me-1"></i> {{ $field->name }}
                                                    </h6>

                                                    @php
                                                        $grouped = $field->schedules->groupBy('day');
                                                    @endphp

                                                    @foreach ($grouped as $day => $schedules)
                                                        <div class="ms-3 mb-2">
                                                            <span class="badge bg-secondary mb-1"
                                                                style="font-size: 0.75rem;">
                                                                {{ ucfirst($day) }}
                                                            </span>
                                                            <ul class="list-unstyled ms-2 mb-0" style="font-size: 0.8rem;">
                                                                @foreach ($schedules as $schedule)
                                                                    <li class="d-flex justify-content-between text-muted">
                                                                        <span>{{ $schedule->time_slot }}</span>
                                                                        <span class="fw-semibold text-success">
                                                                            Rp
                                                                            {{ number_format($schedule->price, 0, ',', '.') }}
                                                                        </span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @empty
                                                <p class="text-muted text-center small mb-0">Belum ada jadwal</p>
                                            @endforelse
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="mt-auto d-flex gap-2">
                                            <a href="{{ route('venues.bookings', $venue->id) }}"
                                                class="btn btn-info btn-sm flex-fill">
                                                <i class="bi bi-calendar-check"></i> Booking
                                            </a>
                                            <button class="btn btn-warning btn-sm flex-fill" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit{{ $venue->id }}">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm flex-fill" data-bs-toggle="modal"
                                                data-bs-target="#modal-delete{{ $venue->id }}">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Anda belum memiliki lapangan. Klik tombol "Tambah Lapangan" untuk memulai.
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $venues->links() }}
                    </div>
                </div>
            @endif


        </div>

    </div>

    {{-- --}}

    @if (Session::has('add'))
        <script>
            Swal.fire({
                title: "Success!",
                text: "Data has been added.",
                icon: "success",
                timer: 2000
            });
        </script>
    @elseif (Session::has('update'))
        <script>
            Swal.fire({
                title: "Success!",
                text: "Data has been updated.",
                icon: "success",
                timer: 2000
            });
        </script>
    @elseif (Session::has('delete'))
        <script>
            Swal.fire({
                title: "Success!",
                text: "Data has been deleted.",
                icon: "success",
                timer: 2000
            });
        </script>
    @elseif ($errors->any())
        <script>
            Swal.fire({
                title: "Oops...",
                text: "Something went wrong.",
                icon: "error",
                timer: 2000
            });
        </script>
    @endif

</x-app-layout>
{{-- MODAL ADD / EDIT / DELETE --}}
@include('profile.modal.edit')
@include('profile.modal.delete')
@include('profile.modal.add')
