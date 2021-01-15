<?php load_module_asset('users', 'css'); ?>
<link rel="stylesheet" href="assets/css/datatables.min.css?<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/responsive.bootstrap4.min.css?<?php echo time(); ?>">
<?php load_module_asset('users', 'js'); ?>
<section class="content-header">
    <h2> User <small>list</small> &nbsp;&nbsp;
        <?php echo anchor(site_url('admin/users/create'), ' + Add User', 'class="btn btn-default"'); ?>
    </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin </a></li>        
        <li class="active">User list</li>
    </ol>
</section>

<section class="content"> 
    <div class="panel panel-default">
        <?php $this->load->view('filter_form'); ?>
        <?php echo $this->session->flashdata('message'); ?>
        
        <?php if($users_data){ ?>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reg.Date</th>
                    <th>Name</th>                                                    
                    <th>Username </th>
                    <th>Contact</th>
                    <th>Role</th>       
                    <th>Action</th>
                </tr>   
            </thead>
            <tbody>
                <?php foreach ($users_data as $user) { ?>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo globalDateFormat($user->created); ?></td>                        
                        <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                       
                        <td><?php echo $user->email; ?></td>
                        <td><?php echo $user->contact; ?></td>
                        <td><?php echo Users_helper::getRoleNameByID($user->role_id); ?></td>
                        <td><?php
//                            echo anchor(site_url(Backend_URL . 'users/force_logout/' . $user->id), '<i class="fas fa-sign-out-alt"></i> Force Logout', 'class="btn btn-xs btn-primary"');
                            echo anchor(site_url(Backend_URL . 'users/update/' . $user->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');                            
                            echo anchor(site_url(Backend_URL . 'users/delete/' . $user->id), '<i class="fa fa-fw fa-trash"></i> Delete ', 'class="btn btn-xs btn-danger"');
                            ?>                                                      
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <div class="row" style="padding-top: 10px; padding-bottom: 10px; margin: 0;">
            <div class="col-md-6">
                <span class="btn btn-primary">Total Record : <?php echo $total_rows ?></span>
            </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
        
        <?php }else{ ?>
        
        <div class="panel-body"><p class="ajax_notice">User Not found!</p></div>
        
        <?php } ?>
        
    
    </div>
</section>    
<script>
  $(document).ready(function() {
        $('.table-striped').DataTable( {
            "searching": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false,
            "responsive": true,
            "aaSorting": []
        });
    } );
</script>
