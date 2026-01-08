<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Booking Saya') }}
        </h2>
    </x-slot> --}}

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Daftar Booking</h3>

                @forelse($bookings as $booking)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="fw-bold">{{ $booking->field->venue->name }}</h5>
                                    <p class="text-muted mb-2">{{ $booking->field->name }}</p>

                                    <div class="mb-2">
                                        <span class="badge bg-primary me-2">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $booking->booking_date->format('d M Y') }}
                                        </span>
                                        <span class="badge bg-info me-2">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $booking->schedule->time_slot }}
                                        </span>
                                        <span class="badge
                                            @if($booking->status == 'confirmed') bg-success
                                            @elseif($booking->status == 'cancelled') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>

                                    <p class="mb-1">
                                        <strong>Kode Booking:</strong> {{ $booking->booking_code }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Total:</strong>
                                        <span class="text-success fw-bold">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    @if($booking->status == 'pending' && $booking->booking_date->greaterThan(now()->addDay()))
                                        <form action="{{ route('bookings.cancel', $booking->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-x-circle me-1"></i>Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        Belum ada riwayat booking
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
