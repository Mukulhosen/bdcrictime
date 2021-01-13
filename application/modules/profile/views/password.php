<?php load_module_asset('profile', 'css' );?>
<?php load_module_asset('profile', 'js' );?>


<section class="content-header">
    <h2>My Account<small>Change Password</small></h2>
    <ol class="breadcrumb">
        <li><a href="<?php Backend_URL ?>"><i class="fa fa-user"></i> Admin</a></li>
        <li><a href="<?php Backend_URL . '/profile/' ?>"><i class="fa fa-dashboard"></i> Profile</a></li>
        <li class="active">Change Password</li>
    </ol>
</section>

<section class="content">
    <?php echo Profile_helper::makeTab('password'); ?>
    <div class="box">
       
        <div class="box-body">
            
            <div class="col-md-12">
                <div id="ajax_respond"></div>
                <form name="updatePassword" id="update_password" role="form" method="POST">                                       
                    <div class="input-group">                               
                        <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i> Current Password<sup>*</sup> </span>                        
                        <input type="password" required="" name="old_pass" id="old_pass" class="form-control">
                    </div>                          

                    <div class="input-group">
                        <span class="input-group-addon">New Password<sup>*</sup></span>
                        <input type="password" required="" name="new_pass" id="new_pass" class="form-control">                         
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">Confirm New Password<sup>*</sup></span>
                        <input type="password" required="" name="con_pass" id="con_pass"  class="form-control">                         
                    </div>
                    <div class="col-md-3 col-lg-offset-2"> 
                        <button class="btn btn-primary emform" onclick="password_change();" type="button" ><i class="fa fa-random" ></i> Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<script>
    function  password_change() {
        var formData = jQuery('#update_password').serialize();
        var error = 0;
        
                                        
        if( !error ) {
            jQuery.ajax({
                url: 'admin/profile/update_password',
                type: "post",
                dataType: 'json',
                data: formData,
                beforeSend: function () {
                    jQuery('#ajax_respond')
                            .html('<p class="ajax_processing">Please Wait...</p>')
                            .css('display', 'block');
                },
                success: function (jsonRespond) {
                    if(jsonRespond.Status === 'OK'){
                        jQuery('#ajax_respond').html(jsonRespond.Msg);
                        setTimeout(function() { jQuery('#ajax_respond').slideUp('slow') }, 2000);
                    } else {                    
                        jQuery('#ajax_respond').html(jsonRespond.Msg);                
                    }
                }
            });
        }
        
     
     return false;
    };
</script>
