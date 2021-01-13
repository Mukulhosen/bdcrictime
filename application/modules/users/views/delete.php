<?php 

defined('BASEPATH') OR exit('No direct script access allowed'); 

load_module_asset('users','css');

?>

<section class="content-header">
    <h2>User Details <small>of</small> <?php echo $full_name; ?> </h2>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Admin </a></li>        
        <li class="active">User list</li>
    </ol>
</section>

<section class="content">
    <div class="row">
    <div class="col-md-12">               
        <?php echo Users_helper::makeTab($id,  'delete' ); ?>
        
        <div class="box box-primary no-border">
            <div class="box-body">
                <table class="table table-striped">    
                    <tr>
                        <td width="220">Full Name</td>
                        <td width="5">:</td>
                        <td><?php echo $full_name; ?></td>
                    </tr>
                    <tr>
                        <td>User Role</td>
                        <td>:</td>
                        <td><?php echo $role_name; ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td><?php echo $email; ?></td>
                    </tr>
                    <tr>
                        <td>Contact </td>
                        <td>:</td>
                        <td><?php echo $contact; ?></td>
                    </tr>

                    <tr>
                        <td>Registration Date </td>
                        <td>:</td>
                        <td><?php echo globalDateFormat($created); ?></td>
                    </tr>                                                                                              
                </table>
                
                <hr/>
                                                   
                <table class="table table-striped">    
                    <tbody><tr>
                        <th width="220">Link Tables</th>
                        <th width="5">:</th>
                        <th width="150">Match Record</th>
                        <th>Match File/Photo</th>
                    </tr>
                                                                   
                    <tr>
                        <td>CMS/Business Page</td>
                        <td>:</td>
                        <td><?php echo countMatchRecord('cms', $id ); ?></td>
                        <td><?php echo countMatchFiles('cms', $id ); ?></td>
                    </tr>                              
                                                                   
                    <tr>
                        <td>Posted Post</td>
                        <td>:</td>
                        <td><?php echo countMatchRecord('posts', $id ); ?></td>
                        <td><?php echo countMatchFiles('posts', $id ); ?></td>
                    </tr>
                    <tr>
                        <td>Post Comments</td>
                        <td>:</td>
                        <td><?php echo countMatchRecord('post_comments', $id ); ?></td>
                        <td><em>No File</em></td>
                    </tr>
                    <tr>
                        <td>Post Comments Like / Unlike</td>
                        <td>:</td>
                        <td><?php echo countMatchRecord('post_comments_like_unlike', $id ); ?></td>
                        <td><em>No File</em></td>
                    </tr>
                    <tr>
                        <td>Post Like / Unlike</td>
                        <td>:</td>
                        <td><?php echo countMatchRecord('post_like_unlike', $id ); ?></td>
                        <td><em>No File</em></td>
                    </tr>                    
                    <tr>
                        <td>Mail Records</td>
                        <td>:</td>
                        <td><?php echo countMailRecord( $id ); ?></td>
                        <td><?php echo countMailAttachments($id ); ?></td>
                    </tr>                                
                    <tr>
                        <td>Reviews</td>
                        <td>:</td>
                        <td><?php // echo countMatchRecord('reviews', $id, 'customer_id' ); ?></td>
                        <td><em>No File</em></td>
                    </tr>                               
                                                                   
                                                                                                                       
                                                   
                </tbody>
                </table>                
                
                
                
                <center style="padding-top: 15px;">                    
                    <?php if( in_array($id, array(1,2,3))) { ?>
                        <button class="btn btn-danger disabled"> Your can not delete this account</button>                        
                        <p class="btn btn-warning disabled">User id 1,2 and 3 not deletable </p>
                    <?php } else { ?>
                        <a href="admin/users/confirm_delete/<?php echo $id; ?>" onclick="return confirm('Confirm Delete User');" class="btn btn-danger"> Confirm  Delete</a>
                    <?php } ?>
                </center>
                
                
            </div>
        </div>                        
    </div>
    
     
    </div>

</section>



























<?php /*
 * 
 * <table class="table table-striped">    
        <tr>
            <th width="220">Link Tables</th>
            <th width="5">:</th>
            <th>Match Recored</th>
            <th>Match File/Photo</th>
        </tr>
            <?php foreach($tables as $table ){ ?>                                           
        <tr>
            <td><\?php echo '<?php echo $table; ?>'; ?\></td>
            <td>:</td>
            <td><\?php echo countMatchRecord('<?php echo $table; ?>', $id ); ?\></td>
            <td><\?php echo countMatchFiles('<?php echo $table; ?>', $id ); ?\></td>
        </tr>                                 
            <?php } ?>
    </table> 
 */



