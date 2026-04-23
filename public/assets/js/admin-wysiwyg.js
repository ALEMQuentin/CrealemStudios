(function(){
    document.addEventListener('DOMContentLoaded', function(){
        var editors = document.querySelectorAll('[data-wysiwyg]');
        if(!editors.length) return;

        editors.forEach(function(el){
            el.setAttribute('contenteditable','true');
        });
    });
})();
