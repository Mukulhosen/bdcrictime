<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h2>Category  <small>Delete</small> </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li><li><a href="<?php echo Backend_URL ?>posts">Company</a></li><li><a href="<?php echo Backend_URL ?>posts/category">Category</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content">
    <?php echo categoryTabs($id, 'delete'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Before Delete</h3>
        </div>
        <table class="table table-striped">
            <tr><td width="150">Category Name</td><td width="5">:</td><td><?php echo $name; ?></td></tr>
            <?php if ($sub_category_id) { ?>
                <tr><td width="150">Sub Category</td><td width="5">:</td><td><?php echo getCategoryNameById($sub_category_id); ?></td></tr>
            <?php } ?>
            <tr><td width="150">Parent Category</td><td width="5">:</td><td><?php echo getCategoryNameById($parent_id); ?></td></tr>
        </table>
        <div class="box-header">
            <?php echo anchor(site_url(Backend_URL . 'posts/category/delete_action/' . $id), '<i class="fa fa-fw fa-trash"></i> Confirm Delete ', 'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); ?>
        </div>
    </div>
</section>