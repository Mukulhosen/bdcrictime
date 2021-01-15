<script>
<!--jQuery.noConflict();-->
jQuery('#signin').on('click', function(e){       
    e.preventDefault();   
    var credential = jQuery('#credential').serialize();
    var redirect = 0;
    if (readCookie('comment')){
        redirect = JSON.parse(readCookie('comment')).redirect
        }
    jQuery.ajax({
        url: 'auth/login',
        type: "POST",
        dataType: "json",
        cache: false,
        data: credential,
        beforeSend: function(){
            jQuery('#respond').html('<p class="ajax_processing">Please Wait... Checking....</p>');
        },
        success: function( jsonData ){
            if(jsonData.Status === 'OK'){
                jQuery('#respond').html( jsonData.Msg );
                if(redirect){
                    window.location.href = redirect
                } else {
                    window.location.assign(window.location.href);
                }

//                window.location.assign(document.referrer);
            } else {
                //grecaptcha.reset();
                jQuery('#respond').html( jsonData.Msg );
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            jQuery('#respond').html( '<p>XML: '+ XMLHttpRequest + '</p>' );
            jQuery('#respond').append( '<p>Status: '+textStatus + '</p>' );
            jQuery('#respond').append( '<p>Error: '+ errorThrown + '</p>' );
        }  
    });        
});

jQuery('#admin_signin').on('click', function(e){       
    e.preventDefault();   
    var credential = jQuery('#credential').serialize();
    var redirect = 0;
    if (readCookie('comment')){
        redirect = JSON.parse(readCookie('comment')).redirect
        }
    jQuery.ajax({
        url: 'auth/admin_login',
        type: "POST",
        dataType: "json",
        cache: false,
        data: credential,
        beforeSend: function(){
            jQuery('#respond').html('<p class="ajax_processing">Please Wait... Checking....</p>');
        },
        success: function( jsonData ){
            if(jsonData.Status === 'OK'){
                jQuery('#respond').html( jsonData.Msg );
                if(redirect){
                    window.location.href = redirect
                } else {
                    window.location.assign(window.location.href);
                }

//                window.location.assign(document.referrer);
            } else {
                //grecaptcha.reset();
                jQuery('#respond').html( jsonData.Msg );
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            jQuery('#respond').html( '<p>XML: '+ XMLHttpRequest + '</p>' );
            jQuery('#respond').append( '<p>Status: '+textStatus + '</p>' );
            jQuery('#respond').append( '<p>Error: '+ errorThrown + '</p>' );
        }  
    });        
});

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
}
    return null;
}
 
function  password_change() {
    var formData = jQuery('#update_password').serialize();
    jQuery.ajax({
        url: 'my_account/change_password',
        type: "POST",
        dataType: 'json',
        data: formData,
        beforeSend: function () {
            jQuery('#ajax_respond')
                    .html('<p class="ajax_processing">Updating...</p>')
                    .css('display','block');
        },
        success: function ( jsonRespond ) {
            jQuery('#ajax_respond').html( jsonRespond.Msg );
            if(jsonRespond.Status === 'OK'){
               setTimeout(function() {	jQuery('#ajax_respond').slideUp('slow'); }, 2000);	  
            }
        }
    });
    return false;
}    

jQuery('.js_forgot').on('click', function(){
    jQuery('.js_login').slideUp('slow');
    jQuery('.js_forget_password').slideDown('slow');
});
jQuery('.js_back_login').on('click', function(){
    jQuery('.js_forget_password').slideUp('slow');
    jQuery('.js_login').slideDown('slow');

});


jQuery('#forgot_pass').click(function(){
    var formData = jQuery('#forgotForm').serialize();
    var email = jQuery('#forgot_mail').val();
    jQuery.ajax({
        url: 'auth/forgot_pass',
        type: "POST",
        dataType: 'json',
        data: formData,
        beforeSend: function () {
            jQuery('.formresponse')
                    .html('<p class="ajax_processing">Checking user...</p>')
                    .css('display','block');
        },
        success: function ( jsonRespond ) {
            if( jsonRespond.Status === 'OK'){
                jQuery('.formresponse').html( jsonRespond.Msg );
                jQuery('#maingReport').load( 'mail/send_pwd_mail/?email=' + email +'&_token=' + jsonRespond._token );
            } else {
                jQuery('.formresponse').html( jsonRespond.Msg );
            }                
        }
    });
    return false;
});
    
jQuery('.open-mail').on('click', function(){
    var mail_id = jQuery(this).data('mailid');     
    jQuery('#manageReport').modal({
        show: 'false'
    }).load('my_account/read_mail/' + mail_id );
     
});  

function show_list(){
    $('#reply_bar').addClass('hidden');
    $('#message_bar').removeClass('hidden');
}
    
</script>
