<?php load_module_asset('my_account', 'css'); ?>

<style>
    .attach_file{
        display: flow-root;
        margin: 10px 0px;
    }
	
</style>

<div class="my_account k_new">

    <div style="background-image: url('assets/theme/images/my-account-banner.jpg'); background-repeat:no-repeat; background-size:cover;  background-position: center center;" class="about-banner contact-us-new">
        <div class="container">
            <div class="aboutbanner-text">
                <h2>My Notifications Portal</h2>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="backbuttonmyac">
            <div style="margin-bottom: 10px; margin-top:10px;" class="col-md-12 massagebackbutton">
                <a style="background-color: #39AA0D; border: 0 none; text-transform: uppercase;" href="my_account" class="btn btn-info pull-right"><i class="fa fa-backward"></i> Back to My Account</a>
                <br><br>
            </div>
        </div>
        
        <div class="mbody">
            <div class="col-md-3 no-padding" id="message_bar">
                <div class="col-md-12 no-padding">
                    <div class="panel panel-default no-padding" style="display: table; width: 100%; clear: both;">
                        <div class="panel-heading">
                            <h4 class="title">My Messages</h4>
                        </div>
                        
                        <div style="padding-left:0; padding-right:0;" class="panel-body ">
                            <div class="mail_sidebar_user">

                                <form class="col-md-12" action="" id="search" method="post">
                                    <input type="text" placeholder="Search" name="content" id="content" class="form-control" value=""/>
                                </form>
                                <div class="clearfix"></div>
                                <div id="ajax_respond_search"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            
            <script>
                $('#content').keyup(function(){
                    var content = $('#content').val();
                    $('.mail_sidebar_user_list').remove();

                    $.ajax({
                        url: 'my_account/my_notification/mailList',
                        type: 'post',
                        data: {content:content},
                        dataType: 'text',
                        success: function( data ){
                            $('#ajax_respond_search').html( data );
//                            reload();
                        },
                    });
                });
                    
                $( document ).ready(function() {
                    var content = $('#content').val();
                    $.ajax({
                        url: 'my_account/my_notification/mailList',
                        type: 'post',
                        data: {content:content},
                        dataType: 'text',
                        success: function( data ){
                            $('#ajax_respond_search').html( data );
                        },
                    });
                });               
                
                $( document ).ready(function() {
                    var id = null;
                    $.ajax({
                        url: 'my_account/my_notification/read_mail_first',
                        type: 'post',
                        data: {id:id},
                        dataType: 'text',
                        success: function( data ){
                            $('#ajax_respond_read_mail').html( data );
                        }
                    });
                });
            </script>
            
            
            <div class="col-md-9 formobilemassv kamransss reply_bar" id="reply_bar">
                <div class="col-md-12 no-padding">
                    <div class="panel panel-default no-padding formobilemassv" style="display: table; width: 100%; clear: both; border-radius:0;">
                        <div style="padding-left:0;  padding-top: 15px; padding-right:0;" class="panel-body">
                            <div class="mail_body">

                                <div id="ajax_respond_read_mail"></div>

                            </div>
                        </div>                                                                        
                    </div>
                </div>
            </div>
            
            <div class="clearfix"></div>            
        </div>
    </div>
</div>

<script type="text/javascript">    
    function send_reply(){
        var fd = new FormData(document.getElementById("reply"));       
        var form    = jQuery('#reply');
        var url     = form.attr('action');
        var method  = form.attr('method');  
        var error = 0;
        var notification_id = jQuery('#notification_id').val();

        var message = jQuery('#message').val();
        if (!message) {
            jQuery('#message').addClass('required');
            error = 1;
        } else {
            jQuery('#message').removeClass('required');
        }

        if (!error) {
            $.ajax({
                url: url,
                type: method,
                data: fd,
                dataType: 'json',
                enctype: 'multipart/form-data',
                beforeSend: function () {
                    $('#ajax_respond')
                            .html('<p class="ajax_processing">Processing...</p>')
                            .css('display', 'block');
                },
                success: function( jsonData ){
                    $('<div>').load('my_account/my_notification/get_sent_mail/'+notification_id+'/', function() {
                        $('#mail_body_list_send').append($(this).find('.mail_body_list_send').html());
                    });
                    
                    if(jsonData.Status === 'OK'){
                        $('#ajax_respond').html( jsonData.Msg );
                        setTimeout(function () {
                            jQuery('#ajax_respond').slideUp( );
                            document.getElementById("reply").reset();
                        }, 2000);
                    } else {
                        $('#ajax_respond').html( jsonData.Msg );
                    }
                },
                processData: false, // tell jQuery not to process the data
                contentType: false   // tell jQuery not to set contentType
            });
        }

        return false;    
    }    
    
</script>
<?php load_module_asset('my_account', 'js'); ?>
<?php load_module_asset('my_account', 'js', 'script.mobile.js.php'); ?>