(function () {
    var modal = null;
    var modalGrid = null;
    var modalTitle = null;
    var closeBtn = null;
    var uploadInput = null;
    var uploadBtn = null;
    var currentContext = null;
    var currentSearch = '';

    function qs(id) {
        return document.getElementById(id);
    }

    function ensureModal() {
        modal = qs('cs-media-modal');
        if (!modal) return false;

        modalGrid = qs('cs-media-modal-grid');
        modalTitle = qs('cs-media-modal-title');
        closeBtn = qs('cs-media-modal-close');
        uploadInput = qs('cs-media-modal-upload-input');
        uploadBtn = qs('cs-media-modal-upload-btn');

        if (closeBtn && !closeBtn.dataset.bound) {
            closeBtn.dataset.bound = '1';
            closeBtn.addEventListener('click', closeModal);
        }

        if (uploadBtn && uploadInput && !uploadBtn.dataset.bound) {
            uploadBtn.dataset.bound = '1';
            uploadBtn.addEventListener('click', function () {
                uploadInput.click();
            });
        }

        if (uploadInput && !uploadInput.dataset.bound) {
            uploadInput.dataset.bound = '1';
            uploadInput.addEventListener('change', function () {
                if (uploadInput.files && uploadInput.files[0]) {
                    uploadMediaFile(uploadInput.files[0]);
                }
            });
        }

        if (!modal.dataset.boundBackdrop) {
            modal.dataset.boundBackdrop = '1';
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
        }

        if (!document.body.dataset.boundMediaEscape) {
            document.body.dataset.boundMediaEscape = '1';
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
        }

        var searchInput = qs('cs-media-search-input');
        if (searchInput && !searchInput.dataset.bound) {
            searchInput.dataset.bound = '1';
            searchInput.addEventListener('input', function () {
                currentSearch = (searchInput.value || '').trim().toLowerCase();
                if (modal && modal.classList.contains('is-open')) {
                    renderLibrary();
                }
            });
        }

        return true;
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.style.display = 'none';
        currentContext = null;
        if (modalGrid) modalGrid.innerHTML = '';
    }

    function getMediaLibrary() {
        var source = qs('cs-media-library-data');
        if (!source) return [];
        try {
            return JSON.parse(source.textContent || '[]');
        } catch (e) {
            return [];
        }
    }

    function getFilteredMediaLibrary() {
        var items = getMediaLibrary();

        if (!currentSearch) return items;

        return items.filter(function (item) {
            var id = String(item.id || '');
            var name = String(item.original_name || item.filename || '').toLowerCase();
            return id.includes(currentSearch) || name.includes(currentSearch);
        });
    }

    function getContextTitle(context) {
        switch (context) {
            case 'product-featured':
                return 'Choisir l’image principale du produit';
            case 'product-gallery':
                return 'Choisir des images pour la galerie produit';
            case 'page-featured':
                return 'Choisir l’image mise en avant de la page';
            case 'blog-featured':
                return 'Choisir l’image mise en avant de l’article';
            case 'wysiwyg-content':
                return 'Insérer un média dans le contenu';
            default:
                return 'Bibliothèque média';
        }
    }

    function buildActionButton(label, onClick) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-secondary btn-sm';
        btn.textContent = label;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            onClick();
        });
        return btn;
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

        if (currentContext === 'product-featured' && typeof window.selectFeaturedMedia === 'function') {
            actions.appendChild(buildActionButton('Utiliser comme image principale', function () {
                window.selectFeaturedMedia(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'product-gallery' && typeof window.addMediaToGallery === 'function') {
            actions.appendChild(buildActionButton('Ajouter à la galerie', function () {
                window.addMediaToGallery(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'page-featured' && typeof window.selectPageFeaturedMedia === 'function') {
            actions.appendChild(buildActionButton('Utiliser pour la page', function () {
                window.selectPageFeaturedMedia(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'blog-featured' && typeof window.selectBlogFeaturedMedia === 'function') {
            actions.appendChild(buildActionButton('Utiliser pour l’article', function () {
                window.selectBlogFeaturedMedia(String(item.id));
                closeModal();
            }));
        }

        if (currentContext === 'wysiwyg-content' && typeof window.adminInsertMediaIntoActiveEditor === 'function') {
            actions.appendChild(buildActionButton('Insérer dans le contenu', function () {
                window.adminInsertMediaIntoActiveEditor({
                    id: String(item.id),
                    path: item.path || '',
                    original_name: item.original_name || item.filename || ''
                });
                closeModal();
            }));
        }

        card.appendChild(preview);
        card.appendChild(meta);
        card.appendChild(actions);

        return card;
    }

    function renderLibrary() {
        if (!modalGrid) return;

        var items = getFilteredMediaLibrary();
        modalGrid.innerHTML = '';

        if (!items.length) {
            var empty = document.createElement('div');
            empty.className = 'text-muted';
            empty.textContent = 'Aucun média disponible.';
            modalGrid.appendChild(empty);
            return;
        }

        items.forEach(function (item) {
            modalGrid.appendChild(createMediaCard(item));
        });
    }

    async function uploadMediaFile(file) {
        if (!file) return;

        var formData = new FormData();
        formData.append('media_file', file);

        try {
            if (uploadBtn) {
                uploadBtn.disabled = true;
                uploadBtn.textContent = 'Upload en cours...';
            }

            var response = await fetch('/admin.php?module=media&action=upload', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Upload impossible');
            }

            window.location.reload();
        } catch (e) {
            window.alert("L'upload du média a échoué.");
        } finally {
            if (uploadBtn) {
                uploadBtn.disabled = false;
                uploadBtn.textContent = 'Uploader un média';
            }
            if (uploadInput) uploadInput.value = '';
        }
    }

    window.openMediaModal = function (context) {
        if (!ensureModal()) return;

        currentContext = context || 'wysiwyg-content';
        currentSearch = '';

        var searchInput = qs('cs-media-search-input');
        if (searchInput) searchInput.value = '';

        if (modalTitle) {
            modalTitle.textContent = getContextTitle(currentContext);
        }

        renderLibrary();

        modal.style.display = 'flex';
        modal.classList.add('is-open');
    };

    window.closeMediaModal = closeModal;

    document.addEventListener('DOMContentLoaded', ensureModal);
})();
