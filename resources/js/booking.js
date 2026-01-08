(function () {
    'use strict';

    // CONFIRM BOOKING (Ubah Status ke Confirmed)
    function confirmBooking(bookingId, bookingCode) {
        if (typeof Swal === 'undefined') {
            alert('SweetAlert2 error.');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Booking?',
            html: `Apakah Anda yakin ingin mengubah status booking <strong>${bookingCode}</strong> menjadi <span class="text-success fw-bold">Confirmed</span>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754', // Warna hijau success
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-lg me-1"></i> Ya, Konfirmasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                // Kirim request ke server
                fetch(`/bookings/${bookingId}/confirm`, {
                    method: 'PATCH', // Menggunakan PATCH untuk update
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem.'
                        });
                    });
            }
        });
    }

    // CANCEL BOOKING (Ubah Status)
    function cancelBooking(bookingId, bookingCode) {
        if (typeof Swal === 'undefined') {
            alert('SweetAlert2 tidak ter-load. Silakan refresh halaman.');
            return;
        }

        Swal.fire({
            title: 'Batalkan Booking?',
            html: `Apakah Anda yakin ingin membatalkan booking <strong>${bookingCode}</strong>?<br><small class="text-muted">Status akan diubah menjadi "Cancelled"</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/bookings/${bookingId}/cancel-by-owner`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat membatalkan booking'
                        });
                    });
            }
        });
    }

    // DELETE BOOKING (Hapus Permanent)
    function deleteBooking(bookingId, bookingCode) {
        if (typeof Swal === 'undefined') {
            alert('SweetAlert2 tidak ter-load. Silakan refresh halaman.');
            return;
        }

        Swal.fire({
            title: 'Hapus Booking?',
            html: `
                <p>Apakah Anda yakin ingin menghapus booking <strong>${bookingCode}</strong>?</p>
                <div class="alert alert-danger mt-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Data akan dihapus permanent dari database dan tidak dapat dikembalikan!
                </div>
            `,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus Permanent!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/bookings/${bookingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus booking'
                        });
                    });
            }
        });
    }

    // EVENT LISTENERS
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded');

        // Button CONFIRM
        document.querySelectorAll('.btn-confirm-booking').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-booking-id');
                const bookingCode = this.getAttribute('data-booking-code');
                confirmBooking(bookingId, bookingCode);
            });
        });

        // Button CANCEL
        document.querySelectorAll('.btn-cancel-booking').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-booking-id');
                const bookingCode = this.getAttribute('data-booking-code');
                cancelBooking(bookingId, bookingCode);
            });
        });

        // Button DELETE
        document.querySelectorAll('.btn-delete-booking').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-booking-id');
                const bookingCode = this.getAttribute('data-booking-code');
                deleteBooking(bookingId, bookingCode);
            });
        });
    });

    // Export untuk debugging
    window.cancelBooking = cancelBooking;
    window.deleteBooking = deleteBooking;
})();
