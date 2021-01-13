<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h2> Tags  <small><?php echo $button ?></small> <a href="<?php echo site_url(Backend_URL . 'posts/tags') ?>" class="btn btn-default">Back</a> </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>posts">Posts</a></li>
        <li><a href="<?php echo Backend_URL ?>posts/tags">Tags</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">       
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Create New Tags</h3>
        </div>
        <div class="box-body">
            <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Tag Name :</label>
                <div class="col-sm-10">                    
                    <input type="text" class="form-control" name="name" id="name" placeholder="Tag Name" value="<?php echo $name; ?>" />
                    <?php echo form_error('name') ?>
                </div>
            </div>
            <div class="col-md-12 no-padding text-right">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <button type="submit" class="btn btn-primary"><?php echo $button; ?></button> 
                <a href="<?php echo site_url(Backend_URL . 'posts/tags') ?>" class="btn btn-default">Cancel</a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>