$(function() {
    $(document).on('click','#submenu',function (){;
        $(this).find("#sub-content").toggleClass("show");
        $(this).find("#sub-content").toggleClass("hide");
        $(this).find('#icon').toggleClass('ion-chevron-down');
        $(this).find('#icon').toggleClass('ion-chevron-up');
    })
    $(document).on('click','#account',function (){;
        $("#topbar-menu").toggleClass("show");
        $("#topbar-menu").toggleClass("hide");
        $(this).find('#ico-account').toggleClass('ion-chevron-down');
        $(this).find('#ico-account').toggleClass('ion-chevron-up');
    })
});