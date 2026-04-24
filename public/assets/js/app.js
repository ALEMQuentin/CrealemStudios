
// ===== Stops dynamic handling =====
document.addEventListener('DOMContentLoaded', () => {

    const container = document.getElementById('stops-container');
    const addBtn = document.getElementById('add-stop');

    if (!container || !addBtn) return;

    let stopIndex = 0;

    addBtn.addEventListener('click', () => {
        stopIndex++;

        const div = document.createElement('div');
        div.className = 'booking-stop';

        div.innerHTML = `
            <div class="booking-stop-row">
                <input type="text" 
                    name="stops[]" 
                    class="booking-input stop-input" 
                    placeholder="Adresse arrêt ${stopIndex}">
                <button type="button" class="remove-stop">✕</button>
            </div>
        `;

        container.appendChild(div);

        div.querySelector('.remove-stop').addEventListener('click', () => {
            div.remove();
        });

        initAutocomplete(div.querySelector('.stop-input'));
    });

});

