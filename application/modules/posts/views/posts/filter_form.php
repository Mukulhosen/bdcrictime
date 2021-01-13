<div class="box-header with-border" style="padding: 0;">
    <div class="filter_row">
        <div class="row">
            <div class="col-md-12">
                <div class="box-header with-border" style="padding: 0; ">  

                    <form method="get" name="report" action="">
                        <input type="hidden" name="post_type" value="<?php echo ($this->input->get('post_type')) ? $this->input->get('post_type') : 'all'; ?>">
                        <div class="filter_row" style="background: #FFEEDF; padding: 8px 0;">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Keyword -->
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="q" placeholder="Keyword" value="<?php echo $q; ?>">
                                    </div>

                                    <!-- Category -->
                                    <div class="col-md-2">
                                        <select name="category" class="form-control">
                                            <?php echo getPostsCategoryList($category, '-- Any Category --'); ?>
                                        </select>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-2">
                                        <select name="status" class="form-control">
                                            <option value="">--Any Status--</option>
                                            <?php echo selectOptions($status, [
                                                                        'Publish' => 'Publish', 
                                                                        'Draft' => 'Draft',
                                                                        'Pending' => 'Pending', 
                                                                        'Trash' => 'Trash',
                                                                    ]); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 text-right">
                                        <button type="submit" class="btn btn-primary" name="go"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                                        <button type="button" class="btn btn-default" onclick="location.href = 'admin/posts';">Reset</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>