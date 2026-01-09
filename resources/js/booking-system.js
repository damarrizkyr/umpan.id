// --- 1. HELPER FUNCTIONS (Fungsi Pembantu) ---

// Mengambil elemen HTML berdasarkan ID
function getElemen(id) {
    return document.getElementById(id);
}

// Mengubah angka menjadi format Rupiah
const formatRupiah = (angka) => {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
};

// Mengubah tanggal menjadi format panjang Indonesia
const formatTanggal = (tanggal) => {
    return new Date(tanggal).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Template HTML Struk (Support Download Button)
const buatHtmlStruk = (dataBooking, includeDownloadBtn = false) => {
    const namaVenue = dataBooking.venue_name || 'Umpan.id Sport Venue';
    const alamatVenue = dataBooking.venue_address || 'Alamat Venue';

    // A. Logika Tombol Download (Hanya tampil jika diminta)
    const downloadBtnHtml = includeDownloadBtn
        ? `<div class="no-print" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
             <button onclick="downloadReceipt()" style="padding: 12px 24px; background: #198754; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 8px;">
               <span>Download Gambar</span>
             </button>
             <p style="font-size: 11px; color: #888; margin-top: 8px;">
                (Klik tombol di atas untuk simpan gambar, atau tekan Ctrl+P untuk print PDF)
             </p>
           </div>`
        : '';

    // B. Script Download (Disisipkan langsung ke window baru)
    const scriptDownload = includeDownloadBtn
        ? `<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
           <script>
             async function downloadReceipt() {
               const btn = document.querySelector('button');
               const originalText = btn.innerText;
               btn.innerText = 'Memproses...';
               btn.disabled = true;

               const element = document.querySelector('.kotak-struk');
               try {
                 const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#fff' });
                 const link = document.createElement('a');
                 link.href = canvas.toDataURL('image/png');
                 link.download = 'Struk-${dataBooking.booking_code}.png';
                 link.click();
               } catch(err) {
                   console.error(err);
                   alert('Gagal mendownload gambar.');
               } finally {
                   btn.innerText = originalText;
                   btn.disabled = false;
               }
             }
           </script>`
        : '';

    // C. Return HTML Lengkap
    return `<html>
                <head>
                    <title>Struk Booking - ${dataBooking.booking_code}</title>
                    ${scriptDownload}
                    <style>
                        body { font-family: 'Courier New', monospace; padding: 40px; background: #f8f9fa; display: flex; justify-content: center; }
                        .kotak-struk {
                            border: 2px dashed #333;
                            padding: 30px;
                            width: 380px;
                            background: #fff;
                            text-align: center;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        }
                        h3 { margin: 0 0 10px 0; line-height: 1.4; color: #000; }
                        .alamat-struk { font-size: 12px; color: #555; margin-bottom: 20px; line-height: 1.4; }
                        .baris-data { display: flex; justify-content: space-between; margin: 8px 0; font-size: 14px; color: #333; }
                        hr { border: none; border-top: 1px dashed #333; margin: 15px 0; }

                        @media print {
                            .no-print { display: none !important; }
                            body { background: #fff; padding: 0; }
                            .kotak-struk { box-shadow: none; border: 2px dashed #000; width: 100%; max-width: 350px; margin: 0 auto; }
                        }
                    </style>
                </head>
                <body>
                    <div style="display:flex; flex-direction:column;">
                        <div class="kotak-struk">
                            <h3>STRUK BOOKING<br>${namaVenue}</h3>
                            <p class="alamat-struk">${alamatVenue}</p>
                            <hr>
                            <div class="baris-data">
                                <span>Kode Booking</span>
                                <b>${dataBooking.booking_code}</b>
                            </div>
                            <div class="baris-data">
                                <span>Tanggal Main</span>
                                <span>${formatTanggal(dataBooking.booking_date)}</span>
                            </div>
                            <div class="baris-data">
                                <span>Penyewa</span>
                                <span>${dataBooking.customer_name}</span>
                            </div>
                            <div class="baris-data">
                                <span>Lapangan</span>
                                <span>${dataBooking.field_name}</span>
                            </div>
                            <div class="baris-data">
                                <span>Jam</span>
                                <span>${dataBooking.time_slot}</span>
                            </div>
                            <hr>
                            <div class="baris-data" style="font-weight:bold; font-size: 16px;">
                                <span>TOTAL</span>
                                <span>${formatRupiah(dataBooking.total_price)}</span>
                            </div>
                            <hr>
                            <small style="color:#777;">Terima kasih telah booking!</small>
                        </div>
                        ${downloadBtnHtml}
                    </div>
                </body>
            </html>`;
};


// --- 2. GLOBAL FUNCTIONS (Dipanggil dari HTML onclick) ---

window.openBookingModal = (tombolYangDiklik) => {
    const dataTombol = tombolYangDiklik.dataset;
    const daftarIdInput = ['scheduleId', 'fieldId', 'bookingDate'];

    daftarIdInput.forEach(idInput => {
        getElemen(idInput).value = dataTombol[idInput];
    });

    getElemen('displayFieldName').value = dataTombol.fieldName;
    getElemen('displayDay').value = dataTombol.day;
    getElemen('displayTimeSlot').value = dataTombol.timeSlot;

    getElemen('displayDate').value = formatTanggal(dataTombol.bookingDate);
    getElemen('displayPrice').value = formatRupiah(dataTombol.price);
    getElemen('totalPayment').innerText = formatRupiah(dataTombol.price);

    new bootstrap.Modal(getElemen('bookingModal')).show();
};

window.filterByDate = (tombolTanggal) => {
    document.querySelectorAll('.btn-date-selector').forEach(tombol => {
        tombol.classList.remove('active');
    });
    tombolTanggal.classList.add('active');
    const tanggalDipilih = tombolTanggal.dataset.date;

    document.querySelectorAll('.date-schedules').forEach(divJadwal => {
        const harusDisembunyikan = divJadwal.dataset.date !== tanggalDipilih;
        divJadwal.classList.toggle('d-none', harusDisembunyikan);
    });
};

window.goToGallerySlide = (indexSlide) => {
    const carousel = bootstrap.Carousel.getOrCreateInstance(getElemen('carouselGallery'));
    carousel.to(indexSlide);
};

// --- FUNGSI CETAK STRUK (UPDATED) ---
// Membuka tab baru (BUKAN POPUP) -> Tampil Struk -> Ada tombol Download
window.printReceipt = (dataBooking) => {
    // Dengan menghapus parameter 'width' dan 'height', browser akan membukanya sebagai TAB BARU
    const jendelaPrint = window.open('', '_blank');

    if (jendelaPrint) {
        jendelaPrint.document.write(buatHtmlStruk(dataBooking, true));
        jendelaPrint.document.close();
        jendelaPrint.focus();
    } else {
        alert('Pop-up diblokir! Izinkan pop-up untuk melihat struk.');
    }
};


// --- 3. EVENT LISTENERS (Saat DOM Ready) ---
document.addEventListener('DOMContentLoaded', () => {

    // A. Handle Submit Form
    const formBooking = getElemen('bookingForm');

    if (formBooking) {
        formBooking.onsubmit = async (event) => {
            event.preventDefault();

            // 1. Validasi Input HP
            const inputHp = getElemen('customerPhone');
            const nilaiHp = inputHp.value.trim();
            const elemenError = getElemen('phoneErrorMsg');

            inputHp.classList.remove('is-invalid');

            if (!nilaiHp.startsWith('08')) {
                elemenError.textContent = 'Harus diawali "08"';
                inputHp.classList.add('is-invalid');
                return inputHp.focus();
            }
            if (nilaiHp.length < 11 || nilaiHp.length > 13) {
                elemenError.textContent = 'Min 11, Max 13 digit';
                inputHp.classList.add('is-invalid');
                return inputHp.focus();
            }

            // 2. Proses Kirim Data
            const tombolSubmit = formBooking.querySelector('button[type="submit"]');
            const teksTombolLama = tombolSubmit.innerText;

            tombolSubmit.disabled = true;
            tombolSubmit.innerText = 'Sedang Memproses...';

            try {
                const response = await fetch(formBooking.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: new FormData(formBooking)
                });

                if (!response.ok) {
                    const dataError = await response.json();
                    throw new Error(dataError.message || 'Terjadi Kesalahan Server');
                }

                const dataJson = await response.json();

                if (dataJson.success) {
                    bootstrap.Modal.getInstance(getElemen('bookingModal')).hide();
                    window.dataBookingSementara = dataJson.data;

                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Berhasil!',
                        html: `<p style="margin-bottom:15px;">Kode Booking: <b>${dataJson.data.booking_code}</b></p>
                               <div style="display:flex; justify-content:center;">
                                   <button onclick="window.printReceipt(window.dataBookingSementara)" class="btn btn-primary">
                                        <i class="bi bi-receipt"></i> Lihat / Cetak Struk
                                   </button>
                               </div>`,
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup'
                    }).then((hasilSweetAlert) => {
                        if(hasilSweetAlert.isConfirmed || hasilSweetAlert.isDismissed) {
                            location.reload();
                        }
                    });
                }

            } catch (error) {
                Swal.fire('Gagal', error.message, 'error');
            } finally {
                tombolSubmit.disabled = false;
                tombolSubmit.innerText = teksTombolLama;
            }
        };
    }

    // B. Reset Form saat Modal Tutup
    const elemenModal = getElemen('bookingModal');
    if (elemenModal) {
        elemenModal.addEventListener('hidden.bs.modal', () => {
            const daftarInputReset = ['customerName', 'customerPhone'];
            daftarInputReset.forEach(idInput => {
                const elemenInput = getElemen(idInput);
                if (elemenInput) {
                    elemenInput.value = elemenInput.dataset.default || '';
                    elemenInput.classList.remove('is-invalid');
                }
            });
        });
    }
});
