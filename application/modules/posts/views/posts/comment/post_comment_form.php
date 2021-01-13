<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('posts', 'css'); ?>

<section class="content-header">
    <h2> Post Comment <?php if(!empty($parent_id)) { echo 'Reply'; }?><small><?php echo $button ?></small> 
    <?php if(empty($parent_id)) { ?>
        <a href="<?php echo Backend_URL; ?>posts/comments/<?php echo $post_id; ?>" class="btn btn-default">Back</a></h2>
    <?php } else { ?>
        <a href="<?php echo Backend_URL; ?>posts/comments/reply/<?php echo $parent_id; ?>" class="btn btn-default">Back</a></h2>
    <?php } ?>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <?php if(empty($parent_id)) { ?>
            <li><a href="<?php echo Backend_URL; ?>posts/comments/<?php echo $post_id; ?>">Post Comment</a></li>
        <?php } else { ?>
            <li><a href="<?php echo Backend_URL; ?>posts/comments/reply/<?php echo $parent_id; ?>">Post Comment Reply</a></li>
        <?php } ?>
        <li class="active"><?php echo $button; ?></li>
    </ol>
</section>

<div class="col-md-12 no-padding"><?php echo $this->session->flashdata('message'); ?></div>
<form class="form-horizontal" action="<?php echo $action; ?>" id="form" method="post" enctype="multipart/form-data">
    <section class="content col-md-12">
        <div class="box box-success">
            <div class="box-body">
                
                <div class="form-group no-margin">
                    <label for="">User: </label><br>
                    <?php echo $profile_photo.' '.$name; ?>
                </div>
                <div class="form-group no-margin">
                    <label for="">Comment:</label>
                    <textarea name="description" class="form-control" rows="10"><?php echo $description; ?></textarea>
                </div>
                <div class="form-group no-margin">
                    <label for="int" class="control-label">Status</label>
                    <select class="form-control" name="status" id="status">
                        <?php echo selectOptions($status, [
                            'Approved' => 'Approved',
                            'Pending' => 'Pending',
                        ]); ?>
                    </select>
                </div>
                <div class="box no-border">
                <div class="box-header">
                    <div class="form-group no-margin text-right">
                        <button id="submitButton" type="submit" class="btn btn-flat btn-success"><i
                                    class="fa fa-save"></i> <?php echo $button; ?></button>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
        </div>

            </div>
        </div>

    </section>
    <div class="clearfix"></div>
</form>

