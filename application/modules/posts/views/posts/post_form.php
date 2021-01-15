<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('posts', 'css'); ?>
<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.css"/>
<section class="content-header">
    <input type="hidden" id="upload_url" value="<?php echo base_url() . 'ajax/load_file_to_server'; ?>"/>
    <h2> Post <small><?php echo $button ?></small> <a href="<?php echo site_url('admin/posts') ?>"
                                                      class="btn btn-default">Back</a></h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL; ?>posts">Posts</a></li>
        <li class="active"><?php echo $button; ?></li>
    </ol>
</section>

<div class="col-md-12 no-padding" id="message"><?php echo $this->session->flashdata('message'); ?></div>
<form class="form-horizontal" action="<?php echo $action; ?>" id="form" method="post" enctype="multipart/form-data">
    <section class="content col-md-9">
        <div class="box box-success">
            <div class="box-body">
                <div class="col-md-12">

                    <div id="post_title_message"></div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i> Page Title</span>
                        <input required="required" type="text" name="title" class="form-control" id="postTitle"
                               placeholder="Title" value="<?php echo $title; ?>">
                        <input type="hidden" name="user_id" value="<?php echo getLoginUserData('user_id'); ?>"/>
                        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
                    </div>

                    <div class="form-group input-group journalist_permalink">
                        <span class="input-group-addon"><i
                                    class="fa fa-globe"></i> Permalink : <?php echo base_url(); ?></span>
                        <input type="text" name="post_url" class="form-control" value="<?php echo $post_url; ?>"
                               id="postSlug" required="required" <?=$status == 'Publish' && $post_show == 'Frontend' ? (checkPermission('post/post-url-change',getLoginUserData('role_id')) ? '' : 'disabled') : ''?>>
                    </div>

                    <div id="post_description_message"></div>
                    <div class="form-group">
                        <textarea name="description" id="content"
                                  class="form-control"><?php echo $description; ?></textarea>
                    </div>
                </div>
								
								<div class="col-xs-12">
                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea placeholder="Add Short Description" name="short_description" id="short_description" class="form-control"><?=$short_description?></textarea>
                    </div>
                </div>
								
								
								
								
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8 no-padding">
                            <label>Youtube Videos</label>
                        </div>
                    </div>

                    <div id="after_append" style="margin-top: 15px;">
                            <div class="add_remove">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-youtube"></i> https://www.youtube.com/watch?v=</span>
                                        <input type="text" class="form-control" value="<?php echo $youtube_json; ?>"
                                               name="video_id" placeholder="Video ID" maxlength="12">
                                        <span class="input-group-addon" style="min-width: 25px;"><i
                                                    class="fa fa-remove remove-me text-danger fa-lg"></i></span>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-youtube"></i> https://vimeo.com/</span>
                            <input type="text" class="form-control" value="<?php echo $vimeo_id; ?>" name="vimeo_id"
                                placeholder="Video ID" maxlength="12">
                            <span class="input-group-addon" style="min-width: 25px;"><i
                                        class="fa fa-remove remove-me text-danger fa-lg"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Photo Caption</label>
                        <textarea placeholder="Add Photo Caption" name="photo_caption" id="photo_caption" class="form-control"><?=$photo_caption?></textarea>
                    </div>
                </div>
            </div>
        </div>
        



        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">SEO Information</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12">

                    <div class="form-group">
                        <label>SEO Title</label>
                        <input type="text" name="seo_title" maxlength="60" class="form-control" id="seo_title"
                               placeholder="SEO Title" value="<?php echo $seo_title; ?>"/>
                    </div>

                    <div class="form-group">
                        <label>SEO Keyword</label>
                        <textarea name="seo_keyword" maxlength="160" class="form-control" id="seo_keyword"
                                  placeholder="SEO Keyword"><?php echo $seo_keyword; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>SEO Description</label>
                        <textarea name="seo_description" maxlength="160" class="form-control" id="seo_description"
                                  placeholder="SEO Description"><?php echo $seo_description; ?></textarea>
                    </div>

                </div>
            </div>
        </div>

    </section>


    <section class="content col-md-3 ">
        <div class="box box-success">
            <div class="box-header with-border">
                <div class="form-group  no-margin">
                    <h3 class="box-title">Additional Settings</h3>
                </div>
            </div>

            <div class="box-header with-border">
                <div id="post_category_message"></div>
                <div class="form-group  no-margin">
                    <label for="int" class="control-label">Category Name</label>
                    <select class="form-control" id="category_id" onChange="get_cat(this.value);" name="category_id"
                            required="required">
                        <?php echo getPostsCategoryList($category_id, '-- Select a Category --'); ?>
                    </select>
                </div>
                <div class="form-group no-margin">
                    <label for="int" class="control-label">Sub Category Name</label>
                    <select class="form-control" id="sub_category_id" name="sub_category_id"
                            onChange="get_child_cat(this.value);">
                        <?php echo getPostParentCategoryOption($category_id, $sub_category_id); ?>
                    </select>
                </div>
                <div class="form-group no-margin">
                    <label for="int" class="control-label">Child Category Name</label>
                    <select class="form-control" id="child_category_id" name="child_category_id" onclick="movie_f(this.value)">
                        <?php echo getPostChildCategoryOption($sub_category_id, $child_category_id); ?>
                    </select>
                </div>

                <div class="form-group no-margin">
                    <label for="int" class="control-label">Status</label>
                    <select class="form-control" name="status" id="status" <?=$status=='Publish'&&$post_show=='Frontend' ? (getLoginUserData('role_id') != 1 ? 'disabled' : '') : ''?>>
                        <?php echo selectOptions($status, [
                            'Publish' => 'Publish',
                            'Draft' => 'Draft',
                            'Pending' => 'Pending',
                            'Trash' => 'Trash',
                            'Rejected' => 'Reject',
                        ]); ?>
                    </select>
                </div>
                <div class="form-group no-margin reject_note <?php echo $status == "Rejected" ? "show" : "hide"; ?>">
                    <label for="int" class="control-label">Note Of Rejection</label>
                    <textarea name="reject_note" class="form-control"><?php echo $reject_note; ?></textarea>
                </div>

				<?php if (in_array(getLoginUserData('role_id'), [1, 2, 3])) { ?>
					<div class="form-group no-margin">
						<label class="control-label">Is Home</label>
						<?=htmlRadio('home_section_id', $home_section_id, ['0' => 'No' , 1=> 'Yes'])?>
					</div>
				<?php } ?>




            </div>

        </div>


        <div class="box box-success">
            <div class="box-header with-border">
                <div class="form-group  no-margin">
                    <h3 class="box-title">Tags</h3>
                </div>
            </div>
            <div class="box-header with-border">
                <div class="form-group no-margin">
                    <!-- <select name="tags[]" id="tags" class="form-control" multiple
                            style="width: 100%;">
                    </select> -->
                    <select id="select2Select" name="tags[]" placeholder="Search tags" class="form-control" multiple>
                        <?php echo getTagsList($tags); ?>
                    </select>
                </div>
            </div>

        </div>

        <div class="box box-success">
            <div class="box-header with-border">
                <div class="form-group no-margin">
                    <h3 class="box-title">Upload Feature Image</h3>
                    <div class="col-md-12 no-padding" id="post_image_message"></div>
                </div>
                <div class="thumbnail upload_image" style="border:0!important;">
                    <?php echo getPostFeaturedThumb($post_image, 'large'); ?>
                </div>
                <input type="file" id="post_image" name="post_image" class="file_select" onchange="instantShowUploadImage(this, '.upload_image')" <?= empty($post_image) ? 'required' : ''?>>
            </div>       
        </div>

        <div class="box no-border">
            <div class="box-header no-padding">
                <div class="form-group no-margin no-padding">
                    <button id="submitButton" type="submit" class="btn btn-flat btn-block btn-success"><i
                                class="fa fa-save"></i> <?php echo $button; ?></button>
                </div>
            </div>
        </div>

    </section>
    <div class="clearfix"></div>
</form>
<input type="hidden" id="schedule_input" value="0">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>

<script>
    $('.datetime').datetimepicker({
        format: 'YYYY-MM-DD hh:mm A',
        minDate: new Date(),
    });
</script>

<script>
    CKEDITOR.replace('content', {
        toolbar: [
            {
                items: ['Copy', 'Cut', 'Paste', 'Undo', 'Redo', 'SelectAll',
                    'Bold', 'Italic', 'Underline', 'RemoveFormat', 'Image',"NumberedList","BulletedList",
                    'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Link', 'Unlink', 'Table',
                    'FontSize', 'Format', 'TextColor', 'BGColor', 'Print', 'Source', 'Preview', 'Maximize', 'Smiley',
                ]
            }
        ],
        height: ['360px'],
        customConfig: '<?php echo site_url(); ?>assets/lib/plugins/ckeditor/config.js',
    });

    /*------------ Instant Show Preview Image to a targeted place ------------*/
    function instantShowUploadImage(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(target + ' img').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
        $(target).show();
    }
    <?php if (!($status == 'Publish' && $post_show == 'Frontend')) {?>
    $("#postTitle").on('keyup keypress blur change', function () {
        var Text = $(this).val();
        Text = Text.toLowerCase();
        var regExp = /\s+/g;
        Text = Text.replace(regExp, '-');
        $("#postSlug").val(Text);
        $(".pageSlug").text(Text);
    });
    <?php } ?>

    function get_cat(category_id, select = null) {
        $.ajax({
            url: 'admin/posts/category/posts_category_by_parent_id',
            type: "POST",
            dataType: "text",
            data: {category_id: category_id},
            beforeSend: function () {
                $('#sub_category_id').html('<option value="0">Loading...</option>');
            },
            success: function (response) {
                $('#sub_category_id').html(response);
                if (select) {
                    $('#sub_category_id').val(select);
                }
                $('#movie_block').css('display', 'none');
            }
        });
    }

    function get_child_cat(category_id, select = null) {
        $.ajax({
            url: 'admin/posts/category/child_category_by_sub_category_id',
            type: "POST",
            dataType: "text",
            data: {category_id: category_id},
            beforeSend: function () {
                $('#child_category_id').html('<option value="0">Loading...</option>');
            },
            success: function (response) {
                $('#child_category_id').html(response);
                if (select) {
                    $('#child_category_id').val(select);
                }
                $('#movie_block').css('display', 'none');
            }
        });
    }


    $("#status").change(function () {
        if ($(this).val() == "Rejected") {
            $('.reject_note').removeClass('hide');
            $('.reject_note').addClass('show');
        } else {
            $('.reject_note').removeClass('show');
            $('.reject_note').addClass('hide');
        }
    });


</script>
<script>
    let storageName = window.location.href;
    if (storageName.substring(storageName.lastIndexOf('/') + 1) == 'new_post') {
        storageName = 'new_post';
    } else {
        storageName = 'update_post_' + storageName.substring(storageName.lastIndexOf('/') + 1);
    }
    $(document).on('change', '#postTitle, #photo_caption, #postSlug, #select2Select, #category_id, #sub_category_id, #child_category_id, #movie, #status, #business_category, #is_featured_label, #oil_category, #oil_subcategory, #seo_title, #seo_keyword, #seo_description', function () {
        saveData();
    })

    CKEDITOR.instances.content.on('change', function() {
        setTimeout(() => {
            saveData();
        }, 1000)
    });
    
    function saveData() {
        let storeData = new Object();
        storeData.id = $('#id').val();
        storeData.postTitle = $('#postTitle').val();
        storeData.postSlug = $('#postSlug').val();
        storeData.photo_caption = $('#photo_caption').val();
        storeData.select2Select = $('#select2Select').val();
        storeData.category_id = $('#category_id').val();
        storeData.sub_category_id = $('#sub_category_id').val();
        storeData.child_category_id = $('#child_category_id').val();
        storeData.status = $('#status').val();
        storeData.seo_title = $('#seo_title').val();
        storeData.seo_keyword = $('#seo_keyword').val();
        storeData.seo_description = $('#seo_description').val();
        storeData.content = CKEDITOR.instances.content.getData();

        localStorage.setItem(storageName, JSON.stringify(storeData));
    }
    
    function getData() {
        let storedData = localStorage.getItem(storageName);
        storedData = JSON.parse(storedData);
        if (storedData) {
            Object.keys(storedData).forEach(function(key) {
                $('#' + key).val(storedData[key]);
                if (key == 'category_id') {
                    get_cat(storedData[key], storedData['sub_category_id']);
                }
                if (key == 'sub_category_id') {
                    get_child_cat(storedData[key], storedData['child_category_id']);
                }

                if (key == 'select2Select') {
                    $('#select2Select').val(storedData[key]);
                    $('#select2Select').trigger('change.select2');
                }

                if (key == 'content') {
                    CKEDITOR.instances['content'].setData(storedData[key]);
                }
            });
        }
    }
    $(document).ready(function () {
        if (storageName == 'new_post') {
            getData();
        } else {
            getData();
        }
        $(document.body).on('click','#submitButton',function (e) {
            e.preventDefault();
            let postTitle = $('#postTitle').val();
            let category_id = $('#category_id').val();
            let status = $('#status').val();
            let content = CKEDITOR.instances.content.getData();
            if (storageName == 'new_post') {
                if (status == 'Publish') {
                    if (postTitle == '') {
                        $("#post_title_message").html("<p class=\"ajax_error\">Please put a title.</p>")
                    } else if (category_id == '' || category_id == 0) {
                        $("#post_category_message").html("<p class=\"ajax_error\">Please Select a category.</p>")
                    } else if (content == '') {
                        $("#post_description_message").html("<p class=\"ajax_error\">Please put some description.</p>")
                    }  else if (($('#child_category_id :selected').data('template') != 42) && ($('#post_image').get(0).files.length === 0)) {
                        $("#post_image_message").html("<p class=\"ajax_error\">Please Select Feature Image.</p>")
                    } else {
                        localStorage.removeItem(storageName);
						$('#form').submit();

                    }
                } else {
                    localStorage.removeItem(storageName);
					$('#form').submit();

                }

            } else {
                if (status == 'Publish') {
                    if (postTitle == '') {
                        $("#post_title_message").html("<p class=\"ajax_error\">Please put a title.</p>")
                    } else if (category_id == '' || category_id == 0) {
                        $("#post_category_message").html("<p class=\"ajax_error\">Please Select a category.</p>")
                    } else if (content == '') {
                        $("#post_description_message").html("<p class=\"ajax_error\">Please put some description.</p>")
                    } else {
                        localStorage.removeItem(storageName);
						$('#form').submit();

                    }
                } else {
                    localStorage.removeItem(storageName);
					$('#form').submit();

                }
            }

        });
    });




</script>
