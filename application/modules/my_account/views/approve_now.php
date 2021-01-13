<?php load_module_asset('my_account', 'css'); ?>

<?php //echo $title; ?>

<div class="my_account">
    <div class="container">
        <div class="col-md-12">
            <h2><small>Welcome</small> <?php echo getLoginUserData('name'); ?> </h2>
        </div>
        <div class="row">

            <div class="col-md-9 pull-right force_mobile_width">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="title">Approve Now</h4>
                    </div>
                    <div class="panel-body">
                        <?php $posts = Modules::run('my_account/approve_now'); ?>
                        <?php if ($posts) { ?>                        
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th width="120">Photo</th>
                                        <th>Title</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl = 1; foreach ($posts as $post){ ?>
                                        <tr class="mail-row">                 
                                            <td><?php echo getPostFeaturedThumb($post->post_image, 'tiny') ?> </td>     
                                            <td>
                                                <?php echo $post->title; ?><br>
                                                <a class="btn btn-primary btn-xs" 
                                                   href="<?php echo site_url() .'news/'. $post->post_url; ?>" 
                                                   style="margin-top: 10px;"><i class="fa fa-eye"></i> Approve Now</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <p class="alert alert-warning"><strong>Sorry!</strong> No Posts Data Found.</p>
                        <?php } ?>

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