<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h2> Category  <small><?php echo $button ?></small> <a href="<?php echo site_url(Backend_URL . 'posts/category') ?>" class="btn btn-default">Back</a> </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li><li><a href="<?php echo Backend_URL ?>posts">Company</a></li><li><a href="<?php echo Backend_URL ?>posts/category">Category</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">       
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Create New Category</h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                
                <div class="form-group">
                    <label for="parent_id" class="col-sm-2 control-label">Select Category</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="parent_id" id="parent_id">
                            <?php echo getCategoryList( $parent_id, '-- Root Category --' ); ?>
                        </select>
                        <?php echo form_error('parent_id'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="parent_id" class="col-sm-2 control-label">Select Sub Category</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="sub_category_id" id="sub_category_id">
                            <option value="">Select Category First</option>
                        </select>
                        <?php echo form_error('sub_category_id'); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Category Name</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="name" id="postTitle" placeholder="Name" value="<?php echo $name; ?>" />
                        <?php echo form_error('name') ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="slug" class="col-sm-2 control-label">Slug</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="slug" id="postSlug" placeholder="slug" value="<?php echo $slug; ?>" />
                        <?php echo form_error('slug') ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">SEO Title</label>
                    <div class="col-sm-10">
                        <input type="text" name="seo_title" maxlength="60" class="form-control" id="seo_title" 
                            placeholder="SEO Title" value="<?php echo $seo_title; ?>"/>     
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">SEO Keyword</label>
                    <div class="col-sm-10">
                        <textarea name="seo_keyword" maxlength="160" class="form-control" id="seo_keyword" 
                                  placeholder="SEO Keyword"><?php echo $seo_keyword; ?></textarea>  
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">SEO Description</label>
                    <div class="col-sm-10">
                        <textarea name="seo_description" maxlength="160" class="form-control" id="seo_description" 
                                  placeholder="SEO Description"><?php echo $seo_description; ?></textarea>    
                    </div>
                </div>
                
<!--                <div class="form-group">-->
<!--                    <label for="menu_order" class="col-sm-2 control-label">Menu Order</label>-->
<!--                    <div class="col-sm-10">                    -->
<!--                        <input type="text" class="form-control" name="menu_order" id="menu_order" placeholder="Menu Order" value="--><?php //echo $menu_order; ?><!--" />-->
<!--                        --><?php //echo form_error('menu_order') ?>
<!--                    </div>-->
<!--                </div>-->
<!--                -->
<!--                <div class="form-group">-->
<!--                    <label for="menu_position" class="col-sm-2 control-label">Menu Position</label>-->
<!--                    <div class="col-sm-10">                    -->
<!--                        <select class="form-control" name="menu_position">-->
<!--                            --><?php //echo selectOptions($menu_position, ['' => '--Select Menu Position--', 'Top' => 'Top', 'Footer' => 'Footer']); ?>
<!--                        </select>-->
<!--                        --><?php //echo form_error('menu_position') ?>
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    <label for="status" class="col-sm-2 control-label">Template Design</label>-->
<!--                    <div class="col-sm-10">-->
<!--                        <select class="form-control" name="template_design" id="template_design">-->
<!--                            --><?php //echo selectOptions( $template_design, templateDesign()); ?>
<!--                        </select>-->
<!--                        --><?php //echo form_error('template_design'); ?>
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group hide" id="game_type">-->
<!--                    <label for="status" class="col-sm-2 control-label">Game Type</label>-->
<!--                    <div class="col-sm-10">-->
<!--                        <select class="form-control" name="game_type">-->
<!--                            --><?php //echo selectOptions( $game_type, getGameType()); ?>
<!--                        </select>-->
<!--                        --><?php //echo form_error('template_design'); ?>
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    <label for="status" class="col-sm-2 control-label">Status</label>-->
<!--                    <div class="col-sm-10">-->
<!--                        <select class="form-control" name="status">                            -->
<!--                            --><?php //echo selectOptions( $status, ['Active' => 'Active', 'Inactive' => 'Inactive']); ?>
<!--                        </select>-->
<!--                        --><?php //echo form_error('status'); ?>
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    <label for="state" class="col-sm-2 control-label">State (Optional)</label>-->
<!--                    <div class="col-sm-10">-->
<!--                    <select name="state_id"  class="form-control">\-->
<!--                        <option value="">No State</option>-->
<!--                        --><?php //foreach (getAllStates() as $item) {
//                            $select = ($item->id == $state_id) ? 'selected' : '';
//                            echo '<option value="' . $item->id . '"' . $select . '>' . $item->name . '</option>';
//                        }
//                        ?>
<!--                    </select>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    <label class="col-sm-2 control-label">Description</label>-->
<!--                    <div class="col-sm-10">-->
<!--                        <textarea name="description" maxlength="160" class="form-control" id="description"-->
<!--                                  placeholder="Description">--><?php //echo $description; ?><!--</textarea>-->
<!--                    </div>-->
<!--                </div>-->
                
                <div class="col-md-12 text-right">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                    <a href="<?php echo site_url(Backend_URL . 'posts/category') ?>" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    $('#parent_id').change(function () {
        $.ajax({
            url: "<?php echo base_url()?>" + "admin/get-sub-categories/" + $(this).val(),
            success: function(result){
                var obj = JSON.parse(result);
                if (obj.categories) {
                    $("#sub_category_id option").remove();
                    $('#sub_category_id').append($("<option></option>").attr("value", 0).text("Select Sub Category"));
                    $.each(obj.categories, function (index, json) {
                        $('#sub_category_id').append($("<option></option>").attr("value", json.id).text(json.name));
                    });
                }
            }});
    });
    $('#template_design').change(function () {
       if ($(this).val() == 7) {
           $('#game_type').removeClass('hide');
       } else {
           $('#game_type').addClass('hide');
       }
    });

	$("#postTitle").on('keyup keypress blur change', function () {
		var Text = $(this).val();
		Text = Text.toLowerCase();
		var regExp = /\s+/g;
		Text = Text.replace(regExp, '-');
		$("#postSlug").val(Text);
	});
</script>
