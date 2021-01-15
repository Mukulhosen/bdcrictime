<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 8th Oct 2016
 */

class Profile extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Profile_model');
        $this->load->helper('profile');

        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
        $user_id = getLoginUserData('user_id');

        $user_data = $this->db->get_where('users', ['id' => $user_id])->row();
        $user_meta_data = $this->db->get_where('user_meta', ['user_id' => $user_id])->row();
        $data = (object) array_merge((array) $user_data, (array) $user_meta_data);
//        pp( $data );
        $this->viewAdminContent('index', $data);
    }

    public function update() {
        ajaxAuthorized();    
        
        if (!empty($_FILES['profile_photo']['name'])) {
            $img = profile_photo_upload($_FILES['profile_photo'], $this->user_id);      
            $old_img = $this->input->post('old_img');
            $file = dirname(BASEPATH) . '/uploads/users_profile/' . $old_img;
            if ($old_img && file_exists($file)) {
                unlink($file);
            }

            $cookie_data = json_decode(base64_decode($this->input->cookie('fm_login_data', false)));
            $cookie_data->photo = $img;
            $cookie = [
                'name' => 'login_data',
                'value' => base64_encode(json_encode($cookie_data)),
                'expire' => 60 * 60 * 24 * 7,
                'secure' => false
            ];

            $this->input->set_cookie($cookie);
        } else {
            $img = $this->input->post('old_img');
        }
        $dob = $this->input->post('dob_yy', TRUE).'-'.$this->input->post('dob_mm', TRUE).'-'.$this->input->post('dob_dd', TRUE);
        $profileSlug = slugify($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE));
        $hasSlug = $this->db->where('profile_slug', $profileSlug)->where('id !=', $this->user_id)->from('users')->get()->row();
        if (!empty($hasSlug)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please change you name. We already have an user having same name<p>');
            return false;
        }
        $datas = [ 
            'first_name'    => $this->input->post('first_name', TRUE),
            'last_name'     => $this->input->post('last_name', TRUE),
            'location'      => $this->input->post('location', TRUE),
            'lat'           => $this->input->post('lat', TRUE),  
            'lng'           => $this->input->post('lng', TRUE),  
            'biography'     => $this->input->post('biography', TRUE),  
            'gender'        => $this->input->post('gender', TRUE),
            'profile_photo' => $img,
            'dob'           => $dob,
            'dob_town'      => $this->input->post('dob_town', TRUE),
            'current_city'  => $this->input->post('current_city', TRUE),
            'school_name'   => $this->input->post('school_name', TRUE),
//            'state_id'      => $this->input->post('state_id', TRUE),
            'facebook_link'      => $this->input->post('facebook_link', TRUE),
            'twitter_link'      => $this->input->post('twitter_link', TRUE),
            'instagram_link'      => $this->input->post('instagram_link', TRUE),
            'profile_slug'      => $profileSlug,
        ];

        $this->db->where('id', $this->user_id)->update('users', $datas);
        echo ajaxRespond('OK', ['msg' => '<p class="ajax_success">Profile Updated Successfully<p>', 'img' => $img]);
    }

    public function password() {
        $this->viewAdminContent('password');
    }

    public function update_password() {

        $old_pass = $this->input->post('old_pass');
        $new_pass = $this->input->post('new_pass');
        $con_pass = $this->input->post('con_pass');


        if (!empty($old_pass) && !empty($new_pass) && !empty($con_pass)) {
            if ($new_pass != $con_pass) {
                echo ajaxRespond('Fail', '<p class="ajax_error">Confirm Password Not Match</p>');
                exit;
            }
            $user_id = getLoginUserData('user_id');
            $user = $this->db->select('password')
                    ->get_where('users', ['id' => $user_id])
                    ->row();

            $db_pass = $user->password;
            $verify = password_verify($old_pass, $db_pass);
            

            if (($verify == true) && ($new_pass == $con_pass)) {
                $hass_pass = password_hash($new_pass, PASSWORD_BCRYPT, ["cost" => 12]);
                $this->db->update('users', ['password' => $hass_pass], ['id' => $user_id]);

                echo ajaxRespond('OK', '<p class="ajax_success">Password Reset Successfully</p>');
            } else {
                echo ajaxRespond('Fail', '<p class="ajax_error">Old Password not match, please try again.</p>');
            }
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_error">All field are required.</p>');
            exit;
        }
    }
}
