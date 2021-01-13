<link rel="stylesheet" href="assets/css/datatables.min.css?<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/responsive.bootstrap4.min.css?<?php echo time(); ?>"> 
<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    load_module_asset('posts', 'css');
?>
<?php
    $comment_t = 'Comments';
    $update_url = 'admin/posts/update_comment/';
    if($reply) {
        $update_url = 'admin/posts/update_reply/';
        $comment_t = 'Comment Replies';
    }
?>
<section class="content-header">
    <h2>Post <?php echo $comment_t; ?><small>Control panel</small>
        <?php if($post_id) { ?>
            <a href="<?php echo Backend_URL; ?>posts/comments/<?php echo $post_id; ?>" class="btn btn-default">Back</a></h2>
        <?php } ?>
    </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL); ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <?php if($post_id) { ?>
            <li><a href="<?php echo Backend_URL; ?>posts/comments/<?php echo $post_id; ?>">Post Comment</a></li>
        <?php } ?>
        <li class="active">Post <?php echo $comment_t; ?></li>
    </ol>
</section>
<style type="text/css">
    .table-responsive {
        overflow-x: inherit;
    }
</style>

<section class="content">    
    
    <div class="box no-border">
        <div class="box-body">
            <?php if($post_comments_data){ ?>
            
            <div class="col-md-12 no-padding"><?php echo $this->session->flashdata('message'); ?></div>
            
            <table class="table table-hover responsive table-condensed">
                    <thead>
                        <tr>
                            <th class="all id">ID</th>
                            <th class="desktop" width="100">User</th>
                            <th class="all">Details</th>
                            <?php if(!$reply) { ?>
                                <th class="desktop">Reply</th>
                            <?php } ?>
                            <th class="desktop" width="80">Post Date</th>
                            <th class="desktop status" >Status</th>
                            <th class="desktop status" >Edited</th>
                            <th class="all actions">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($post_comments_data as $post_comment) { ?>
                            <tr>
                                <td class="all"><?php echo $post_comment->id; ?></td>
                                <td class="desktop"><?php echo getUserProfilePhoto($post_comment->profile_photo, 'tiny'); ?>
                                <br>
                                    <?php echo $post_comment->name; ?>
                                </td>
                                <td class="post_details all">        
                                    <?php echo getShortContent($post_comment->description, 150); ?>
                                </td>
                                <?php if(!$reply) { ?>
                                <td>
                                    <?php 
                                        $replies = getAllCommentReplies($post_comment->id);
                                    
                                        echo ((count($replies)) ? '<a href="'.site_url('admin/posts/comments/reply/'.$post_comment->id).'">'.count($replies).' Reply</a>' : '0 Reply');
                                    ?>
                                </td>
                                <?php } ?>
                                <td class="desktop"><?php echo globalDateFormat($post_comment->created); ?></td>
                                <td class="desktop"><?php echo $post_comment->status; ?></td>
                                <td class="desktop"><span class="badge info"><?php echo (($post_comment->is_edited) ? 'Yes' : 'No') ; ?></span></td>
                                <td class="all actions">
                                <a class="btn btn-xs btn-warning" href="<?php echo site_url($update_url. $post_comment->id); ?>"><i class="fa fa-fw fa-edit"></i></a>
                                    
                                    <?php if(in_array(getLoginUserData('role_id'), [1,2,3]) || getLoginUserData('user_id') == $post_comment->user_id){ ?>
                                        <a class="btn btn-xs btn-danger" 
                                           href="<?php echo site_url('admin/posts/delete_comment/'. $post_comment->id); ?>" 
                                           onclick="javasciprt: return confirm('Are You Sure?')">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <div class="row">
                <div class="col-md-12" style="padding-bottom:10px">
                    <div class="col-md-6">
                        <span class="btn btn-primary">Total Posts: <?php echo $total_rows ?></span>	    
                    </div>
                    <div class="col-md-6 text-right">
                        <?php echo $pagination ?>
                    </div>  
                </div>
            </div>
            
            <?php }else{ ?>
            <p class="ajax_notice"> No record found! </p>
            <?php } ?>
            
        </div>
    </div>
</section>

<?php load_module_asset('posts', 'js'); ?>