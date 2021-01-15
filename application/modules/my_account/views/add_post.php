<?php load_module_asset('my_account', 'css'); ?>

<div class="my_account">
    <div class="container">
        <div class="col-md-12">
            <h2><small>Welcome</small> <?php echo getLoginUserData('name'); ?> </h2>
        </div>
        <div class="row">
            <?php $user = Modules::run('my_account/profile_info_view', $this->user_id); ?> 

            <div class="col-md-9 pull-right force_mobile_width">
                <div class="col-md-12 no-padding">
                    <div class="panel panel-default no-padding">
                        <div class="panel-heading">
                            <h4 class="title"> Add New <a class="btn btn-primary pull-right" href="my_account?tab=my_posts">Back To Post</a></h4>
                        </div>
                        <form method="post" id="add_post" enctype="multipart/form-data">
                            <div class="panel-body">

                                <div class="col-md-12"> <div id="ajax_respond"></div> </div>

                                <div class="col-md-12 no-padding">
                                    <div class="form-group">
                                        <label for="title">Brief Description</label>
                                        <input type="text" class="form-control" maxlength="80" name="title" value="" required="required" />
                                        <i style="font-size: 11px;" class="pull-right">Maximum Length 80 Character</i>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Detail Description</label>
                                        <textarea class="form-control" rows="10" maxlength="1000" required="required" placeholder="Enter your post description" name="description"></textarea>
                                        <i style="font-size: 11px;" class="pull-right">Maximum Length 1000 Character</i>
                                    </div>
                                    <input type="hidden" name="post_url" />

                                    <div class="form-group">
                                        <label for="thumb">Image</label>
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary">
                                                    Browse&hellip; <input type="file" name="thumb"  style="display: none;">
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" id="file_view" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" style="display: none">
                                        <label for="video">Video</label>
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary">youtube.com</span>
                                            </label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group"><p></p></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 pull-right">
                                        <!--<button type="button" onclick="add_post();"  class="pull-right btn btn-primary">Add New Post</button>-->
                                        <input type="submit" value="Add New Post"   class="pull-right btn btn-primary"  name="submit" />
                                    </div>                                    
                                </div>
                            </div>
                    </div>

                    </form>


                </div>

            </div>
            
            
            <div class="col-md-3 pull-left force_mobile_width"> 
                <?php echo Modules::run('my_account/menu'); ?> 
            </div>
            
            
        </div>
    </div>
</div>
</div>
<?php load_module_asset('my_account', 'js'); ?>
<script>

    // We can attach the `fileselect` event to all file inputs on the page
    jQuery(document).on('change', ':file', function () {
        var input = jQuery(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    // We can watch for our custom `fileselect` event like this
    jQuery(document).ready(function () {
        jQuery(':file').on('fileselect', function (event, numFiles, label) {
            var input = jQuery(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                jQuery('#file_view').val(log);
            } else {
                if (log)
                    alert(log);
            }
        });
    });
    
    jQuery("[name=title]").on('keyup keypress blur change', function () {
        var Text = jQuery(this).val();
        Text = Text.toLowerCase();
        var regExp = /\s+/g;
        Text = Text.replace(regExp, '-');
        jQuery("[name=post_url]").val(Text);
    });

    jQuery("#add_post").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(jQuery(this)[0]);
        jQuery.ajax({
            url: 'ajax/add_post',
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function () {
                jQuery('#ajax_respond')
                    .html('<p class="ajax_processing">Processing...</p>')
                    .css('display', 'block');
            },
            success: function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    jQuery('#ajax_respond').html(jsonRespond.Msg);
                    setTimeout(function () {
                        jQuery('#ajax_respond').slideUp();
//                        jQuery('#add_post').reset();
                        document.getElementById("add_post").reset();
                    }, 2000);
                } else {

                }

            }
        });
        return false;
        //}
    });
    
</script>