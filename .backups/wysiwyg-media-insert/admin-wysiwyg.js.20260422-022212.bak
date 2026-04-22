(function () {
    function wrapSelection(tag, textarea) {
        var start = textarea.selectionStart || 0;
        var end = textarea.selectionEnd || 0;
        var value = textarea.value || '';
        var selected = value.substring(start, end);

        var openTag = '';
        var closeTag = '';

        switch (tag) {
            case 'bold':
                openTag = '<strong>';
                closeTag = '</strong>';
                break;
            case 'italic':
                openTag = '<em>';
                closeTag = '</em>';
                break;
            case 'h2':
                openTag = '<h2>';
                closeTag = '</h2>';
                break;
            case 'h3':
                openTag = '<h3>';
                closeTag = '</h3>';
                break;
            case 'p':
                openTag = '<p>';
                closeTag = '</p>';
                break;
            case 'ul':
                openTag = '<ul>\\n  <li>';
                closeTag = '</li>\\n</ul>';
                break;
            case 'ol':
                openTag = '<ol>\\n  <li>';
                closeTag = '</li>\\n</ol>';
                break;
            case 'link':
                var url = window.prompt('URL du lien :', 'https://');
                if (!url) return;
                openTag = '<a href="' + url + '">';
                closeTag = '</a>';
                break;
            default:
                return;
        }

        var replacement = openTag + (selected || 'Texte') + closeTag;
        textarea.value = value.substring(0, start) + replacement + value.substring(end);
        textarea.focus();
        textarea.selectionStart = start;
        textarea.selectionEnd = start + replacement.length;
    }

    function createToolbar(textarea) {
        var wrapper = document.createElement('div');
        wrapper.className = 'cs-wysiwyg-wrapper';

        var toolbar = document.createElement('div');
        toolbar.className = 'cs-wysiwyg-toolbar';
        toolbar.style.display = 'flex';
        toolbar.style.flexWrap = 'wrap';
        toolbar.style.gap = '6px';
        toolbar.style.marginBottom = '10px';

        var buttons = [
            { action: 'bold', label: 'Gras' },
            { action: 'italic', label: 'Italique' },
            { action: 'h2', label: 'H2' },
            { action: 'h3', label: 'H3' },
            { action: 'p', label: 'Paragraphe' },
            { action: 'ul', label: 'Liste puces' },
            { action: 'ol', label: 'Liste numérotée' },
            { action: 'link', label: 'Lien' }
        ];

        buttons.forEach(function (btn) {
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-outline-secondary btn-sm';
            button.textContent = btn.label;
            button.addEventListener('click', function () {
                wrapSelection(btn.action, textarea);
            });
            toolbar.appendChild(button);
        });

        var help = document.createElement('div');
        help.className = 'text-muted';
        help.style.fontSize = '12px';
        help.style.marginBottom = '8px';
        help.textContent = 'Éditeur HTML simple : les boutons insèrent les balises directement dans le contenu.';

        textarea.parentNode.insertBefore(wrapper, textarea);
        wrapper.appendChild(toolbar);
        wrapper.appendChild(help);
        wrapper.appendChild(textarea);
    }

    document.addEventListener('DOMContentLoaded', function () {
        var selectors = [
            'textarea[name="content"]',
            'textarea[name="short_description"]',
            'textarea[name="excerpt"]'
        ];

        var textareas = document.querySelectorAll(selectors.join(','));
        textareas.forEach(function (textarea) {
            if (textarea.dataset.wysiwygReady === '1') return;
            textarea.dataset.wysiwygReady = '1';
            createToolbar(textarea);
        });
    });
})();
