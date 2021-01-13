<?php
load_module_asset('my_account', 'css');
load_module_asset('my_account', 'js');
?>

<!-- <div class="container">
    <div class="col-md-4 col-md-offset-4"> 
        <div class="login-box-custom"> 
            <div class="login-box">
                <div class="login-box-body">
                    <form id="credential" action="" method="post">
                        <h3 style="margin:0;">Reset Your Password</h3>
                        <div id="respond"></div>
                        <input type="hidden" name="verify_token" value="<?php echo $this->input->get('token'); ?>" >
                        <div class="form-group has-feedback">
                            <input type="text" readonly class="form-control" id="email" name="email" value="<?php echo $this->input->get('email'); ?>">
                        </div>    

                        <div class="form-group has-feedback">
                            <input type="password" value="" name="new_password" id="new_password"  class="form-control" placeholder="New Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>

                        <div class="form-group has-feedback">
                            <input type="password" value="" name="retype_password" id="retype_password"  class="form-control" placeholder="Retype password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>

                        <button type="button" id="reset_pass"  class="btn btn-primary btn-block btn-flat">Reset & Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="account-area">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 offset-xl-4 col-md-6 offset-md-3 col-12">
                <form class="account-wrap forgot-password" id="credential">
                    <h3 class="account-title">CREATE NEW PASSWORD</h3>
                    <p>Your password has been reset, please enter a new password.</p>
                    <div id="respond"></div>
                    <input type="hidden" name="verify_token" value="<?php echo $this->input->get('token'); ?>" >
                    <input type="hidden" readonly id="email" name="email" value="<?php echo $this->input->get('email'); ?>">

                    <div class="input-field-icon">
                        <span><img src="assets/images/account/lock.svg" alt="lock"></span>
                        <input type="password" value="" name="new_password" id="new_password"  class="input-form" placeholder="New Password">
                    </div>
                    <div class="input-field-icon">
                        <span><img src="assets/images/account/lock.svg" alt="lock"></span>
                        <input type="password" value="" name="retype_password" id="retype_password"  class="input-form" placeholder="Retype password">
                    </div>
                    <button type="button" id="reset_pass"  class="account-btn w-100">Reset & Log in</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery-3.5.1.min.js"></script>
<script>
    var $ = jQuery;
    $('#reset_pass').on('click', function () {
        $('.validation_error').html('');
        var error = 0;
        var _email = $('#email').val();
        if (!_email) {
            $('#email').css('border', '1px solid red').css('background-color', '#FFF5AB');
            $('#email').closest('.form-group').append('<em class="validation_error">Enter Email address </em>');
            error = 1;
        } else {
            $('#email').addClass('required');
        }
        var new_password = $('#new_password').val();
        if (!new_password) {
            $('#new_password').addClass('required');
            $('#new_password').closest('.form-group').append('<em class="validation_error">Please enter new password</em>');
            error = 1;
        } else {
            $('#new_password').css('border', '1px solid #999').css('background-color', '#FFF');
        }
        var retype_password = $('#retype_password').val();
        if (!retype_password) {
            $('#retype_password').addClass('required');
            $('#retype_password').closest('.form-group').append('<em class="validation_error">Retype  password</em>');
            error = 1;
        } else {
            $('#retype_password').addClass('required');
        }
        if (error === 0) {
            var formData = jQuery('#credential').serialize();
            console.log(formData);
            jQuery.ajax({
                url: 'auth/reset_password_action',
                type: "post",
                dataType: 'json',
                data: formData,
                beforeSend: function () {
                    jQuery('#respond').html('<p class="ajax_processing">Updating...</p>');
                },
                success: function (jsonRespond) {
                    if (jsonRespond.Status === 'OK') {
                        setTimeout(function () {
                            jQuery('#respond').html(jsonRespond.Msg);
                            jQuery('.formresponse').fadeOut('slow');
                            window.location.href = "my-account";
                        }, 4000);
                    }

                }
            });
            return false;
        }
    });



</script>

