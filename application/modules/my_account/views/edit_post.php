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
                            <h4 class="title"> Update Post <a class="btn btn-default pull-right" href="my_account?tab=my_posts"><i class="fa fa-backward"></i> Back to Post</a>  </h4>
                        </div>

                        <form method="post" id="update_post" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="col-md-12"> <div id="ajax_respond"></div> </div>
                                <?php echo frontEditPost($this->input->get('id')); ?>
                                <div class="col-md-12">
                                    <div class="form-group text-right" style="display: block; ">
                                        <a class="btn btn-default" href="my_account?tab=my_posts" style="margin-right: 5px;"><i class="fa fa-backward"></i> Back to Post</a>                                
                                        <button class="btn btn-success" name="submit" type="submit"><i class="fa fa-save"></i> Update &amp; Stay Here</button>
                                    </div>
                                </div>
                            </div>  
                        </form>
                        
                    </div>
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

    jQuery("#update_post").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(jQuery(this)[0]);
        jQuery.ajax({
            url: 'ajax/update_post',
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function () {
                jQuery('#ajax_respond')
                        .html('<p class="ajax_processing">Loading...</p>')
                        .css('display', 'block');
            },
            success: function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    jQuery('#ajax_respond').html(jsonRespond.Msg);
                    setTimeout(function () {
                        jQuery('#ajax_respond').slideUp();
                        location.reload();
                    }, 2000);
                } else {
                    jQuery('#ajax_respond').html(jsonRespond.Msg);
                }

            }
        });
        return false;
    });
    
    function removePhoto( id, photo ){
        var con = confirm('Are you sure to remove this photo?');
        if( con == true ){
            jQuery.ajax({
                url: 'my_account/remove_photo',
                type: "POST",
                dataType: 'json',
                data: { id: id, photo: photo },
                beforeSend: function () {
                    jQuery('#ajax_respond')
                            .html('<p class="ajax_processing">Processing...</p>')
                            .css('display', 'block');
                },
                success: function (jsonRespond) {
                    if (jsonRespond.Status === 'OK') {
                        jQuery('#ajax_respond').html(jsonRespond.Msg);
                        setTimeout(function () {
                            jQuery('#ajax_respond').slideUp( );
                            jQuery('#remove_photo').slideUp( );
                        }, 2000);
                    } else {
                        jQuery('#ajax_respond').html(jsonRespond.Msg);
                    }

                }
            });
            return false;
        }else{
            return false;
        }        
    }

</script>