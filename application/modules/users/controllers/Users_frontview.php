<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_frontview extends Frontend_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->helper('users');
        $this->load->library('form_validation');
    }

    function message_member(){
        ajaxAuthorized();
        $user_ids = $this->input->post('user_id');
        $message = $this->input->post('message');
        $insert_batch = [];
        
        foreach (array_keys($user_ids) as $key) {
            $insert_batch[] = array(
                'parent_id'     => 0,
                'sender_id'     => $this->user_id,
                'reciever_id'   => $user_ids[$key],
                'body'          => $message,
                'created'       => date('Y-m-d H:i:s'),
            );
        }
        
        if($insert_batch){
            $this->db->insert_batch('notifications', $insert_batch);
            echo ajaxRespond('OK', '<p class="ajax_success">Message send success!</p>');
        }else{
            echo ajaxRespond('Fail', '<p class="ajax_success">Message not send!</p>');
        }
        
    }
}
