<script type="text/javascript">
    var $ = jQuery;
    
    function countUserByRoleID(RoleID) {
        jQuery.ajax({
            url: "admin/users/countUser",
            type: "POST",
            dataType: "text",
            data: {RoleID: RoleID},
            beforeSend: function () {
                jQuery('#role_' + RoleID).html('Loading...');
            },
            success: function (jsonData) {
                jQuery('#role_' + RoleID).html(jsonData);
            }
        });
    }


    // Manage ACL 
    function manage_acl(id) {
        jQuery.noConflict();
        jQuery('.js_update_respond').empty();
        jQuery('#manageAcl').modal({
            show: 'false'
        });


        jQuery.ajax({
            url: "admin/users/roles/getAcl",
            type: "POST",
            dataType: "text",
            data: {id: id},
            beforeSend: function () {
                jQuery('.acl_respond').html('<i class="fa fa-2x fa-spinner" aria-hidden="true"></i>');
            },
            success: function (msg) {
                jQuery('.acl_respond').html(msg);
            }
        });
    }


  function ajaxFormSubmit(FormID, ajaxRespondID = 'ajaxRespondID'){
    //console.log("submit event");
    var frm = jQuery('#' + FormID);
    frm.submit(function (ev) {
        jQuery.ajax({
            cache: false,
            type: frm.attr('method'),
            url: frm.attr('action'),
            dataType: 'json',
            data: frm.serialize(),
            beforeSend: function () {
                jQuery('#' + ajaxRespondID).html('<div class="alert alert-success">Loading</div>').css('display', 'block');
            },
            success: function (jsonData) {
                if (jsonData.Status === 'OK') {
                    jQuery('#' + ajaxRespondID).html('<div class="alert alert-success">' + jsonData.Msg + '</div>');
                    setTimeout(function () {
                        jQuery('#' + ajaxRespondID).slideUp('slow');
                        document.getElementById(FormID).reset();
                    }, 2000);
                } else {
                    jQuery('#' + ajaxRespondID).html('<div class="alert alert-danger">' + jsonData.Msg + '</div>');
                }
            }
        });
        ev.preventDefault();
    });
            return false;
    }


    // Delete Role ID
    function delete_role(id) {
        var yes = confirm('Really Want to Delete?');
        if (yes) {
            jQuery.ajax({
                url: "admin/users/roles/delete",
                type: "POST",
                dataType: "json",
                data: {id: id},
                beforeSend: function () {
                    jQuery('.role_id_' + id).css('background-color', '#FF0000');
                },
                success: function (respond) {
                    jQuery('.role_id_' + id).fadeOut('slow');
                    jQuery('#ajaxRespond').html('<p class="alert alert-success">' + respond.Msg + '</p>');
                    setTimeout(function () {
                        jQuery('#ajaxRespond').slideUp('slow');
                    }, 1500);
                }
            });
        }
    }

    // Rename Role 
    function edit_role(id) {
        jQuery.ajax({
            url: 'admin/users/roles/rename',
            type: 'POST',
            dataType: "text",
            data: {id: id},
            beforeSend: function () {
                jQuery('.edit_id_' + id).html('Loading...');
            },
            success: function (msg) {
                jQuery('.edit_id_' + id).html(msg);
            }
        });
    }

    // Update Role Value
    function update_role(id) {
        var update_form = jQuery('#update_form').serialize();
        jQuery.ajax({
            url: "admin/users/roles/update",
            type: "POST",
            dataType: "json",
            data: update_form,
            cache: false,
            beforeSend: function () {
                jQuery('.edit_id_' + id).html('Loading...');
            },
            success: function (jsonData) {
                jQuery('.edit_id_' + id).html(jsonData.Msg);
            }
        });

    }


    // Module Access 
    function module_manage() {
        var FormData = jQuery('#access_permission').serialize();

        jQuery.ajax({
            url: "admin/users/roles/update_acl",
            type: "POST",
            dataType: "json",
            data: FormData,
            beforeSend: function () {
                jQuery('.js_update_respond').html('<i class="fa fa-2x fa-spinner" aria-hidden="true"></i>');
            },
            success: function (jsonRespond) {                
                jQuery('.js_update_respond').html(jsonRespond.Msg);               
            }
        });
    }

    var checked = false;
    function checkedAll() {
        if (checked == false) {
            checked = true
        } else {
            checked = false
        }
        for (var i = 0; i < document.getElementById('access_permission').elements.length; i++) {
            document.getElementById('access_permission').elements[i].checked = checked;
        }
    }
    
    
    // random password generator
    function make_password() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < 12; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        $('#new_pass').val(text);
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


 function date_range(range){
     var range = range;
     if( range == 'Custom'){       
      $('#custom').css('display','block');
     } else {      
      $('#custom').css('display','none');
     }
    }
    
</script>