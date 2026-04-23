(function () {
    function qs(id){return document.getElementById(id);}
    var modal, grid, title;

    function init(){
        modal = qs('cs-media-modal');
        if(!modal) return;

        grid = qs('cs-media-modal-grid');
        title = qs('cs-media-modal-title');

        var close = qs('cs-media-modal-close');
        if(close){
            close.onclick = function(){
                modal.style.display='none';
                modal.classList.remove('is-open');
            };
        }
    }

    function getData(){
        var el = qs('cs-media-library-data');
        if(!el) return [];
        try{return JSON.parse(el.textContent||'[]');}catch(e){return [];}
    }

    function render(){
        if(!grid) return;
        grid.innerHTML='';
        var items = getData();

        items.forEach(function(item){
            var d=document.createElement('div');
            d.style.border='1px solid #ddd';
            d.style.padding='8px';
            d.style.borderRadius='8px';
            d.style.background='#fff';

            var img='';
            if(item.path){
                img='<img src="'+item.path+'" style="max-width:100%;max-height:100px;">';
            }

            d.innerHTML = img + '<div>ID:'+item.id+'</div>';
            grid.appendChild(d);
        });
    }

    window.openMediaModal = function(){
        init();
        if(!modal) return;

        render();
        modal.style.display='flex';
        modal.classList.add('is-open');
    };

    document.addEventListener('DOMContentLoaded', init);
})();
