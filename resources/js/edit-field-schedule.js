if (!window.editScheduleInitialized) {
    window.editScheduleInitialized = true;

    // 1. DATA & CONFIG
    const STATE = {
        fieldCounter: 0,
        scheduleCounter: 0
    };

    const CONFIG = {
        times: ['12:00-13:00', '15:00-17:00', '18:00-19:00', '21:00-22:00'],
        days: ["senin", "selasa", "rabu", "kamis", "jumat", "sabtu", "minggu"]
    };

    // 2. HELPER (Fungsi Pembantu Kecil)
    const Utils = {
        generateIdSche: () => `new_Sched_${Date.now()}_${++STATE.scheduleCounter}`,

        generateIdField: () => `new_field_${Date.now()}_${++STATE.fieldCounter}`,

        // Generator nama input yang dinamis
        getNameAttr: (fieldId, day, isNewField) => {
            const uniqueId = Utils.generateIdSche();
            if (isNewField) {
                return {
                    time: `new_fields[${fieldId}][schedules][${day}][][time]`,
                    price: `new_fields[${fieldId}][schedules][${day}][][price]`
                };
            }
            return {
                time: `prices[${fieldId}][${day}][${uniqueId}][time]`,
                price: `prices[${fieldId}][${day}][${uniqueId}][price]`
            };
        },

        // Cari jam yang belum terpakai di blok hari tersebut
        findAvailableSlot: (dayBlock) => {
            const usedSlots = Array.from(dayBlock.querySelectorAll('input[type="text"]')).map(i => i.value.trim());
            return CONFIG.times.find(slot => !usedSlots.includes(slot));
        },

        // Cari nama hari yang aktif (dari text <strong> atau dropdown <select>)
        getDayName: (dayBlock) => {
            const strong = dayBlock.querySelector("strong");
            const select = dayBlock.querySelector("select");
            return strong ? strong.textContent.toLowerCase().trim() : select.value;
        }
    };

    // 3. UI TEMPLATES (HTML Strings)
    const UI = {
        field: (index, count) => `
            <div class="card mt-3 new-field-card" data-field-index="${index}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-success mb-0">
                            <i class="bi bi-plus-circle-fill"></i> Lapangan ${count}
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-new-field">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="schedule-wrapper-new" data-field="${index}"></div>
                    <button type="button" class="btn btn-sm btn-success mt-3 add-day-new" data-field="${index}">
                        + Tambah Hari
                    </button>
                </div>
            </div>`,

        day: (options, selectorClass) => `
            <div class="day-block border rounded p-3 mt-3">
                <div class="d-flex justify-content-between mb-2">
                    <select class="form-select w-auto ${selectorClass}">${options}</select>
                    <button type="button" class="btn btn-sm btn-danger remove-day">
                        Hapus Hari
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-slot">
                    <i class="bi bi-plus-circle"></i> Tambah Jam
                </button>
            </div>`,

        slot: (nameTime, namePrice, timeValue) => {
            const isNew = nameTime.includes("new_fields");
            return `
            <div class="row mt-2 align-items-center schedule-row">
                <div class="col-md-4">
                    <input type="text"
                            name="${nameTime}"
                            value="${timeValue}"
                            class="form-control ${isNew ? 'time-input-new' : 'time-input'}"
                            required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="${namePrice}" placeholder="Harga" class="form-control ${isNew ? 'price-input-new' : 'price-input'}" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-slot">✕</button>
                </div>
            </div>`;
        }
    };

    // 4. ACTIONS (Logika Utama)
    const Actions = {
        addField: (btn) => {
            const venueId = btn.dataset.venueId; // Ambil ID venue dari tombol

            // 1. Hitung jumlah lapangan yang SUDAH ADA (dari database/server)
            // Kita cari elemen .card di dalam container existing-fields
            const existingCount = document.querySelectorAll(`#existing-fields-${venueId} .card`).length;

            // 2. Hitung jumlah lapangan BARU yang sedang ditambahkan (belum disimpan)
            // Kita cari elemen .new-field-card di dalam container new-fields
            const newCount = document.querySelectorAll(`#new-fields-container-${venueId} .new-field-card`).length;

            // 3. Tentukan Nomor Label (Total Existing + Total Baru + 1)
            const displayLabel = existingCount + newCount + 1;

            const container = document.getElementById(`new-fields-container-${venueId}`);

            // Masukkan displayLabel ke fungsi UI.field
            container.insertAdjacentHTML("beforeend", UI.field(Utils.generateIdField(), displayLabel));
        },

        addSlot: (btn) => {
            const dayBlock = btn.closest(".day-block");
            const wrapper = dayBlock.closest(".schedule-wrapper, .schedule-wrapper-new");
            const fieldId = wrapper.dataset.field;
            const isNewField = wrapper.classList.contains("schedule-wrapper-new");

            // 1. Cek Slot Kosong
            const availableSlot = Utils.findAvailableSlot(dayBlock);
            if (!availableSlot) return alert("Semua jam sudah terisi!");

            // 2. Buat Atribut Name
            const dayName = Utils.getDayName(dayBlock);
            const names = Utils.getNameAttr(fieldId, dayName, isNewField);

            // 3. Render HTML
            btn.insertAdjacentHTML("beforebegin", UI.slot(names.time, names.price, availableSlot));
        },

        addDay: (btn) => {
            const fieldId = btn.dataset.field;
            const isNewField = btn.classList.contains("add-day-new");
            const wrapper = document.querySelector(isNewField
                ? `.schedule-wrapper-new[data-field="${fieldId}"]`
                : `.schedule-wrapper[data-field="${fieldId}"]`);

            // 1. Filter Hari yang belum ada
            const existingDays = Array.from(wrapper.querySelectorAll(".day-block"))
                .map(block => Utils.getDayName(block));

            const availableDays = CONFIG.days.filter(d => !existingDays.includes(d));

            if (availableDays.length === 0) return alert("Semua hari sudah ada!");

            // 2. Siapkan Dropdown
            const selectedDay = availableDays[0];
            const options = availableDays.map(d =>
                `<option value="${d}" ${d === selectedDay ? 'selected' : ''}>${d.charAt(0).toUpperCase() + d.slice(1)}</option>`
            ).join("");

            // 3. Buat HTML Block Hari
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = UI.day(options, isNewField ? "day-selector-new" : "day-selector");
            const newDayBlock = tempDiv.firstElementChild;

            // 4. Tambahkan Slot Jam Pertama Otomatis ke dalam Hari Baru
            const names = Utils.getNameAttr(fieldId, selectedDay, isNewField);
            const firstSlotHTML = UI.slot(names.time, names.price, CONFIG.times[0]);

            // Masukkan slot sebelum tombol "Tambah Jam"
            newDayBlock.querySelector('.add-slot').insertAdjacentHTML('beforebegin', firstSlotHTML);

            wrapper.appendChild(newDayBlock);
        },

        deleteImage: (btn) => {
            Swal.fire({
                title: "Hapus gambar ini?", text: "Permanen!", icon: "warning", showCancelButton: true, confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/venues/images/${btn.dataset.imageId}`)
                        .then(res => {
                            if (res.data.success) {
                                btn.closest(".position-relative").remove();
                                Swal.fire("Terhapus!", res.data.message, "success");
                            }
                        })
                        .catch(err => Swal.fire("Gagal!", "Error server.", "error"));
                }
            });
        }
    };

    // 5. EVENT LISTENERS (Updated)
    document.body.addEventListener("click", (e) => {
        const target = e.target;

        // --- Routing Tombol Tambah ---
        if (target.closest(".add-new-field")) Actions.addField(target.closest(".add-new-field"));
        else if (target.classList.contains("add-slot")) Actions.addSlot(target);
        else if (target.matches(".add-day, .add-day-new")) Actions.addDay(target);
        else if (target.classList.contains("delete-image-btn")) Actions.deleteImage(target);

        // --- Logic Hapus (Visual + Data) ---

        // A. HAPUS LAPANGAN BARU (Belum masuk DB, langsung hapus visual saja)
        else if (target.closest(".remove-new-field")) {
            if (confirm("Hapus lapangan baru ini?")) target.closest(".new-field-card").remove();
        }

        // B. HAPUS SESI / JAM (PENTING: Ini perbaikan utamanya)
        else if (target.classList.contains("remove-slot")) {
            const row = target.closest(".schedule-row");
            const inputTime = row.querySelector("input[name*='prices']");

            // Cek apakah ini data lama (punya ID di dalam attribute name)
            // Format name lama: prices[field_id][day][SCHEDULE_ID][time]
            // Kita cari angka di kurung siku ketiga
            if (inputTime && inputTime.name.startsWith("prices")) {
                // Regex untuk ambil ID: cari angka di dalam kurung siku setelah nama hari
                // Contoh string: prices[1][senin][45][time] -> Kita mau ambil angka 45
                const match = inputTime.name.match(/prices\[\d+\]\[.+?\]\[(\d+)\]/);

                if (match && match[1]) {
                    const scheduleId = match[1];
                    const form = row.closest("form");

                    // Buat surat perintah hapus untuk sesi ini
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "deleted_schedules[]"; // Array penampung ID yg dihapus
                    input.value = scheduleId;
                    form.appendChild(input);

                    console.log("Menandai schedule ID " + scheduleId + " untuk dihapus.");
                }
            }

            if (confirm("Hapus jam ini?")) row.remove();
        }

        // C. HAPUS HARI (Perbaikan agar lebih akurat)
        else if (target.classList.contains("remove-day")) {
            if (confirm("Hapus semua jadwal di hari ini?")) {
                const dayBlock = target.closest(".day-block");
                const strongTag = dayBlock.querySelector("strong");

                // Jika ada tag <strong> berarti ini hari lama yg tersimpan di DB
                if (strongTag) {
                    const wrapper = dayBlock.closest(".schedule-wrapper");
                    const fieldId = wrapper.dataset.field;
                    const dayName = strongTag.textContent.trim().toLowerCase();
                    const form = dayBlock.closest("form");

                    // Buat surat perintah hapus hari
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = `deleted_days[${fieldId}][]`;
                    input.value = dayName;

                    // PENTING: Append ke FORM, bukan ke dayBlock (karena dayBlock bakal dihapus)
                    form.appendChild(input);

                    console.log(`Menandai hari ${dayName} di lapangan ${fieldId} untuk dihapus.`);
                }

                dayBlock.remove();
            }
        }

        // D. HAPUS LAPANGAN LAMA
        else if (target.closest(".delete-field")) {
            const btn = target.closest(".delete-field");
            if (confirm("Hapus lapangan lama? Semua jadwal di lapangan ini akan hilang.")) {
                const card = btn.closest(".card");
                const form = card.closest("form");

                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "deleted_fields[]";
                input.value = btn.dataset.fieldId;
                form.appendChild(input);

                card.remove();
            }
        }
    });

    // Event Change: Update nama input saat hari diganti
    document.body.addEventListener("change", (e) => {
        if (e.target.matches(".day-selector, .day-selector-new")) {
            const select = e.target;
            const newDay = select.value;
            const wrapper = select.closest(".schedule-wrapper, .schedule-wrapper-new");
            const fieldId = wrapper.dataset.field;
            const isNewField = select.classList.contains("day-selector-new");

            // Regex Replace
            select.closest(".day-block").querySelectorAll("input").forEach(input => {
                const pattern = isNewField ? /\[schedules\]\[.*?\]/ : /prices\[.*?\]\[.*?\]/;
                const replacement = isNewField ? `[schedules][${newDay}]` : `prices[${fieldId}][${newDay}]`;
                input.name = input.name.replace(pattern, replacement);
            });
        }
    });

    console.log("Edit Schedule Script Loaded (Refactored)");
}

export { };
