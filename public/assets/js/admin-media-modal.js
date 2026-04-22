(function () {

    var modal, modalGrid, modalTitle, closeBtn;
    var uploadInput, uploadBtn;
    var infoPreview, infoName, infoUrl, infoMime, infoSize;
    var infoAltInput, infoNameInput, saveBtn, copyBtn;

    var currentItem = null;
    var currentContext = null;

    function ensureModal() {
        modal = document.getElementById('cs-media-modal');
        if (!modal) return false;

        modalGrid = document.getElementById('cs-media-modal-grid');
        modalTitle = document.getElementById('cs-media-modal-title');
        closeBtn = document.getElementById('cs-media-modal-close');

        uploadInput = document.getElementById('cs-media-modal-upload-input');
        uploadBtn = document.getElementById('cs-media-modal-upload-btn');

        infoPreview = document.getElementById('cs-media-info-preview');
        infoName = document.getElementById('cs-media-info-name');
        infoUrl = document.getElementById('cs-media-info-url');
        infoMime = document.getElementById('cs-media-info-mime');
        infoSize = document.getElementById('cs-media-info-size');

        infoAltInput = document.getElementById('cs-media-edit-alt');
        infoNameInput = document.getElementById('cs-media-edit-name');
        saveBtn = document.getElementById('cs-media-save-btn');
        copyBtn = document.getElementById('cs-media-copy-url-btn');

        if (closeBtn && !closeBtn.dataset.bound) {
            closeBtn.dataset.bound = '1';
            closeBtn.onclick = closeModal;
        }

        if (uploadBtn && !uploadBtn.dataset.bound) {
            uploadBtn.dataset.bound = '1';
            uploadBtn.onclick = () => uploadInput.click();
        }

        if (uploadInput && !uploadInput.dataset.bound) {
            uploadInput.dataset.bound = '1';
            uploadInput.onchange = () => {
                if (uploadInput.files[0]) upload(uploadInput.files[0]);
            };
        }

        if (copyBtn && !copyBtn.dataset.bound) {
            copyBtn.dataset.bound = '1';
            copyBtn.onclick = () => {
                if (!currentItem) return;
                navigator.clipboard.writeText(currentItem.path);
            };
        }

        if (saveBtn && !saveBtn.dataset.bound) {
            saveBtn.dataset.bound = '1';
            saveBtn.onclick = saveMedia;
        }

        return true;
    }

    function closeModal() {
        modal.style.display = 'none';
        modal.classList.remove('is-open');
    }

    function upload(file) {
        var fd = new FormData();
        fd.append('media_file', file);

        fetch('/admin.php?module=media&action=upload', {
            method: 'POST',
            body: fd
        }).then(() => location.reload());
    }

    function loadLibrary() {
        try {
            return JSON.parse(document.getElementById('cs-media-library-data').textContent || '[]');
        } catch {
            return [];
        }
    }

    function updatePanel(item) {
        currentItem = item;

        infoName.textContent = item.original_name || item.filename;
        infoUrl.textContent = item.path;
        infoMime.textContent = item.mime_type;
        infoSize.textContent = item.size || '-';

        infoAltInput.value = item.alt_text || '';
        infoNameInput.value = item.original_name || '';

        infoPreview.innerHTML = '';
        if (item.path) {
            var img = document.createElement('img');
            img.src = item.path;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '180px';
            infoPreview.appendChild(img);
        }
    }

    function saveMedia() {
        if (!currentItem) return;

        const payload = {
            id: currentItem.id,
            alt_text: infoAltInput.value,
            original_name: infoNameInput.value
        };

        fetch('/admin.php?module=media&action=update', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        }).then(res => {
            if (res.ok) {
                saveBtn.textContent = "Enregistré";
                setTimeout(()=> saveBtn.textContent = "Enregistrer", 1500);
            } else {
                // fallback localStorage
                localStorage.setItem('media_'+currentItem.id, JSON.stringify(payload));
            }
        });
    }

    function createCard(item) {
        var div = document.createElement('div');
        div.style.border = '1px solid #ddd';
        div.style.padding = '10px';
        div.style.cursor = 'pointer';

        div.onclick = () => updatePanel(item);

        var img = document.createElement('img');
        img.src = item.path;
        img.style.maxWidth = '100%';

        div.appendChild(img);
        return div;
    }

    window.openMediaModal = function(context) {
        ensureModal();
        currentContext = context;

        const items = loadLibrary();
        modalGrid.innerHTML = '';

        items.forEach(i => modalGrid.appendChild(createCard(i)));

        if (items[0]) updatePanel(items[0]);

        modal.style.display = 'flex';
        modal.classList.add('is-open');
    };

    document.addEventListener('DOMContentLoaded', ensureModal);

})();

// ===== PATCH SEARCH =====

(function () {
    var originalRenderLibrary = renderLibrary;

    var currentSearch = '';

    function filterItems(items) {
        if (!currentSearch) return items;
        return items.filter(function (item) {
            var id = String(item.id || '');
            var name = String(item.original_name || item.filename || '').toLowerCase();
            return id.includes(currentSearch) || name.includes(currentSearch);
        });
    }

    var oldGetMediaLibrary = getMediaLibrary;
    getMediaLibrary = function () {
        return filterItems(oldGetMediaLibrary());
    };

    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('cs-media-search-input');
        if (!input) return;

        input.addEventListener('input', function () {
            currentSearch = (input.value || '').toLowerCase();
            if (typeof originalRenderLibrary === 'function') {
                originalRenderLibrary();
            }
        });
    });

})();

// ===== END PATCH SEARCH =====


// ===== PATCH PAGINATION =====

(function () {

    var ITEMS_PER_PAGE = 12;
    var currentPage = 1;

    function paginate(items) {
        var start = (currentPage - 1) * ITEMS_PER_PAGE;
        return items.slice(start, start + ITEMS_PER_PAGE);
    }

    var oldGetMediaLibrary = getMediaLibrary;

    getMediaLibrary = function () {
        var items = oldGetMediaLibrary();
        return paginate(items);
    };

    function createPaginationControls(totalItems) {
        var existing = document.getElementById('cs-media-pagination');
        if (existing) existing.remove();

        var totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        if (totalPages <= 1) return;

        var container = document.createElement('div');
        container.id = 'cs-media-pagination';
        container.style.display = 'flex';
        container.style.gap = '8px';
        container.style.marginTop = '10px';

        var prev = document.createElement('button');
        prev.textContent = '←';
        prev.className = 'btn btn-outline-secondary btn-sm';
        prev.disabled = currentPage === 1;
        prev.onclick = function () {
            currentPage--;
            renderLibrary();
        };

        var next = document.createElement('button');
        next.textContent = '→';
        next.className = 'btn btn-outline-secondary btn-sm';
        next.disabled = currentPage >= totalPages;
        next.onclick = function () {
            currentPage++;
            renderLibrary();
        };

        var info = document.createElement('span');
        info.textContent = 'Page ' + currentPage + ' / ' + totalPages;

        container.appendChild(prev);
        container.appendChild(info);
        container.appendChild(next);

        var grid = document.getElementById('cs-media-modal-grid');
        if (grid && grid.parentNode) {
            grid.parentNode.appendChild(container);
        }
    }

    var oldRenderLibrary = renderLibrary;

    renderLibrary = function () {
        var allItems = document.getElementById('cs-media-library-data');
        var totalItems = 0;

        try {
            totalItems = JSON.parse(allItems.textContent || '[]').length;
        } catch (e) {}

        oldRenderLibrary();
        createPaginationControls(totalItems);
    };

})();

// ===== END PATCH PAGINATION =====

