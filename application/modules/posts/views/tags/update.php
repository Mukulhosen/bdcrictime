<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h2>Tags<small><?php echo $button ?></small> </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>posts">Posts</a></li>
        <li><a href="<?php echo Backend_URL ?>posts/tags">Tags</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content"><div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Update Tags</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
                        <?php echo form_error('name') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Heading :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="heading" id="name" placeholder="Name" value="<?php echo $heading; ?>" />
                        <?php echo form_error('heading') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Meta Description :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="meta_description" id="name" placeholder="Name" value="<?php echo $meta_description; ?>" />
                        <?php echo form_error('meta_description') ?>
                    </div>
                </div>

                <div class="col-md-12 no-padding text-right">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                    <a href="<?php echo site_url(Backend_URL . 'posts/tags') ?>" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>