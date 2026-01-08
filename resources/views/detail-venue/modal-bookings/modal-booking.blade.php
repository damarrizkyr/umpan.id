{{-- Modal Booking --}}
@if (!Auth::check() || (Auth::check() && Auth::user()->role === 'user'))
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-check me-2"></i>Form Booking
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="bookingForm" action="{{ route('bookings.store') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="schedule_id" id="scheduleId">
                        <input type="hidden" name="field_id" id="fieldId">
                        <input type="hidden" name="booking_date" id="bookingDate">

                        {{-- Input Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Pembooking</label>
                            <input type="text" name="customer_name" id="customerName" class="form-control" required
                                value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                data-default="{{ Auth::check() ? Auth::user()->name : '' }}" {{-- Tambahkan ini --}}
                                placeholder="Masukkan nama Anda">
                        </div>

                        {{-- Input No HP --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Handphone</label>
                            <input type="tel" name="customer_phone" id="customerPhone" class="form-control" required
                                value="{{ Auth::check() && Auth::user()->phone ? Auth::user()->phone : '' }}"
                                data-default="{{ Auth::check() && Auth::user()->phone ? Auth::user()->phone : '' }}"
                                placeholder="08xxxxxxxxxx" maxlength="13"
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.classList.remove('is-invalid')">
                            {{-- oninput di atas: Hapus huruf & Hapus error merah saat ngetik --}}

                            {{-- Pesan Error Validasi (Muncul via JS) --}}
                            <div class="invalid-feedback" id="phoneErrorMsg">
                                Nomor tidak valid.
                            </div>

                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                *Wajib diawali 08, min 11 digit, max 13 digit.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lapangan</label>
                            <input type="text" id="displayFieldName" class="form-control" readonly>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Hari</label>
                                <input type="text" id="displayDay" class="form-control" readonly>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tanggal</label>
                                <input type="text" id="displayDate" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sesi Waktu</label>
                            <input type="text" id="displayTimeSlot" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="text" id="displayPrice" class="form-control" readonly>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Total Pembayaran:</span>
                                <span class="fs-5 fw-bold text-primary" id="totalPayment"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-credit-card me-1"></i>Bayar & Cetak Struk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
