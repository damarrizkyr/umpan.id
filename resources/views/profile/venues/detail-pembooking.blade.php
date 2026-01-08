<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Statistics Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Booking</h6>
                                    <h3 class="mb-0">{{ $bookings->total() }}</h3>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Confirmed</h6>
                                    <h3 class="mb-0">{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Pending</h6>
                                    <h3 class="mb-0">{{ $bookings->where('status', 'pending')->count() }}</h3>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Pendapatan</h6>
                                    <h3 class="mb-0 small">Rp
                                        {{ number_format($bookings->where('payment_status', 'paid')->sum('total_price'), 0, ',', '.') }}
                                    </h3>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter & Search --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('venues.bookings', $venue->id) }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari nama/kode booking..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="field" class="form-select">
                                <option value="">Semua Lapangan</option>
                                @foreach ($venue->fields as $field)
                                    <option value="{{ $field->id }}"
                                        {{ request('field') == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('venues.bookings', $venue->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Booking List --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Daftar Booking</h5>
                </div>
                <div class="card-body p-0">
                    @if ($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Kode Booking</th>
                                        <th>Tanggal Main</th>
                                        <th>Lapangan</th>
                                        <th>Waktu</th>
                                        <th>Pemesan</th>
                                        <th>Kontak</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <strong class="text-primary">{{ $booking->booking_code }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $booking->created_at->format('d M Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <strong>{{ $booking->booking_date->format('d M Y') }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $booking->booking_date->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $booking->field->name }}</span>
                                            </td>
                                            <td>
                                                <i class="bi bi-clock text-primary me-1"></i>
                                                {{ $booking->schedule->time_slot }}
                                            </td>
                                            <td>
                                                <strong>{{ $booking->customer_name }}</strong>
                                            </td>
                                            <td>
                                                <a href="https://wa.me/{{ $booking->customer_phone }}"
                                                    class="text-success text-decoration-none" target="_blank">
                                                    <i class="bi bi-whatsapp"></i> {{ $booking->customer_phone }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                </strong>
                                                <br>
                                                @if ($booking->payment_status == 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->status == 'confirmed')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Confirmed
                                                    </span>
                                                @elseif($booking->status == 'pending')
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-clock me-1"></i>Pending
                                                    </span>
                                                @elseif($booking->status == 'cancelled')
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Cancelled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if ($booking->status == 'pending')
                                                        <button type="button"
                                                            class="btn btn-outline-success btn-confirm-booking"
                                                            data-booking-id="{{ $booking->id }}"
                                                            data-booking-code="{{ $booking->booking_code }}"
                                                            title="Konfirmasi Booking">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    @endif
                                                        @if ($booking->status == 'confirmed' || $booking->status == 'pending')
                                                            <button type="button" class="btn btn-outline-danger"
                                                                onclick="cancelBooking({{ $booking->id }})">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        @endif

                                                        {{-- Button Delete (hapus permanent) --}}
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-delete-booking"
                                                            data-booking-id="{{ $booking->id }}"
                                                            data-booking-code="{{ $booking->booking_code }}"
                                                            title="Hapus dari Database">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="p-3 border-top">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada booking untuk venue ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
