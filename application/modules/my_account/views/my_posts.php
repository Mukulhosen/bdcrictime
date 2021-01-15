<?php load_module_asset('my_account', 'css'); ?>

<?php //echo $title; ?>

<div class="my_account">
    <div class="container">
        <div class="col-md-12">
            <h2><small>Welcome</small> <?php echo getLoginUserData('name'); ?> </h2>
        </div>
        <div class="row">
            <div class="col-md-9 pull-right force_mobile_width">
                <div class="col-md-12 no-padding">
                    <div class="panel panel-default no-padding">
                        <div class="panel-heading">
                            <h4 class="title">My Posts <a class="btn btn-success pull-right" href="my_account?tab=add_post"><i class="fa fa-plus"></i> Add New Post</a></h4>
                        </div>
                        <div class="panel-body mypostmob">
                            <?php $posts = Modules::run('my_account/getMyPosts'); ?>
                            
                            <?php if ($posts) { ?>                                                        
                                <table class="table table-hover table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th width="30">#</th>
                                            <th>Photo</th>
                                            <th>Title</th>
                                            <th>Hits</th>
                                            <th>Comments</th>
                                            <th>Like</th>
                                            <th>Unlike</th>
                                            <th width="140">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sl = 1; foreach ($posts as $post){ ?>
                                            <tr class="mail-row">                                    
                                                <td><?php echo $sl++; ?></td>
                                                <td> <?php echo getPostFeaturedThumb($post->post_image, 'tiny') ?> </td>     
                                                <td> <?php echo $post->title; ?></i></td>  
                                                <td> <?php echo $post->hit_count ?> </td>   
                                                <td> <?php echo $post->comment_count ?> </td>   
                                                <td> <?php echo $post->like_count ?> </td>   
                                                <td> <?php echo $post->unlike_count ?> </td>     
                                                <td>
                                                    <a class="btn btn-primary btn-xs" href="my_account?tab=edit_post&id=<?php echo $post->id; ?>"><i class="fa fa-edit"></i> Edit</a>
                                                    <a class="btn btn-primary btn-xs" href="<?php echo site_url() .'news/'. $post->post_url; ?>" target="_blank"><i class="fa fa-search-plus"></i> Preview</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="modal fade" id="manageReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
                            <?php } else { ?>
                                <p class="alert alert-warning"><strong>Sorry!</strong> No Posts Data Found.</p>
                            <?php } ?>

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
<script>
    function showMoreContent(id) {
        jQuery('#less_' + id).css('display', 'none');
        jQuery('#more_' + id).css('display', 'block');
    }

    function showLessContent(id) {
        jQuery('#less_' + id).css('display', 'block');
        jQuery('#more_' + id).css('display', 'none');
    }
</script>