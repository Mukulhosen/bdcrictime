<link rel="stylesheet" href="assets/css/datatables.min.css?<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/responsive.bootstrap4.min.css?<?php echo time(); ?>">
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h2> Tags  <small>Control panel</small> </h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>posts">Posts</a></li>
        <li class="active">Tags</li>
    </ol>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </h3>
                </div>

                <div class="box-body">
                    <div style="padding:0 15px;">
                        <?php echo form_open(Backend_URL . 'posts/tags/create_action', array('class' => 'form-horizontal', 'method' => 'post')); ?>
                        <div class="form-group">
                            <label for="name">Tag Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Tag Name" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save New</button> 
                            <a href="<?php echo site_url('admin/posts/tags') ?>" class="btn btn-default">Reset</a>
                        </div>
                        <?php echo form_close(); ?>
                    </div>                    
                </div>    
            </div>
        </div>

        <div class="col-md-8 col-xs-12">

            <div class="box box-primary">            
                <div class="box-header with-border">                                   
                    <div class="col-md-5 col-md-offset-7 text-right">
                        <form action="<?php echo site_url(Backend_URL . 'posts/tags'); ?>" class="form-inline" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                                <span class="input-group-btn">
                                    <?php if ($q <> '') { ?>
                                        <a href="<?php echo site_url(Backend_URL . 'posts/tags'); ?>" class="btn btn-default">Reset</a>
                                    <?php } ?>
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box-body">
                    <?php echo $this->session->flashdata('message'); ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="40">ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($tags as $tags) { ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>
                                        <td><?php echo $tags->name ?></td>
                                        <td><?php echo $tags->slug ?></td>
                                        <td>
                                            <?php
                                            echo anchor(site_url(Backend_URL . 'posts/tags/update/' . $tags->id), '<i class="fa fa-fw fa-edit"></i>', 'class="btn btn-xs btn-default" title="Edit"');
                                            echo anchor(site_url(Backend_URL . 'posts/tags/delete_action/' . $tags->id), '<i class="fa fa-fw fa-trash"></i>', 'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>


                    <div class="row">                
                        <div class="col-md-6">
                            <span class="btn btn-primary">Total Record : <?php echo $total_rows ?></span>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo $pagination ?>
                        </div>                
                    </div>
                </div>
            </div>
        </div>   
    </div>
</section>
<script>
    $(document).ready(function() {
        $('.table-hover').DataTable( {
            "searching": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false,
            "responsive": true,
            "aaSorting": []
        });
    } );
</script>