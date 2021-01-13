<?php load_module_asset('my_account', 'css' );?>
  
<div class="my_account">
    <div class="container">
        
        <div class="col-md-12">
            <h2><small>Welcome</small> <?php echo getLoginUserData('name'); ?> </h2>
        </div>
        <div class="row">
            <div class="col-md-3"> 
                <?php echo Modules::run('my_account/menu'); ?> 
            </div>

            <?php 
            $user_id    = getLoginUserData('user_id');
            $user       = Modules::run('my_account/profile_info_view', $user_id ); ?> 

            <div class="col-md-9 ">
                 
                
                <div class="col-md-12 no-padding">
                 
                    	
                    <div class="panel panel-default no-padding">
                        <div class="panel-heading">
                            <h4 class="title">Report Spam Advert</h4>
                        </div>
                        <?php /*
                        <form method="post" id="update_profile_info">
                        <div class="panel-body">
                            <div class="formresponse"></div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user->first_name; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user->last_name; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="contact">Mobile Number</label>
                                    <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $user->contact; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="user_email">Contact Email</label>
                                    <input type="text" class="form-control" name="user_email" id="user_email" disabled="" value="<?php echo $user->email; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="country_id">Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <?php echo getDropDownCountries($user->country_id); ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_line1">Address Line 1</label>
                                    <input type="text" class="form-control" id="add_line1" name="add_line1" value="<?php echo $user->add_line1; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="add_line2">Address Line 2</label>
                                    <input type="text" class="form-control" id="add_line2" name="add_line2" value="<?php echo $user->add_line2; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="<?php echo $user->city; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="state">State/Region</label>
                                    <input type="text" class="form-control" id="state" name="state" value="<?php echo $user->state; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="postcode">Post Code</label>
                                    <input type="text" class="form-control" id="postcode" name="postcode" value="<?php echo $user->postcode; ?>">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6 pull-right">
                                        <button type="button" onclick="update_profile();"  class="pull-right btn btn-sm btn-success">Update</button>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        
                    </form>
                        
                        */ ?>
                        
                        
                        
                          <div class="mailbox-messages">
                            <table class="table table-hover table-striped" id="mytable">
                            <thead>
                                <tr>
                                    <th width="40"></th>
                                    <th>Post Title</th>
                                    <th>Mail Type</th>
                                    <th>Mail Subject</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
//                                $serial = 0;
//                                foreach ($mails as $mail){ $serial++; ?>
                                <tr class="mail-row">                                    
                                    <td class="mailbox-star"><?php // echo $serial; ?></td>
                                    <td class="open-mail" data-mailid="<?php // echo $mail->id; ?>">aaa<?php // echo $mail->mail_from; ?></td>
                                    <td class="open-mail" data-mailid="<?php //echo $mail->id; ?>">aaa<?php // echo $mail->mail_type ?></td>
                                    <td class="open-mail" data-mailid="<?php // echo $mail->id; ?>">aaa
                                        <b>aaaa<?php // echo getShortContent($mail->subject,25) ?></b> - aaa<?php // echo getShortContent($mail->body,40) ?>
                                    </td>                                   
                                    <td>aaa<?php // echo globalDateFormat($mail->created);?></td>
                                </tr>
                                <?php // } ?>
                            </tbody>
                            </table>
                            


                        </div>
                        
                        
                        
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
</div>
<?php load_module_asset('my_account', 'js' );?>