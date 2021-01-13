
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('posts', 'css'); ?>
<section class="content-header">
    <h2> Category Tree  <small>Control panel</small> <?php echo anchor(site_url(Backend_URL . 'posts/category/create'), ' + Add New', 'class="btn btn-default"'); ?> </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li><li><a href="<?php echo Backend_URL ?>posts">Company</a></li><li class="active">Category</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th width="50">#ID</th>
                            <th>Category</th>                            
                            <th>SLUG</th>
<!--                            <th>Menu Position</th>                            -->
<!--                            <th>Menu Order</th>                            -->
<!--                            <th>Status</th>     -->
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($categories as $category) { 
                            
                            $category = (object ) $category; ?>
                            <tr>
                                <td><?php echo $category->id; ?></td>
                                <td><?php echo $category->name; ?></td>                                
                                <td><?php echo $category->slug; ?></td>
<!--                                <td>--><?php //echo $category->menu_position; ?><!--</td>-->
<!--                                <td>--><?php //echo $category->menu_order; ?><!--</td>-->
<!--                                <td>--><?php //echo $category->status; ?><!--</td>-->
                                <td>
                                    <?php
                                    echo anchor(site_url(Backend_URL . 'posts/category/update/' . $category->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                    echo anchor(site_url(Backend_URL . 'posts/category/delete/' . $category->id), '<i class="fa fa-fw fa-trash"></i> Delete ', 'class="btn btn-xs btn-danger"');
                                    ?>
                                </td>
                            </tr>
                            
                            <?php if($category->child){
                                $sl = 0;
                                foreach ($category->child as $child) {
                                    $sl++;
                            ?>
                            <tr>
                                <td></td>
                                <td><?php echo $sl; ?>) &nbsp;&nbsp; |__ <?php echo $child->name ?></td>                                    
                                <td><?php echo $child->slug; ?></td>
<!--                                <td>--><?php //echo $child->menu_position; ?><!--</td>                                    -->
<!--                                <td>--><?php //echo $child->menu_order; ?><!--</td>                                    -->
<!--                                <td>--><?php //echo $child->status; ?><!--</td>-->
                                <td>
                                    <?php                                    
                                    echo anchor(site_url(Backend_URL . 'posts/category/update/' . $child->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                    echo anchor(site_url(Backend_URL . 'posts/category/delete/' . $child->id), '<i class="fa fa-fw fa-trash"></i> Delete ', 'class="btn btn-xs btn-danger"');
                                    ?>
                                </td>
                            </tr>
                                <?php
                                    $sub_child = getSubChild($child->id);
                                    if($sub_child){
                                    $serial = 0;
                                    foreach ($sub_child as $subChild) {
                                        $serial++;
                                ?>
                                    <tr>
                                        <td></td>
                                        <td> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $serial; ?>) &nbsp;&nbsp; |__ <?php echo $subChild->name ?></td>
                                        <td><?php echo $subChild->slug; ?></td>
<!--                                        <td>--><?php //echo $subChild->menu_position; ?><!--</td>-->
<!--                                        <td>--><?php //echo $subChild->menu_order; ?><!--</td>-->
<!--                                        <td>--><?php //echo $subChild->status; ?><!--</td>-->
                                        <td>
                                            <?php
                                            echo anchor(site_url(Backend_URL . 'posts/category/update/' . $subChild->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                            echo anchor(site_url(Backend_URL . 'posts/category/delete/' . $subChild->id), '<i class="fa fa-fw fa-trash"></i> Delete ', 'class="btn btn-xs btn-danger"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php }} ?>
                        <?php } } } ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Record : <?php echo $total_rows; ?></span>
                </div>
            </div>
            
        </div>
    </div>
</section>

<script>
    
    function Scrapping( slug, category_id, sub_category_id ){
        if(confirm("Are you sure to play news scrapping on this site?") == true){
            $.ajax({
                type: "POST",
                url: "<?php echo Backend_URL ?>posts/scrapping",
                dataType: 'json',
                data: { slug: slug, category_id: category_id, sub_category_id: sub_category_id },
                beforeSend: function (){
                    $('.preview_modal').empty();
                    $('.preview_modal').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i><p>Please Wait .......</p>');
                    $("#getCodeModal").modal('show');
                },                               
                success: function (jsonData) {
                    if (jsonData.Status === 'OK'){
                        setTimeout(function(){
                            $('.preview_modal i').removeClass('fa-spinner');
                            $('.preview_modal i').removeClass('fa-spin');
                            $('.preview_modal i').addClass('fa-smile-o');
                            $('.preview_modal p').html('Success!');
                        }, 3000);
                        
                        setTimeout(function(){
                            $('#getCodeModal').modal('toggle');
                        }, 5000);
                    }else{
                        setTimeout(function(){
                            $('.preview_modal i').removeClass('fa-spinner');
                            $('.preview_modal i').removeClass('fa-spin');
                            $('.preview_modal i').addClass('fa-frown-o');
                            $('.preview_modal p').html('Fail!');
                        }, 3000);
                        
                        setTimeout(function(){
                            $('#getCodeModal').modal('toggle');
                        }, 5000);
                    }
                },
                cache: false
            });
        }else{
            return false;
        }
    }
    
</script>

<div class="modal fade in" id="getCodeModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="preview_modal"></div>
    </div>
</div>
