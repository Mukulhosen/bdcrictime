<link rel="stylesheet" href="assets/css/datatables.min.css?<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/responsive.bootstrap4.min.css?<?php echo time(); ?>"> 
<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    load_module_asset('posts', 'css');
?>
<section class="content-header">
    <h2>Posts <small>Control panel</small>
    <?php
        if($add_permission){
            echo anchor(site_url('admin/posts/new_post'), ' + Add New', 'class="btn btn-success"');
        }
    ?>
    </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL); ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Posts</li>
    </ol>
</section>
<style type="text/css">
    .table-responsive {
        overflow-x: inherit;
    }
</style>
<section class="content">    
    <div class="responsive-tabpost">
        <?php echo makeTabPost(); ?>
    </div>
    
    <div class="box no-border">
        <?php $this->load->view('filter_form'); ?>
        <div class="box-body">
            <?php if($post_data){ ?>
            
            <div class="col-md-12 no-padding"><?php echo $this->session->flashdata('message'); ?></div>
            
            <table class="table table-hover responsive table-condensed">
                    <thead>
                        <tr>
                            <th class="all id">ID</th>
                            <th class="desktop" width="100">Thumb</th>
                            <th class="all">Details</th>
                            <th class="desktop">Category</th>
                            <th class="desktop">Hits</th>
                            <th class="desktop">Like</th>
                            <th class="desktop">Unlike</th>
                            <th class="desktop">Post Date</th>
                            <th class="desktop" width="120">Post Show</th>
                            <th class="desktop status" >Status</th>
                            <th class="all actions">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($post_data as $post) { ?>
                            <tr>
                                <td class="all"><?php echo $post->id; ?></td>
                                <td class="desktop"><?php echo getPostFeaturedThumb($post->post_image, 'tiny'); ?></td>
                                <td class="post_details all">        
                                    <h4><a href="<?php echo site_url(getSegmentByTemplate($post->sub_cat_tem_desgin).'/'.$post->post_url); ?>" target="_blank"><?php echo getShortContent($post->title, 35); ?></a></h4>
                                    <p><b>Posted by: </b> <em><?php echo getUserNameById($post->user_id); ?></em></p>
                                    <?php
                                        if($post->source_url){
                                            echo '<p><em><a href="'.$post->source_url.'" target="_blank">Source Link <i class="fa fa-external-link"></i></a></em></p>';
                                        }
                                    ?>
                                </td>
                                <td class="desktop"><?php echo getPostsCaretoryNameById($post->category_id); ?></td>
                                <td class="desktop"><?php echo $post->hit_count; ?></td>
                                <td class="desktop"><?php echo $post->like_count; ?></td>
                                <td class="desktop"><?php echo $post->unlike_count; ?></td>
                                <td class="desktop"><?php echo globalDateFormat($post->created); ?></td>
                                <td class="desktop"><?php echo $post->post_show; ?></td>
                                <td class="desktop"><?php echo ((in_array($post->status,['Schedule','Schedule_Publish'])) ? 'Scheduled' : $post->status); ?></td>
                                <td class="all actions">
                                    <a class="btn btn-xs btn-success" href="<?php echo site_url(getSegmentByTemplate($post->sub_cat_tem_desgin).'/'.$post->post_url); ?>" target="_blank"><i class="fa fa-fw fa-eye"></i></a>
                                    <a class="btn btn-xs btn-warning" href="<?php echo site_url('admin/posts/update_post/'. $post->id); ?>"><i class="fa fa-fw fa-edit"></i></a>
                                    
                                    <?php
                                    if($post->status == 'Publish' && $post->post_show == 'Frontend' && getLoginUserData('role_id') == 1){

                                        ?>
                                        <a class="btn btn-xs btn-danger"
                                           href="<?php echo site_url('admin/posts/post_delete/'. $post->id); ?>"
                                           onclick="javasciprt: return confirm('Are You Sure?')">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                    <?php } elseif (!($post->status == 'Publish' && $post->post_show == 'Frontend'))  {?>
                                        <a class="btn btn-xs btn-danger"
                                           href="<?php echo site_url('admin/posts/post_delete/'. $post->id); ?>"
                                           onclick="javasciprt: return confirm('Are You Sure?')">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                        <?php }?>

                                    <?php if($post->comment_count > 0) { ?>
                                        <a class="btn btn-xs btn-info" href="<?php echo site_url('admin/posts/comments/'. $post->id); ?>"><i class="fa fa-fw fa-comments"></i></a>
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