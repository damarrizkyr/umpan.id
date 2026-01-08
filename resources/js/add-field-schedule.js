//
document.addEventListener('DOMContentLoaded', () => {

    const fieldCount = document.getElementById('fieldCount');
    const container = document.getElementById('fieldsContainer');


    if (!fieldCount || !container) {
        return;
    }

    const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    const timeSlots = ['12:00-13:00', '15:00-17:00', '18:00-19:00', '21:00-22:00'];

    fieldCount.addEventListener('input', function () {
        const count = parseInt(this.value);
        container.innerHTML = '';

        if (!count || count < 1) return;

        for (let i = 1; i <= count; i++) {
            container.appendChild(createField(i));
        }
    });

    function createField(fieldIndex) {
        const wrapper = document.createElement('div');
        wrapper.className = 'card mb-4 p-3';

        wrapper.innerHTML = `<h5 class="mb-3">Lapangan ${fieldIndex}</h5>`;

        days.forEach(day => {
            const dayBlock = document.createElement('div');

            const dayBtn = document.createElement('button');
            dayBtn.type = 'button';
            dayBtn.className = 'btn btn-sm btn-outline-primary mb-2';
            dayBtn.textContent = day;

            const slotContainer = document.createElement('div');
            slotContainer.className = 'ms-3';
            slotContainer.style.display = 'none';

            dayBtn.addEventListener('click', () => {
                slotContainer.style.display =
                    slotContainer.style.display === 'none' ? 'block' : 'none';
            });

            timeSlots.forEach(slot => {
                const row = document.createElement('div');
                row.className = 'd-flex align-items-center mb-1 gap-2';

                const slotBtn = document.createElement('button');
                slotBtn.type = 'button';
                slotBtn.className = 'btn btn-sm btn-outline-success';
                slotBtn.textContent = slot;

                const priceInput = document.createElement('input');
                priceInput.type = 'number';
                priceInput.className = 'form-control form-control-sm';
                priceInput.placeholder = 'Harga';
                priceInput.style.width = '140px';
                priceInput.disabled = true;

                //
                priceInput.name = `prices[${fieldIndex}][${day.toLowerCase()}][${slot}]`;

                slotBtn.addEventListener('click', () => {
                    priceInput.disabled = false;
                    priceInput.focus();
                });

                row.appendChild(slotBtn);
                row.appendChild(priceInput);
                slotContainer.appendChild(row);
            });

            dayBlock.appendChild(dayBtn);
            dayBlock.appendChild(slotContainer);
            wrapper.appendChild(dayBlock);
        });

        return wrapper;
    }

    console.log('field-schedule.js loaded OK');
});
