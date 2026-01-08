document.addEventListener('DOMContentLoaded', () => {
    // Definisi Elemen
    const wrapper = document.getElementById('drop-area-wrapper'); // Area Drop Luas
    const visualBox = document.getElementById('visual-drop-box'); // Kotak Putus-putus
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');

    // Variabel penampung file
    let dt = new DataTransfer();

    // 1. PENGAMAN GLOBAL (PENTING!)
    // Mencegah browser membuka file di tab baru jika user meleset saat drop di manapun di window
    window.addEventListener('dragover', e => {
        e.preventDefault();
    }, false);

    window.addEventListener('drop', e => {
        e.preventDefault();
    }, false);


    // 2. EVENT LISTENER PADA WRAPPER

    // Highlight saat file masuk area
    wrapper.addEventListener('dragenter', highlight, false);
    wrapper.addEventListener('dragover', highlight, false);

    // Un-highlight saat file keluar area
    wrapper.addEventListener('dragleave', unhighlight, false);
    wrapper.addEventListener('drop', handleDrop, false);

    // Klik pada kotak visual trigger input file
    visualBox.addEventListener('click', () => imageInput.click());

    // Handle saat user memilih file lewat Explorer
    imageInput.addEventListener('change', function() {
        handleFiles(this.files);
    });


    // 3. FUNGSI-FUNGSI LOGIKA

    function highlight(e) {
        e.preventDefault();
        e.stopPropagation();
        // Ubah warna background wrapper dan border kotak visual
        wrapper.style.backgroundColor = 'rgba(13, 110, 253, 0.05)'; // Biru tipis
        visualBox.style.borderColor = '#0d6efd'; // Biru solid
    }

    function unhighlight(e) {
        e.preventDefault();
        e.stopPropagation();
        // Kembalikan warna asal
        wrapper.style.backgroundColor = 'transparent';
        visualBox.style.borderColor = '#dee2e6';
    }

    function handleDrop(e) {
        unhighlight(e); // Matikan visual effect
        const droppedFiles = e.dataTransfer.files;
        handleFiles(droppedFiles);
    }

    function handleFiles(files) {
        const newFiles = [...files];

        // Cek Limit Max 5 Gambar
        if (dt.items.length + newFiles.length > 5) {
            alert("Maksimal hanya boleh 5 gambar!");
            return;
        }

        newFiles.forEach(file => {
            // Validasi Tipe Image
            if (!file.type.startsWith('image/')) return;

            // Validasi Ukuran (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert(`File ${file.name} terlalu besar (Max 2MB)`);
                return;
            }

            // Cek duplikasi (Opsional: mencegah file yang sama diupload 2x)
            let isDuplicate = false;
            for (let i = 0; i < dt.files.length; i++) {
                if (dt.files[i].name === file.name && dt.files[i].size === file.size) {
                    isDuplicate = true;
                    break;
                }
            }

            if (!isDuplicate) {
                // Tambahkan ID unik ke file object untuk keperluan hapus
                file.uniqueId = Date.now() + Math.random().toString(16).slice(2);

                // Tambahkan ke DataTransfer
                dt.items.add(file);

                // Tampilkan Preview
                previewFile(file);
            }
        });

        // UPDATE INPUT FILE ASLI
        imageInput.files = dt.files;
    }

    function previewFile(file) {
        const reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onloadend = function() {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-sm-6 preview-image-wrapper fade-in';
            col.setAttribute('data-id', file.uniqueId);

            const html = `
                <div class="card h-100 shadow-sm border-0 position-relative group-action">
                    <img src="${reader.result}" class="card-img-top" style="height: 120px; object-fit: cover; border-radius: 8px;">

                    <button type="button"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle shadow-sm"
                        style="width: 28px; height: 28px; padding: 0; line-height: 28px;"
                        onclick="removeImage('${file.uniqueId}')">
                        <i class="bi bi-x"></i>
                    </button>

                    <div class="card-body p-2 text-center">
                        <small class="text-muted d-block text-truncate" style="font-size: 10px;">${file.name}</small>
                    </div>
                </div>
            `;

            col.innerHTML = html;
            previewContainer.appendChild(col);
        }
    }

    // Fungsi Hapus Gambar Global
    window.removeImage = function(id) {
        // Hapus elemen HTML
        const element = document.querySelector(`.preview-image-wrapper[data-id="${id}"]`);
        if(element) element.remove();

        // Buat DataTransfer baru untuk menampung sisa file
        const newDataTransfer = new DataTransfer();

        // Loop file yang ada sekarang, ambil yang BUKAN file yang dihapus
        Array.from(dt.files).forEach(file => {
            if (file.uniqueId !== id) {
                newDataTransfer.items.add(file);
            }
        });

        // Update variabel dt utama
        dt = newDataTransfer;

        // Update input file asli
        imageInput.files = dt.files;
    };
});
