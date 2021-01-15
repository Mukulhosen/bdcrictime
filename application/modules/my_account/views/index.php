<?php load_module_asset('my_account', 'css'); ?>  
<div class="my_account">
    <div class="container">
        <div class="col-md-12">
            <h2>&nbsp;</h2>
        </div>
        <div class="row">
            
            <div class="col-md-9 pull-right">
                <div class="panel panel-default">
                    <div class="col-md-12">
                        <h2 class="user_welcome"><small>Welcome Back, </small> <?php echo getLoginUserData('name'); ?> <small style="float: right; font-size: 16px;">You login as <?php echo getRoleName(getLoginUserData('role_id')); ?></small> </h2>
                        <hr/>
                    </div>
                    <div class="panel-body">
                        <div class="row" style="padding:40px 20px;">
                            <div class="col-md-4 tile bg-one">
                                <a href="my_account?tab=my_posts" style="text-decoration: none">
                                    <div class="icon"><i class="fa fa-database" aria-hidden="true"></i></div>
                                    <div class="stat"><?php echo count_my_posts();  ?></div>
                                    <div class="title">Total Post</div>
                                    <div class="highlight bg-color-green"></div>
                                </a>
                            </div>
                            <div class="col-md-4 tile bg-one">
                                <a href="my_account?tab=approve_now" style="text-decoration: none">
                                    <div class="icon"><i class="fa fa-asterisk" aria-hidden="true"></i> </div>
                                    <div class="stat"><?php echo count_approve_now(getLoginUserData('user_id'));  ?></div>
                                    <div class="title">post waiting for approval</div>
                                    <div class="highlight bg-color-blue"></div>
                                </a>
                            </div>
                            <div class="col-md-4 tile bg-two">
                                <a href="my_account?tab=mails" style="text-decoration: none">
                                    <div class="icon"><i class="fa fa-envelope"></i></div>
                                    <div class="stat"><?php echo unread_mails();  ?></div>
                                    <div class="title">Unread Message</div>
                                    <div class="highlight bg-color-red"></div>
                                </a>
                            </div>
                        </div>
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