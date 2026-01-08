document.addEventListener('shown.bs.modal', function (event) {
    const modal = event.target;

    const cities = [
        { value: 'medan', label: 'Medan' },
        { value: 'aceh', label: 'Aceh' },
        { value: 'jakarta', label: 'Jakarta' },
    ];

    modal.querySelectorAll('.city-select').forEach(select => {
        // biar tidak double append
        if (select.options.length > 0) return;

        const selected = select.dataset.selected || '';

        // OPTION DEFAULT
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Pilih Kota --';
        select.appendChild(defaultOption);

        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.value;
            option.textContent = city.label;

            if (selected === city.value) {
                option.selected = true;
            }

            select.appendChild(option);
        });
    });
});

export {};
