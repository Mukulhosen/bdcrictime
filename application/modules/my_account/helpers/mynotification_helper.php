<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function mailList( $search = null ){
    $user_id  = getLoginUserData('user_id');
    $CI  =& get_instance();
    
    $mails = $CI->db
            ->where('parent_id', 0)
            ->where('(reciever_id = '.$user_id.' OR sender_id = '.$user_id.')')
            ->get('notifications')
            ->result();
//    echo $CI->db->last_query();
    
    $html = '';
    foreach ($mails as $mail) {
        if( $user_id == $mail->sender_id ){
            $name = 'You';
        }else{
            $name = getUserNameById( $mail->sender_id );
        }
        
        $read_id = $CI->input->get('read');
        if( $mail->id == $read_id ){
            $selected = 'selected';
        }else{
            $selected = '';
        }
        
        $html .= '<div class="mail_sidebar_user_list '.$selected.'">
                    <div class="col-md-12 no-padding custompadd">
                        <a href="my_account?tab=mails&read='. $mail->id .'">
                            <div class="col-md-3 no-padding" style="padding-right: 10px;">
                                <img alt="thumb" src="http://flick.flickoffice.co.uk/uploads/user_file/no-thumb.png" alt="..." width="100%" class="img-circle">
                            </div>
                            <div class="col-md-9 no-padding">
                                <h4>'. $name .' <span>'. globalDateFormat($mail->created).'</span></h4>
                                <p>'. getShortContent( $mail->message, 28) . unread_mails_by_id( $mail->id ).'</p>
                            </div>
                        </a>
                    </div>
                </div>';
    }
    return $html;
}


function read_mail( $notification_id = 0 ){
    if( $notification_id == null ){
        return FALSE;
    }
    
    $user_id  = getLoginUserData('user_id');
    $CI  =& get_instance();
    
    $mail = $CI->db
            ->get_where('notifications', [ 'id' => $notification_id ])
            ->row();
    
    if( $mail == null ){
        return FALSE;
    }else{
        $access = array($mail->sender_id, $mail->reciever_id); 
    
        if(!in_array($user_id, $access)){
            return 'Your are not authorise to acction this email';
        } 
        
        //Update Read/Unread status
        $CI->db->where('reciever_id', $user_id);
        $CI->db->where('id', $notification_id);
        $CI->db->update('notifications', array( 'status' => 'Read' )); 
            
        if( $mail->sender_id == $user_id){
            $name = 'You';
        }else{
            $name = getUserNameById( $mail->sender_id );
        }
        $html = '';
        $html .= '<div class="mail_body_list leftwhatssapp">
                <div class="col-md-12 no-padding">
                    <div class="massegedate">'. globalDateTimeFormat( $mail->created ).'</div>
                    <div class="col-md-1" style="padding-right: 10px; padding-left: 10px;">
                        <img src="http://flick.flickoffice.co.uk/uploads/user_file/no-thumb.png" alt="..." width="100%" class="img-circle">
                    </div>
                    <div class="col-md-11 no-padding">
                        <h4>'.$name.'</h4>
                        '. nl2br( $mail->message ).'
                    </div>
                </div>
            </div>';
        return $html;
    }
}

function read_mail_parent( $notification_id = 0 ){
    if( $notification_id == null ){
        return FALSE;
    }
    
    $user_id  = getLoginUserData('user_id');
    $CI  =& get_instance();
    
    $mail = $CI->db->get_where('notifications', [ 'id' => $notification_id ])->row();
    
    if( $mail == null ){
        return false;
    }else{
        $access = array( $mail->sender_id, $mail->reciever_id ); 
    
        if(!in_array($user_id, $access)){
            return 'You are not authorise to acction this email';
        }
        $mails = $CI->db->get_where('notifications', [ 'parent_id' => $notification_id ])->result();    

        $html = '';
        foreach ( $mails as $mail ){
            $CI->db->where('reciever_id', $user_id);
            $CI->db->where('id', $mail->id);
            $CI->db->update('notifications', array( 'status' => 'Read' ));
            if( $mail->sender_id == $user_id){
                $name = 'You';
                $photo_user_id = $user_id;
                
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
            }else{
                $name = getUserNameById( $mail->sender_id );
                $photo_user_id = $mail->sender_id;
                
                $html .= '<div class="col-md-12 chatbox row">
                                <div class="col-md-2 chatlogo">
                                    '. getUserProfilePhotoByID( $photo_user_id, '', '').'
                                    <div class="chatdate">'. globalTimeFormat( $mail->created ).'</div>
                                </div>
                                <div class="col-md-10 chatdescrip">
                                    <p>'.nl2br($mail->message).'</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>';
            }
        }

        return $html;

    }
}

function getAttachment( $notification_id = 0, $user_id = 0 ){
    $CI  =& get_instance();
    $files = $CI->db->get_where('notification_attachs', [ 'notification_id' => $notification_id ])->result();
    
    $html = '';
    if($files){
        $html .= '<div class="attachments_download">Download Attachments</div>';
    }
    
    foreach( $files as $file ){
        
        $ext = findexts($file->filelocation);

        if ($ext == 'txt' || $ext == 'sql') {
            $class = 'text';
        } elseif ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png' || $ext == 'psd' || $ext == 'ai') {
            $class = 'image';
        } elseif ($ext == 'doc' || $ext == 'docx' || $ext == 'odt') {
            $class = 'doc';
        } elseif ($ext == 'xls') {
            $class = 'xls';
        } elseif ($ext == 'pdf') {
            $class = 'pdf';
        } elseif ($ext == 'mp4' || $ext == 'avi' || $ext == '3gp') {
            $class = 'video';
        } elseif ($ext == 'zip' || $ext == 'rar') {
            $class = 'zip';
        } else {
            $class = 'other';
        }
        
        $html .= '<div class="attachments_show">';
        $html .= '<a href="uploads/email/'.$user_id.'/'.$file->filelocation.'" download>';
        $html .= '<div class="'.$class.'"></div>';
        $html .= '<div class="file_name">'.$file->filename.'</div>';
        $html .= '<div class="clearfix"></div>';
        $html .= '</a>';
        $html .= '</div>';
    }
    
    return $html;
}

function filterEmailTemplate( $html = '' ){
    if(empty($html)){
        return '';
    }
    
    $dom = new domDocument('1.0','UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $dom->preserveWhiteSpace = true;
    $node = $dom->getElementsByTagName('section')->item(0);
    if($node){
        $cleanHTML = $node->ownerDocument->saveHTML($node);
        return $cleanHTML;
    }else{
        return $html;
    }
}

function unread_mails_by_id( $id ){
    $user_id = getLoginUserData('user_id');
    $CI  =& get_instance();
    $count_mail = $CI->db->select('id')
            ->where('status = "Unread" AND reciever_id = '.$user_id.' AND (id = '.$id.' OR parent_id = '.$id.')')
            ->get('notifications')
            ->num_rows();
    
//    echo $CI->db->last_query();
    
    if( $count_mail != 0 ){
        return '<span class="label label-primary pull-right">'.$count_mail.'</span>';
    }else{
        return false;
    }
}

function unread_mails_by_id_color( $id ){
    $user_id = getLoginUserData('user_id');
    $CI  =& get_instance();
    $count_mail = $CI->db->select('id')
            ->where('status = "Unread" AND reciever_id = '.$user_id.' AND (id = '.$id.' OR parent_id = '.$id.')')
            ->get('notifications')
            ->num_rows();
    if( $count_mail != 0 ){
        return 'style ="color: red"';
    }else{
        return false;
    }
}


function mailReplyForm($id = 0){
    $html = '';
    $html .= '<div class="clearfix"></div>
        <div id="ajax_respond"></div>
            <div class="typemessage col-md-12">
                <form action="my_account/my_notification/notification_reply" method="post" id="reply" onsubmit="return send_reply();" enctype="multipart/form-data">
                    <div class="input-group">
                        <textarea name="message" id="message" rows="3" class="form-control input-sm chat_input btn-input"></textarea>
                        <input class="input-file" id="fileInput1" type="hidden" name="Attachments[]">
                        <input type="hidden" name="notification_id" id="notification_id" value="'.$id.'"/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary btn-sm sendchat" id="btn-chat"><i class="fa fa-location-arrow" aria-hidden="true"></i></button>
                        </span>
                    </div>
                </form>
            </div>';
            
    return $html;
}
