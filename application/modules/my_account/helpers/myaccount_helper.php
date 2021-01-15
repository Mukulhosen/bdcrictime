<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function count_my_mails(){
    $CI  =& get_instance();
    $id  = getLoginUserData( 'user_id' );
    $count_mail = $CI->db->select('id')->get_where('mails', ['reciever_id' => $id])->num_rows();
    return $count_mail;
}

function unread_mails_with_badge(){
    $CI  =& get_instance();
    $id  = getLoginUserData( 'user_id' );
    $count_mail = $CI->db->select('id')->get_where('mails', ['reciever_id' => $id, 'status' => 'Unread'])->num_rows();
    if($count_mail == 0){
        return FALSE;
    }else{
        return '<span class="badge bg-green">'.$count_mail.'</span>';
    }    
}

function total_send_mails(){
    $CI  =& get_instance();
    $id  = getLoginUserData( 'user_id' );
    $count_mail = $CI->db->select('id')->get_where('mails', ['sender_id' => $id])->num_rows();
    return $count_mail;
}

function mailList( $search = null ){
    $user_id  = getLoginUserData('user_id');
    $CI  =& get_instance();
    
    $mails = $CI->db
            ->where('parent_id', 0)
            ->where('(reciever_id = '.$user_id.' OR sender_id = '.$user_id.')')
            ->get('mails')
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
                                <h5>'. getShortContent($mail->subject, 25) .'</h5>
                                <p>'. getShortContent( filterEmailTemplate($mail->body), 28) . unread_mails_by_id( $mail->id ).'</p>
                            </div>
                        </a>
                    </div>
                </div>';
    }
    return $html;
}


function read_mail( $mail_id = 0 ){
    if( $mail_id == null ){
        return FALSE;
    }
    
    $user_id  = getLoginUserData('user_id');
    $CI  =& get_instance();
    
    $mail = $CI->db
            ->get_where('mails', [ 'id' => $mail_id ])
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
        $CI->db->where('id', $mail_id);
        $CI->db->update('mails', array( 'status' => 'Read' )); 
            
        if( $mail->sender_id == $user_id){
            $name = 'You';
        }else{
            $name = getUserNameById( $mail->sender_id );
        }
        $html = '';
        $html .= '<div class="mail_body_list leftwhatssapp">
                <h3>'.$mail->subject.'</h3>
                <div class="col-md-12 no-padding">
                    <div class="massegedate">'. globalDateTimeFormat( $mail->created ).'</div>
                    <div class="col-md-1" style="padding-right: 10px; padding-left: 10px;">
                        <img alt="thumb" alt="thumb" src="http://flick.flickoffice.co.uk/uploads/user_file/no-thumb.png" alt="..." width="100%" class="img-circle">
                    </div>
                    <div class="col-md-11 no-padding">
                        <h4>'.$name.'</h4>
                        '.filterEmailTemplate( $mail->body ).'
                    </div>
                </div>
            </div>';
        return $html;
    }
}