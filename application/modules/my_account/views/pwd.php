<?php load_module_asset('my_account', 'css'); ?>  
<div class="my_account">
    <div class="container">
        <div class="col-md-12" >
            <h2><small>Welcome</small> <?php echo getLoginUserData('name'); ?> </h2>
        </div>
        <div class="row">
            <div class="col-md-9 pull-right force_mobile_width">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="title">Change Password</h4>
                    </div>

                    <div class="panel-body">
                        <div id="ajax_respond"></div>
                        <form name="updatePassword" class="form-horizontal" id="update_password" role="form" method="POST">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="old_pass">Current Password<sup>*</sup>:</label>
                                <div class="col-md-5">
                                    <input type="password" name="old_pass" id="old_pass" class="form-control">
                                    <?php echo form_error('old_pass') ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="new_pass">New Password<sup>*</sup>:</label>
                                <div class="col-md-5">
                                    <input type="password" name="new_pass" id="new_pass" class="form-control">
                                    <?php echo form_error('new_pass') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="con_pass">Confirm New Password<sup>*</sup>:</label>
                                <div class="col-md-5">
                                    <input type="password" name="con_pass" id="con_pass"  class="form-control">
                                    <?php echo form_error('con_pass') ?>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-3">
                                <button class="btn btn-primary" onclick="password_change();" type="button" >Update</button>
                            </div>
                        </form>                       
                    </div>
                </div>               
            </div>
            
            <div class="col-md-3 pull-left force_mobile_width"> 
                <?php echo Modules::run('my_account/menu'); ?> 
            </div>

        </div>
    </div>
</div>

<?php load_module_asset('my_account', 'js'); ?>