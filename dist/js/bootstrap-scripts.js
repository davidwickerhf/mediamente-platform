$('a.disabled,.disabled>a').click(function(){ return false; });
//$('.dropdown-menu').click(function(e){ e.stopPropagation(); });

/* Fix IE autofocus */
$(function(){ $('[autofocus]').focus(); });

(function($){
    /* Prepend alert dismissible */
    $.fn.prependAlertDismissible = function(options){
        /* Options:
         *  state
         *  message
         */
        if(!this.length) return this;
        var div = document.createElement('DIV');
        div.className = 'alert alert-dismissible';
        div.innerHTML = '<button class="close" data-dismiss="alert">&times;</button>\n';
        if(typeof options === 'object'){
            if(options.state === 'success' ||
                    options.state === 'info' ||
                    options.state === 'warning' ||
                    options.state === 'danger')
                div.className += ' alert-' + options.state;
            if(options.state === 'warning' ||
                    options.state === 'danger')
                div.innerHTML += '<strong>Attenzione!</strong>\n';
            if(typeof options.message === 'string')
                div.innerHTML += options.message + '.';
        }
        $(this).prepend(div);
        return this;
    };
    /* Add validation state */
    $.fn.addValidationState = function(options){
        if(!this.length) return this;
        if(typeof options === 'object'){
            if(options.state === 'success' ||
                    options.state === 'warning' ||
                    options.state === 'error' ||
                    options.state === 'feedback'){
                $(this).parents('.form-group')
                    .removeClass('has-success has-warning has-error has-feedback')
                    .addClass('has-' + options.state);
            }
            if(typeof options.message === 'string')
                alert('Attenzione!\n' + $.trim(options.message) + '.');
        }
        else if(typeof options === 'string')
            return $(this).addValidationState({state: options});
        return this;
    };
    /* Remove validation state */
    $.fn.removeValidationState = function(){
        if(!this.length) return this;
        $(this).parents('.form-group')
            .removeClass('has-success has-warning has-error has-feedback');
        return this;
    };
    $('.form-group input')
        .keydown(function(){ $(this).removeValidationState(); })
        .change(function(){ $(this).removeValidationState(); });
})(jQuery);

/* Viewport */
var viewport = {
    w: window,
    e: document.documentElement,
    g: document.getElementsByTagName('body')[0],
    width: function(){ return this.w.innerWidth || this.e.clientWidth || this.g.clientWidth; },
    height: function(){ return this.w.innerHeight || this.e.clientHeight || this.g.clientHeight; }
};

/* SidebarLeft ****************************************************************/
var sidebarLeft = {
    object: document.getElementById('sidebar-left'),
    visible: false,
    pinned: sessionStorage.getItem('sidebarLeftPinned') !== null ? sessionStorage.getItem('sidebarLeftPinned') === 'true' : false,
    show: function(){
        if(typeof this.object !== 'object') return;
        $(document.body).addClass('sidebar-left-visible');
        if(document.getElementById('sidebar-left-toggle'))
            document.getElementById('sidebar-left-toggle').title = 'Nascondi menu';
        this.visible = true;
    },
    hide: function(){
        if(typeof this.object !== 'object') return;
        $(document.body).removeClass('sidebar-left-visible');
        if(document.getElementById('sidebar-left-toggle'))
            document.getElementById('sidebar-left-toggle').title = 'Mostra menu';
        this.visible = false;
    },
    pin: function(){
        if(typeof this.object !== 'object') return;
        $(document.body).addClass('sidebar-left-pinned');
        if(document.getElementById('sidebar-left-toggle-pin'))
            document.getElementById('sidebar-left-toggle-pin').title = 'Sblocca menu';
        sessionStorage.setItem('sidebarLeftPinned', 'true');
        this.pinned = true;
    },
    unpin: function(){
        if(typeof this.object !== 'object') return;
        $(document.body).removeClass('sidebar-left-pinned');
        if(document.getElementById('sidebar-left-toggle-pin'))
            document.getElementById('sidebar-left-toggle-pin').title = 'Blocca menu';
        sessionStorage.setItem('sidebarLeftPinned', 'false');
        this.pinned = false;
    }
};
$('#sidebar-left-toggle').click(function(){
    if(sidebarLeft.visible === true)
        sidebarLeft.hide();
    else
        sidebarLeft.show();
});
$('#sidebar-left-toggle-pin').click(function(){
    if(sidebarLeft.pinned === true)
        sidebarLeft.unpin();
    else
        sidebarLeft.pin();
});
$(':root').click(function(e){
    var $elements = $('#sidebar-left, #sidebar-left-toggle');
    for(var i = 0; i < $elements.length; i++)
        if($.contains($elements[i], e.target) || $elements[i] === e.target) return;
    if(viewport.width() < 768 ||
            sessionStorage.getItem('sidebarLeftPinned') === null ||
            sessionStorage.getItem('sidebarLeftPinned') === 'false')
        sidebarLeft.hide();
});

// OnLoad: Show sidebar left
if(typeof sidebarLeft.object === 'object' &&
        viewport.width() > 768 &&
        (sessionStorage.getItem('sidebarLeftPinned') === null ||
        sessionStorage.getItem('sidebarLeftPinned') === 'true')){
    sidebarLeft.show();
    sidebarLeft.pin();
}