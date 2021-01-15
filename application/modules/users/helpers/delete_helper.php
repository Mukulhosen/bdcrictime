<?php

function getLinkedTables() {
    $tables = array(
        'cms',
        'download_bills',
        'event_listing',
        'gallery',
        'gallery_albums',
        'listing_bills',
        'package_transaction',
        'testimonials',
        'user_meta',
        'vendors_gallery',
        'vendor_categories',
        'vendor_services'
    );
    return $tables;
}

function countMatchRecord($table = null, $user_id = 0, $column = 'user_id') {

    if (is_null($table)) { return 'null'; }

    $ci = & get_instance();
    $ci->db->from($table);
    $ci->db->where("{$column}", $user_id);
    return $ci->db->count_all_results();
}
function countJoinMatchRecord($table = null, $user_id = 0, $column = 'service_id') {

    if (is_null($table)) { return 0;  }
    $ci = & get_instance();
    $ci->db->from('event_listing');
    $ci->db->join($table, "{$table}.{$column} = event_listing.id");
    $ci->db->where('event_listing.user_id', $user_id);
    return $ci->db->count_all_results();
}


function countJoinMatchFiles($table = null, $column = '', $user_id = 0) {

    if (is_null($table)) { return 0;  }
    $ci = & get_instance();
    $ci->db->from('event_listing');
    $ci->db->join($table, "{$table}.service_id = event_listing.id");
    $ci->db->where('event_listing.user_id', $user_id);
    $ci->db->where("{$table}.{$column} !=", '');
    return $ci->db->count_all_results();
}


function countMailRecord($user_id = 0) {    
    $ci = & get_instance();
    $ci->db->from('mails');    
    $ci->db->where('sender_id', $user_id);
    return $ci->db->count_all_results();
}

function countMailAttachments( $user_id = 0){    
    $ci = & get_instance();
    $ci->db->from('mails');
    $ci->db->join('mail_attachs', 'mails.id = mail_attachs.mail_id');
    $ci->db->where('mails.sender_id', $user_id);    
    return $ci->db->count_all_results();
}



function countMatchFiles($table = null, $user_id = 0) {
    switch ($table) {
        case 'cms':
            $result = countMatchFileByTable($table, 'thumb', $user_id);
            break;
        case 'posts':
            $result = countMatchFileByTable($table, 'post_image', $user_id);
            break;
        default :
            $result = 0;
    }
    return $result;
}

function countMatchFileByTable($table, $column, $user_id, $where_column = 'user_id') {
    $ci = & get_instance();
    $ci->db->from($table);
    $ci->db->where("{$column} !=", '');
    $ci->db->where("{$where_column}", $user_id);
    return $ci->db->count_all_results();
}

function deleteCmsFiles($user_id = 0) {

    if ($user_id == 0) {
        return false;
    }

    $ci = & get_instance();
    $ci->db->select('thumb');
    $ci->db->from('cms');
    $ci->db->where('post_type', 'business');
    $ci->db->where('user_id', $user_id);
    $files = $ci->db->get()->result();
    $path = dirname(BASEPATH) . '/uploads/cms_photos/';

    foreach ($files as $file) {
        if ($file->thumb && file_exists($path . $file->thumb)) {
            unlink($path . $file->thumb);
        }
    }
    $ci->db->delete('cms', array('user_id' => $user_id, 'post_type' => 'business'));
}


function deletePostsPhotoAndRecord($user_id = 0) {
   
    $ci = & get_instance();
    $ci->db->select('id, post_image');
    $ci->db->from('posts');
    $ci->db->where('user_id', $user_id);
    $posts = $ci->db->get()->result();
    
    foreach ($posts as $post) {
        $path = dirname(BASEPATH) . "/uploads/posts/{$post->post_image}";
        if(file_exists($path) && $post->post_image){
            unlink($path);
        }
        $ci->db->delete('post_comments', array('user_id' => $post->id ));
        $ci->db->delete('post_comments_like_unlike', array('user_id' => $post->id ));
        $ci->db->delete('post_like_unlike', array('user_id' => $post->id ));
    }
    
    $ci->db->delete('posts', array('user_id' => $user_id));
}


function deleteMailAttachmentsAndRecords($user_id = 0) {
   
    $ci = & get_instance();
    
    $ci->db->select('mail_attachs.filename,mail_attachs.id');    
    $ci->db->from('mails');
    $ci->db->join('mail_attachs', 'mails.id = mail_attachs.mail_id');
    $ci->db->where('mails.sender_id', $user_id);          
    $attachments = $ci->db->get()->result();
    
    foreach ($attachments as $attachment) {        
        $path = dirname(BASEPATH) . "/uploads/attachments/{$attachment->filename}";
        if($attachment->filename && file_exists($path)){ unlink($path); }
                
        $ci->db->delete('mail_attachs', array('id' => $attachment->id));        
    }                               
    $ci->db->delete('mails', array('sender_id' => $user_id ));
}

function deleteFileAndFolder($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      
        (is_dir("{$dir}/{$file}")) 
            ? deleteFileAndFolder("{$dir}/{$file}") 
            : unlink("{$dir}/{$file}"); 
    } 
    return rmdir($dir); 
  } 

function deleteUserProfilePhoto($user_id = 0) {
    if ($user_id == 0) {
        return false;
    }

    $ci = & get_instance();
    $ci->db->select('profile_photo');
    $ci->db->from('users');
    $ci->db->where('id', $user_id);
    $photo = $ci->db->get()->row();
    
    $path = dirname(BASEPATH) . '/';
    if ($photo->profile_photo && file_exists($path . $photo->profile_photo)) {
        unlink($path . $photo->profile_photo);
    }
}

