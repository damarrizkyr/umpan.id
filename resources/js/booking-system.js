// --- 1. HELPER FUNCTIONS (Fungsi Pembantu) ---
// // Mengambil elemen HTML berdasarkan ID
function getElemen(id) {
    return document.getElementById(id);
}


// Template HTML Struk (sebelumnya: getReceiptHtml)
const buatHtmlStruk = (dataBooking) => {
    // Ambil nama tempat, jika tidak ada pakai default 'Venue Sport'
    const elemenJudul = document.querySelector('.detail-venue-title');
    const namaVenue = elemenJudul ? elemenJudul.innerText : 'Venue Sport';

    return `<html>
                <head>
                    <style>
                        body { font-family: monospace; padding: 20px; }
                        .kotak-struk { border: 2px dashed #000; padding: 20px; width: 350px; margin: 0 auto; background: #fff; text-align: center; }
                        .baris-data { display: flex; justify-content: space-between; margin: 5px 0; font-size: 14px; }
                        hr { border-top: 1px dashed #000; }
                    </style>
                </head>
                <body>
                    <div class="kotak-struk">
                        <h3>STRUK BOOKING<br>${namaVenue}</h3>
                        <hr>
                        <div class="baris-data">
                            <span>Kode</span><b>${dataBooking.booking_code}</b>
                        </div>
                        <div class="baris-data">
                            <span>Tgl</span>${formatTanggal(dataBooking.booking_date)}
                        </div>
                        <div class="baris-data">
                            <span>Nama</span>${dataBooking.customer_name}
                        </div>
                        <div class="baris-data">
                            <span>Lapangan</span>${dataBooking.field_name}
                        </div>
                        <div class="baris-data">
                            <span>Jam</span>${dataBooking.time_slot}
                        </div>
                        <hr>
                        <div class="baris-data" style="font-weight:bold">
                            <span>TOTAL</span>${formatRupiah(dataBooking.total_price)}
                        </div>
                        <hr><small>Terima kasih!</small>
                    </div>
                </body>
            </html>`;
};

// --- 2. GLOBAL FUNCTIONS (Dipanggil dari HTML onclick) ---

// Buka Modal & Isi Data
window.openBookingModal = (tombolYangDiklik) => {
    // Mengambil data-atribut dari tombol (sebelumnya: d)
    const dataTombol = tombolYangDiklik.dataset;

    // Mengisi Input Hidden & Tampilan Form
    // (sebelumnya singkatan k diganti jadi idInput)
    const daftarIdInput = ['scheduleId', 'fieldId', 'bookingDate'];
    daftarIdInput.forEach(idInput => {
        getElemen(idInput).value = dataTombol[idInput];
    });

    getElemen('displayFieldName').value = dataTombol.fieldName;
    getElemen('displayDay').value = dataTombol.day;
    getElemen('displayTimeSlot').value = dataTombol.timeSlot;

    // Format tampilan agar enak dilihat user
    getElemen('displayDate').value = dataTombol.formattedDate;
    getElemen('displayPrice').value = dataTombol.formattedPrice;
    getElemen('totalPayment').innerText = dataTombol.formattedPrice;

    // Tampilkan Modal Bootstrap
    new bootstrap.Modal(getElemen('bookingModal')).show();
};

// Filter Jadwal per Tanggal
window.filterByDate = (tombolTanggal) => {
    // Hapus kelas 'active' dari semua tombol tanggal
    document.querySelectorAll('.btn-date-selector').forEach(tombol => {
        tombol.classList.remove('active');
    });

    // Tambah kelas 'active' ke tombol yang baru diklik
    tombolTanggal.classList.add('active');

    // Ambil tanggal yang dipilih
    const tanggalDipilih = tombolTanggal.dataset.date;

    // Sembunyikan/Tampilkan jadwal sesuai tanggal
    document.querySelectorAll('.date-schedules').forEach(divJadwal => {
        // Jika tanggal jadwal TIDAK SAMA dengan tanggal dipilih, maka sembunyikan (d-none)
        const harusDisembunyikan = divJadwal.dataset.date !== tanggalDipilih;
        divJadwal.classList.toggle('d-none', harusDisembunyikan);
    });
};

// Navigasi Galeri (sebelumnya: idx diganti indexSlide)
window.goToGallerySlide = (indexSlide) => {
    const carousel = bootstrap.Carousel.getOrCreateInstance(getElemen('carouselGallery'));
    carousel.to(indexSlide);
};

// Print Struk (sebelumnya: w diganti jendelaPrint)
window.printReceipt = (dataBooking) => {
    const jendelaPrint = window.open();
    jendelaPrint.document.write(buatHtmlStruk(dataBooking));
    jendelaPrint.document.close();

    // Tunggu sebentar agar loading selesai baru print
    setTimeout(() => {
        jendelaPrint.print();
        jendelaPrint.close();
    }, 500);
};

// Download Gambar Struk
window.downloadReceiptImage = async (dataBooking) => {
    // Buat elemen div sementara (container)
    const containerSementara = document.createElement('div');
    containerSementara.innerHTML = buatHtmlStruk(dataBooking);
    document.body.appendChild(containerSementara);

    try {
        // Konversi HTML ke Canvas (Gambar)
        const canvas = await html2canvas(containerSementara.querySelector('.kotak-struk'), {
            scale: 2,
            backgroundColor: '#fff'
        });

        // Buat link download palsu
        const linkDownload = document.createElement('a');
        linkDownload.href = canvas.toDataURL();
        linkDownload.download = `Struk-${dataBooking.booking_code}.png`;
        linkDownload.click(); // Klik otomatis

        Swal.fire({
            icon: 'success',
            title: 'Berhasil Didownload!',
            timer: 1000,
            showConfirmButton: false
        });

    } catch(error) {
        console.error(error);
    }

    // Hapus elemen sementara
    containerSementara.remove();
};

// --- 3. EVENT LISTENERS (Saat DOM Ready) ---
document.addEventListener('DOMContentLoaded', () => {

    // A. Handle Submit Form
    const formBooking = getElemen('bookingForm');

    if (formBooking) {
        formBooking.onsubmit = async (event) => {
            event.preventDefault(); // Mencegah reload halaman

            // 1. Validasi Input HP
            const inputHp = getElemen('customerPhone');
            const nilaiHp = inputHp.value.trim();
            const elemenError = getElemen('phoneErrorMsg');

            // Reset state error sebelumnya
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
                // Kirim data ke server (sebelumnya: res)
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
                    // Tutup Modal
                    bootstrap.Modal.getInstance(getElemen('bookingModal')).hide();

                    // Simpan data sementara di window agar bisa diakses tombol Print/Download
                    window.dataBookingSementara = dataJson.data;

                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Berhasil!',
                        html: `<p>Kode Booking: <b>${dataJson.data.booking_code}</b></p>
                               <button onclick="window.printReceipt(window.dataBookingSementara)" class="btn btn-primary mt-2">Cetak Struk</button>
                               <button onclick="window.downloadReceiptImage(window.dataBookingSementara)" class="btn btn-success mt-2">Download Gambar</button>`,
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup'
                    }).then((hasilSweetAlert) => {
                        if(hasilSweetAlert.isConfirmed) location.reload();
                    });
                }

            } catch (error) {
                Swal.fire('Gagal', error.message, 'error');
            } finally {
                // Kembalikan tombol seperti semula
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
                    // Kembalikan ke nilai default (kosong)
                    elemenInput.value = elemenInput.dataset.default || '';
                    elemenInput.classList.remove('is-invalid');
                }
            });
        });
    }
});
