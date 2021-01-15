<?php load_module_asset('profile', 'css'); ?>
<?php load_module_asset('profile', 'js'); ?>

<section class="content-header">
    <h2>Services <small>Update</small> </h2>
    <ol class="breadcrumb">
        <li><a href="<?php Backend_URL ?>"><i class="fa fa-user"></i> Admin</a></li>
        <li><a href="<?php Backend_URL . '/profile/' ?>"><i class="fa fa-dashboard"></i> Profile</a></li>
        <li class="active">Seller</li>
    </ol>
</section>

<section class="content">
    <?php echo Profile_helper::makeTab('my_package'); ?>                                                                                                            
    <div class="box no-border">        
        <div class="box-body">
            <div id="response_upload"></div> 
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($this->session->flashdata('update_data')): echo $this->session->flashdata('update_data');
                    endif;
                    ?>
                </div>
            </div>
            <form id="my_package" name="my_package" action="" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class='col-md-6'>
                            <?php echo getPackageDetails(getPackageId(getLoginUserData('user_id'))) ?>
                            <h4>How to active my account:</h4>
                            <h5><strong>Free Account:</strong></h5>
                            <p>If you choose free advert, you do not need to do anything. Just seat back and relax, our admin will verify your account and make it live for you.</p>

                            <h5><strong>Paid Account:</strong></h5>
                            <p>If you choose a paid account, Please transfer the amount to the following bank:</p>
                            <p> Bank Name: Bank Name<br>
                                Sort Code: 0907xxx<br>
                                Account Number: 98978-9089-9898<br>
                                Feel free to contact with Admin 0708-xxxx-xxxx For further information or send us an email at info@property.com</p>
                        </div>

                        <?php if(getLoginUserData('role_id') == 4) { ?>
                        <div class='col-md-6'>
                            <h4>Update Package:</h4>
                            <div class="form-group">
                                <div class="col-md-12  no-padding">
                                    <label class="col-md-12 control-label no-padding" for="package_id">Package<sup></sup></label>
                                    <select id="package_id" name="package_id" class="form-control input-md">
                                        <?php echo getPackages(getPackageId(getLoginUserData('user_id'))); ?>        
                                    </select><br />
                                    <button type="submit"  class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Update</button>
                                </div>
                            </div>
                        </div> 
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>



