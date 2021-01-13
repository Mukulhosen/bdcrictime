<script type="text/javascript">
var base_url = "<?php echo base_url() ?>";
function CKupdate() {
    for (instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
}
    
function update_profile(update_url) {    
    CKupdate();
    var error = 0;
    var formData = new FormData(document.getElementById("update_profile_info"));    
    
    var first_name = $('#first_name').val();
    if (!first_name) {
        $('#first_name').addClass('required');
        error = 1;
    } else {
        $('#first_name').removeClass('required');
    }
    
    var last_name = $('#last_name').val();
    if (!last_name) {
        $('#last_name').addClass('required');
        error = 1;
    } else {
        $('#last_name').removeClass('required');
    }

    
    if (!error) {
        $.ajax({
            url: update_url,
            type: "POST",
            dataType: 'json',
            data: formData,
            enctype: 'multipart/form-data',
            beforeSend: function () {
                $('#ajax_respond')
                        .html('<p class="ajax_processing">Loading...</p>')
                        .css('display','block');
            },
            success: function ( jsonRespond ) {
                if(jsonRespond.Status === 'OK'){
                    $('#ajax_respond').html( jsonRespond.Msg.msg ); 
                    $("#profile-img-1").attr("src",base_url+'uploads/users_profile/'+jsonRespond.Msg.img);
                    $("#profile-img-2").attr("src",base_url+'uploads/users_profile/'+jsonRespond.Msg.img);

                    setTimeout(function () {
                        $('#ajax_respond').slideUp( );
                    }, 2000);
                } else {
                    $('#ajax_respond').html(jsonRespond.Msg);
                }
                $('html, body').animate({
                    scrollTop: $('#ajaxContent').offset().top - 20 //#DIV_ID is an example. Use the id of your destination on the page
                }, 'slow');
            },
            processData: false, // tell jQuery not to process the data
            contentType: false  // tell jQuery not to set contentType
        });
        return false;
    }
}

/*------------ Instant Show Preview Image to a targeted place ------------*/
function instantShowUploadImage(input, target) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(target + ' img').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    $(target).show();
}

</script>

