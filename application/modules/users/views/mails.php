<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$id = $this->uri->segment('4');
?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('users', 'js'); ?>
<section class="content-header">
    <h2>User Details <small>of</small> </h2>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Admin </a></li>        
        <li class="active">User list</li>
    </ol>
</section>

<section class="content"> 
    <?php echo Users_helper::makeTab($id, 'mails'); ?>

    <div class="box box-primary no-border">

        <!-- /.box-header -->
        <div class="box-body">
            <div class="mailbox-controls text-right">



                1-50/200
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                </div>


            </div>
            <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                    <tbody>
                        <tr class="mail-row">
                            <td width="220" class="mailbox-date">2016-11-26 07:26:54</td>                            
                            <td class="mailbox-subject">
                                <a href="admin/mails/read/32" style="color: black !important;"><b>Contact Request</b> - Test</a>
                            </td>
                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>                          
                        </tr>

                        <tr class="mail-row">
                            <td class="mailbox-date">2016-11-26 07:26:54</td>                            
                            <td class="mailbox-subject">
                                <a href="admin/mails/read/32" style="color: black !important;"><b>Contact Request</b> - Test</a>
                            </td>
                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>                          
                        </tr>
                    </tbody>
                </table>
                
            </div>
            <!-- /.mail-box-messages -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding">
            <div class="mailbox-controls text-right">                                                                                     
                1-50/200
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                </div>                                
            </div>
        </div>
    </div>  
</section>
