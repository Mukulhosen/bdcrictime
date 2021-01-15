<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once ( 'tabs.php ');
?>


        <div class="row">
            <div class="col-sm-8">                                           
                <div class="row">
                    
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            Label <span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-4">
                                            Value
                                        </div>
                                        <div class="col-sm-2">
                                            Label<span class="pull-right">:</span>
                                        </div>
                                        <div class="col-sm-4">
                                            Value
                                        </div>
                                    </div>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        Full Name<span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $title . ' ' . $first_name . ' ' . $last_name; ?>
                                    </div>                                    
                                    <div class="col-sm-2">
                                        Address Line 1<span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $add_line1; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        Role <span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $role_id; ?>
                                    </div>

                                    <div class="col-sm-2">
                                        Address Line 2<span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $add_line2; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        Email <span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $email; ?>
                                    </div>

                                    <div class="col-sm-2">
                                        City<span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $city; ?>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-sm-2">
                                        Contact <span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $contact; ?>
                                    </div>

                                    <div class="col-sm-2">
                                        State<span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $state_id; ?>
                                    </div>
                                </div>     

                                <div class="row">
                                    <div class="col-sm-2">
                                        Reg. Date <span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4"><?php echo $created; ?></div>

                                    <div class="col-sm-2">Postcode<span class="pull-right view_user_profile">:</span></div>
                                    <div class="col-sm-4"><?php echo $postcode; ?></div>
                                </div> 

                                <div class="row">
                                    <div class="col-sm-2">
                                        Date of Birth <span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $dob; ?>
                                    </div>

                                    <div class="col-sm-2">
                                        Country<span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo $country_id; ?>
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-sm-2">
                                        Status <span class="pull-right view_user_profile">:</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php // echo $status; ?>
                                    </div>
                                </div> 



                            </div>

                        </div>

                    </div>

                </div>
            </div>
            <div class="col-sm-4">
                <div class="">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Reset Password
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" class="form-inline" method="post">
                                <div class="form-group">

                                    <label for="new_pass">
                                        New Password
                                    </label>
                                    <input type="text" class="form-control input-sm" id="new_pass" />
                                </div>
                                <button type="button" class="btn btn-default btn-sm" onclick="make_password(); ">
                                    change
                                </button>
                                
                                <div class="form-group" style="margin: 10px 0 0;width: 100%;display: table;">
                                    <button type="submit" class="btn btn-success">Set Password and send Email</button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Profile Photo
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="profile_pic">
                                <?php if($profile_photo) : ?>
                                <img src="uploads/ $profile_photo; ?>" alt="profile" class="img-responsive"/>
                                <?php else : ?>
                                <img src="<?php echo base_url() . 'uploads/default.jpg'; ?>" class="img-responsive" alt="default" />
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

 

</section>
<script>

// random password generator
function make_password(){
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for( var i=0; i < 12; i++ ){
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    $('#new_pass').val(text);   
}

</script>