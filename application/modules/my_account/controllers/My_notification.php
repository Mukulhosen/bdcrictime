<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class My_notification extends Frontend_controller {
    
    function __construct() {
        parent::__construct();
//        $this->load->model('My_notification_model');
        $this->load->helper('mynotification');        
    }

    public function index() {
        $data       = [];             
        $active_tab = $this->input->get('tab');
        $view_page  = $this->getViewPage($active_tab);
        
        if ($this->user_id && $this->user_id >= 1) {
            $this->viewMemberContent($view_page, $data);            
        } else {
            $this->viewMemberContent('my_account/login', $data);
        }
    }
    
    public function mailList(){
        ajaxAuthorized();
        $content = $this->input->post('content');
        
        $user_id  = getLoginUserData('user_id');
        $mails = $this->db
                ->where("(`reciever_id` = ".$user_id." OR `sender_id` = ".$user_id.") AND `system` = 'No' AND `parent_id` = 0 AND (`message` LIKE '%".$content."%' ESCAPE '!')")
                ->order_by('id', 'DESC')
                ->get('notifications')
                ->result();
        
//        echo $this->db->last_query();
        
        $mail_last = $this->db->select('id')
                ->where("(`reciever_id` = ".$user_id." OR `sender_id` = ".$user_id.") AND (`parent_id` = 0)")
                ->order_by('id', 'DESC')->limit(1)->get('notifications')->row();
        
        if($mail_last){
            $mail_last_id = $mail_last->id;
        }else{
            $mail_last_id = 0;
        }
            
        $selected = '';
        
        $html = ''; 
        
        if($mails){
            foreach ($mails as $mail) {
                if( $user_id == $mail->sender_id ){
//                    $name = 'You';
//                    $photo_user_id = $user_id;
                    
                    $name = getUserNameById( $mail->reciever_id );
                    $photo_user_id = $mail->reciever_id;
                }else{
                    $name = getUserNameById( $mail->sender_id );
                    $photo_user_id = $mail->sender_id;
                }
                
                if( $mail_last_id == $mail->id ){
                    $selected = 'selected';
                }else{
                    $selected = '';
                }
                
                $html .= '<div class="mail_sidebar_user_list '.$selected.'" data-id="'.$mail->id.'">
                            <div class="col-md-12 no-padding custompadd">
                                <a href="">
                                    <div class="col-md-3 no-padding newdesignmailthumb" style="padding-right: 10px;">
                                        '. getUserProfilePhotoByID( $photo_user_id, "img-circle", 'width:100%').'
                                    </div>
                                    <div class="col-md-9 no-padding newdesignmail">
                                        <h4>'. $name .' <span>'. globalDateFormat($mail->created).'</span></h4>
                                        <p>'. getShortContent( $mail->message, 80) . unread_mails_by_id( $mail->id ).'</p>
                                    </div>
                                </a>
                            </div>
                        </div>';
            }
            
            $html .= "<script>
                    if ($(window).width() > 800) {
                    
                        $('.mail_sidebar_user_list').click(function(e){
                        
                            $('.mail_sidebar_user_list').removeClass('selected');
                            $(this).addClass('selected');
                            
                            e.preventDefault();
                            var id = $(this).attr('data-id');
                            $.ajax({
                                url: 'my_account/my_notification/read_mail_first',
                                type: 'post',
                                data: {id:id},
                                dataType: 'text',
                                beforeSend: function(){
                                    $('#ajax_respond_read_mail').css( 'opacity', '0.5' );
                                },
                                success: function( data ){
                                    $('#ajax_respond_read_mail').html( data ).css( 'opacity', '1' );;
                                },
                            });
                        });
                                                               
                    } else {                            

                        $('.mail_sidebar_user_list').click(function(e){
    
                            $('#message_bar').addClass('hidden');
                            $('#reply_bar').removeClass('hidden');
                            $('#reply_bar').removeClass('reply_bar');
                            
                            $('.mail_sidebar_user_list').removeClass('selected');
                            $(this).addClass('selected');

                            e.preventDefault();
                            var id = $(this).attr('data-id');
                            $.ajax({
                                url: 'my_account/my_notification/read_mail_first',
                                type: 'post',
                                data: {id:id},
                                dataType: 'text',
                                beforeSend: function(){
                                    $('#ajax_respond_read_mail').css( 'opacity', '0.4' );
                                },
                                success: function( data ){
                                    $('#ajax_respond_read_mail').html( data ).css( 'opacity', '1' );;
                                },
                            });
                        });  
                        
                    }
                    </script>";
        }else{
            $html .= '<p class="ajax_notice" style="width:100%">No mail found.</p>';
        }
        echo $html;
    }
    
    public function read_mail_first(){
        
        $notification_id = $this->input->post('id');
        $user_id  = getLoginUserData('user_id');
        
        if( $notification_id == null ){
            $mail_last = $this->db
                ->select('id')
                ->where("(`reciever_id` = ".$user_id." OR (`sender_id` = ".$user_id." AND type = 'onReply')) AND (`parent_id` = 0)")
//                ->where("(`reciever_id` = ".$user_id." OR `sender_id` = ".$user_id.") AND (`parent_id` = 0)")
                ->order_by('id', 'DESC')
                ->limit(1)->get('notifications')->row();
            
            if($mail_last){
                $notification_id = $mail_last->id;
            }else{
                $notification_id = 0;
            }
        }

        $mail = $this->db->get_where('notifications', [ 'id' => $notification_id ])->row();

        if( $mail == null ){
            echo '<p class="ajax_notice" style="width:100%">No mail found.</p>';
            return FALSE;
        }else{
            $access = array($mail->sender_id, $mail->reciever_id); 

            if(!in_array($user_id, $access)){
                return 'Your are not authorise to acction this email';
            } 

            //Update Read/Unread status
            $this->db->where('reciever_id', $user_id);
            $this->db->where('id', $notification_id);
            $this->db->update('notifications', array( 'status' => 'Read' )); 
            
            echo '<div class="new-chat-design">'; 
            $html = '';
            $html .= '<span onClick="return show_list();" class="btn btn-info pull-right show_message_list"><i class="fa fa-list"></i> Show List</span>';
            $html .= '<div class="clearfix"></div>';
            
            if($mail->sender_id == 0){
                $name = getSettingItem('ComName');
                $user_id = 0;
                $photo = '<img src="assets/admin/icons/logo-icon.png" class="img-circle" alt="logo" style="width:100%"/>';
            }elseif( $mail->sender_id == $user_id){
                $name = 'You';
                $photo = getUserProfilePhotoByID( $user_id, '', '');
                
                $html .= '<div class="col-md-12 row chatbox chat2nd">
                        <div class="col-md-10 chatdescrip">
                            <p>'.nl2br($mail->message).'</p>
                        </div>
                        <div class="col-md-2 chatlogo">
                            '. $photo .'
                            <div class="chatdate">'. globalTimeFormat( $mail->created ).'</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>';
            }else{
                $name = getUserNameById( $mail->sender_id );
                $photo = getUserProfilePhotoByID( $mail->sender_id, '', '');
                
                $html .= '<div class="col-md-12 chatbox row">
                                <div class="col-md-2 chatlogo">
                                    '. $photo .'
                                    <div class="chatdate">'. globalTimeFormat( $mail->created ).'</div>
                                </div>
                                <div class="col-md-10 chatdescrip">
                                    <p>'.nl2br($mail->message).'</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>';
            }
            
            echo $html;
            echo read_mail_parent( $notification_id );
            
            echo '</div>';
            
            echo '<div id="mail_body_list_send"></div>';
            if($mail->system == 'No'){
                echo mailReplyForm($notification_id);
            } 
        }
    }
    
    public function get_sent_mail( $id = 0 ){
        $user_id  = getLoginUserData('user_id');
        $mail = $this->db->where('parent_id', $id)->order_by('id', 'DESC')->limit(1)->get('notifications')->row();        
        if( $mail->sender_id == $user_id){
            $name = 'You';
            $photo_user_id = $user_id;
        }else{
            $name = getUserNameById( $mail->sender_id );
            $photo_user_id = $mail->sender_id;
        }
        $html = '';
        $html .= '<div class="mail_body_list_send">';
        
        $html .= '<div class="col-md-12 row chatbox chat2nd">
                        <div class="col-md-10 chatdescrip">
                            <p>'.nl2br($mail->message).'</p>
                        </div>
                        <div class="col-md-2 chatlogo">
                            '. getUserProfilePhotoByID( $photo_user_id, '', '').'
                            <div class="chatdate">'. globalTimeFormat( $mail->created ).'</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>';
        
        $html .= '</div>';
        echo $html;
    }
    
    public function notification_reply(){
        ajaxAuthorized();
        
        $notification_id    = intval( $this->input->post('notification_id') ); 
        $message            = $this->input->post('message');
        
        $mail_data = $this->db->get_where('notifications', ['id' => $notification_id])->row();

        if(getLoginUserData('user_id') == $mail_data->sender_id){
            $reciever_id = intval( $mail_data->reciever_id );
        }else{
            $reciever_id = intval( $mail_data->sender_id );
        }

        $parent_id = $mail_data->id;
        $sender_id  = getLoginUserData('user_id');
        
        $data = [
            'system'        => 'No',
            'type'          => 'onReply',
            'parent_id'     => $parent_id,
            'sender_id'     => $sender_id,
            'reciever_id'   => $reciever_id,
            'message'       => $message,
            'created'       => date('Y-m-d H:i:s'),
        ];
        
        $this->db->insert('notifications', $data);
                
        echo ajaxRespond('OK', '<p class="ajax_success">Message send ...</p>');
    }
    
    
}
