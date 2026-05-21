const buttons = document.querySelectorAll('.filter-btn');
const items = document.querySelectorAll('.item');

buttons.forEach(button => {

    button.addEventListener('click', () => {

        // =========================
        // ACTIVE BUTTON
        // =========================

        buttons.forEach(btn => {

            btn.classList.remove(
                'border-b-2',
                'border-orange-500'
            );

            btn.classList.add(
                'hover:text-orange-400'
            );

        });

        button.classList.add(
            'border-b-2',
            'border-orange-500'
        );

        button.classList.remove(
            'hover:text-orange-400'
        );

        // =========================
        // FILTER ITEM
        // =========================

        const filter = button.dataset.filter;

        items.forEach(item => {

            // Tampilkan semua
            if(filter === 'all'){
                item.style.display = 'block';
            }

            // Tampilkan sesuai category
            else if(item.classList.contains(filter)){
                item.style.display = 'block';
            }

            // Sembunyikan item lain
            else{
                item.style.display = 'none';
            }

        });

    });

});