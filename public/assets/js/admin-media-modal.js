(function () {
    var modal = null;
    var modalGrid = null;
    var modalTitle = null;
    var closeBtn = null;
    var currentContext = null;

    function ensureModal() {
        modal = document.getElementById('cs-media-modal');
        if (!modal) return false;
        modalGrid = document.getElementById('cs-media-modal-grid');
        modalTitle = document.getElementById('cs-media-modal-title');
        closeBtn = document.getElementById('cs-media-modal-close');

        if (closeBtn && !closeBtn.dataset.bound) {
            closeBtn.dataset.bound = '1';
            closeBtn.addEventListener('click', closeModal);
        }

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });

        return true;
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.style.display = 'none';
        currentContext = null;
        if (modalGrid) modalGrid.innerHTML = '';
    }

    function createMediaCard(item) {
        var card = document.createElement('div');
        card.className = 'cs-media-modal-card';
        card.style.border = '1px solid #e5e7eb';
        card.style.borderRadius = '10px';
        card.style.padding = '10px';
        card.style.background = '#fff';

        var preview = document.createElement('div');
        preview.style.height = '120px';
        preview.style.display = 'flex';
        preview.style.alignItems = 'center';
        preview.style.justifyContent = 'center';
        preview.style.marginBottom = '10px';
        preview.style.background = '#f8fafc';
        preview.style.borderRadius = '8px';

        if (item.path && item.mime_type && item.mime_type.indexOf('image/') === 0) {
            var img = document.createElement('img');
            img.src = item.path;
            img.alt = item.original_name || item.filename || '';
            img.style.maxWidth = '100%';
            img.style.maxHeight = '120px';
            img.style.objectFit = 'contain';
            preview.appendChild(img);
        } else {
            var span = document.createElement('span');
            span.className = 'text-muted';
            span.style.fontSize = '12px';
            span.textContent = 'Aperçu indisponible';
            preview.appendChild(span);
        }

        var meta = document.createElement('div');
        meta.className = 'text-muted';
        meta.style.fontSize = '12px';
        meta.style.marginBottom = '10px';
        meta.textContent = 'ID: ' + item.id + (item.original_name ? ' • ' + item.original_name : '');

        var actions = document.createElement('div');
        actions.style.display = 'grid';
        actions.style.gap = '6px';

        function makeButton(label, handler) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline-secondary btn-sm';
            btn.textContent = label;
            btn.addEventListener('click', handler);
            return btn;
        }

        if (currentContext === 'product-featured') {
            actions.appendChild(makeButton('Utiliser comme image principale', function () {
                if (window.selectFeaturedMedia) window.selectFeaturedMedia(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'product-gallery') {
            actions.appendChild(makeButton('Ajouter à la galerie', function () {
                if (window.addMediaToGallery) window.addMediaToGallery(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'page-featured') {
            actions.appendChild(makeButton('Utiliser pour la page', function () {
                if (window.selectPageFeaturedMedia) window.selectPageFeaturedMedia(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'blog-featured') {
            actions.appendChild(makeButton('Utiliser pour l’article', function () {
                if (window.selectBlogFeaturedMedia) window.selectBlogFeaturedMedia(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'wysiwyg-content') {
            actions.appendChild(makeButton('Insérer dans le contenu', function () {
                if (window.adminInsertMediaIntoActiveEditor) {
                    window.adminInsertMediaIntoActiveEditor({
                        id: String(item.id),
                        path: item.path || '',
                        original_name: item.original_name || item.filename || ''
                    });
                }
                closeModal();
            }));
        }

        card.appendChild(preview);
        card.appendChild(meta);
        card.appendChild(actions);

        return card;
    }


    function getProcessedMediaLibrary() {
        var allItems = getProcessedMediaLibrary();
        var items = getPaginatedMediaLibrary();

        if (currentSearch) {
            items = items.filter(function (item) {
                var id = String(item.id || '');
                var name = String(item.original_name || item.filename || '').toLowerCase();
                return id.includes(currentSearch) || name.includes(currentSearch);
            });
        }

        return items;
    }

    function getPaginatedMediaLibrary() {
        var items = getProcessedMediaLibrary();
        var start = (currentPage - 1) * ITEMS_PER_PAGE;
        return items.slice(start, start + ITEMS_PER_PAGE);
    }

    function renderPagination(totalItems) {
        var old = document.getElementById('cs-media-pagination');
        if (old) old.remove();

        var totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        if (totalPages <= 1 || !modalGrid || !modalGrid.parentNode) return;

        var wrap = document.createElement('div');
        wrap.id = 'cs-media-pagination';
        wrap.style.display = 'flex';
        wrap.style.alignItems = 'center';
        wrap.style.gap = '10px';
        wrap.style.marginTop = '14px';

        var prev = document.createElement('button');
        prev.type = 'button';
        prev.className = 'btn btn-outline-secondary btn-sm';
        prev.textContent = '←';
        prev.disabled = currentPage === 1;
        prev.onclick = function () {
            if (currentPage > 1) {
                currentPage--;
                renderLibrary();
            }
        };

        var info = document.createElement('span');
        info.textContent = 'Page ' + currentPage + ' / ' + totalPages;

        var next = document.createElement('button');
        next.type = 'button';
        next.className = 'btn btn-outline-secondary btn-sm';
        next.textContent = '→';
        next.disabled = currentPage >= totalPages;
        next.onclick = function () {
            if (currentPage < totalPages) {
                currentPage++;
                renderLibrary();
            }
        };

        wrap.appendChild(prev);
        wrap.appendChild(info);
        wrap.appendChild(next);

        modalGrid.parentNode.appendChild(wrap);
    }

    function getMediaLibrary() {
        var source = document.getElementById('cs-media-library-data');
        if (!source) return [];
        try {
            return JSON.parse(source.textContent || '[]');
        } catch (e) {
            return [];
        }
    }

    function getContextTitle(context) {
        switch (context) {
            case 'product-featured': return 'Choisir l’image principale du produit';
            case 'product-gallery': return 'Choisir des images pour la galerie produit';
            case 'page-featured': return 'Choisir l’image mise en avant de la page';
            case 'blog-featured': return 'Choisir l’image mise en avant de l’article';
            case 'wysiwyg-content': return 'Insérer un média dans le contenu';
            default: return 'Bibliothèque média';
        }
    }

    window.openMediaModal = function (context) {
        if (!ensureModal()) return;

        currentContext = context || 'wysiwyg-content';

        var allItems = getProcessedMediaLibrary();
        var items = getPaginatedMediaLibrary();
        modalGrid.innerHTML = '';

        if (modalTitle) modalTitle.textContent = getContextTitle(currentContext);

        if (!items.length) {
            var empty = document.createElement('div');
            empty.className = 'text-muted';
            empty.textContent = 'Aucun média disponible.';
            modalGrid.appendChild(empty);
        } else {
            items.forEach(function (item) {
                modalGrid.appendChild(createMediaCard(item));
            });
        }

        modal.style.display = 'flex';
        modal.classList.add('is-open');
    };

    document.addEventListener('DOMContentLoaded', ensureModal);
})();
