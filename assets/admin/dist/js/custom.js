var sidebar_menu = getCookie( 'sidebar_menu' );
if( sidebar_menu === 'show' ){        
    $('body').removeClass('sidebar-collapse');    
} else if( sidebar_menu === 'hide' ){
    $('body').addClass('sidebar-collapse');    
} else {
    setCookie("sidebar_menu", 'show', 1);    
}

$('.sidebar-toggle').click(function(){
    var sidebar_menu = getCookie('sidebar_menu');
    //alert( sidebar_menu );
    if( sidebar_menu === 'show' ){
        setCookie('sidebar_menu', 'hide', 1);        
    } else {    
        setCookie('sidebar_menu', 'show', 1);        
    }    
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}