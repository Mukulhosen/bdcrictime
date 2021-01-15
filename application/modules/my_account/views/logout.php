<div class="row my_account">
    <div class="container">
        <div class="col-lg-12">
            <h2><small>Welcome</small> <?php echo getLoginUserData('name'); ?> </h2>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-md-3">                    
                    <?php echo Modules::run('my_account/menu');?>
                </div>

                <div class="col-md-9">
                    <div class="panel panel-default" style="box-shadow: none;">
                        <div class="panel-heading">Panel heading without title</div>

                        <div class="panel-body">
                            
                            Logout Page
                            
                            <?php var_dump( $this->session->all_userdata() ); ?>
                            
                            
                            <?php var_dump( $this->session->unset_userdata('name') ); ?>
                            <?php var_dump( $this->session->unset_userdata('value') ); ?>
                            <?php var_dump( $this->session->unset_userdata('expire') ); ?>
                            <?php var_dump( $this->session->unset_userdata('secure') ); ?>
                            
                            <?php var_dump( $this->session->all_userdata() ); ?>
                            
                            
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>   
</div>