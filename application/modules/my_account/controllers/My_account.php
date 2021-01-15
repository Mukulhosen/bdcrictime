<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 8th Oct 2016
 */

class My_account extends Frontend_controller {
    
    protected $isAccessBack;

    function __construct() {
        parent::__construct();
        $this->load->model('My_account_model');
        $this->load->helper('global');
        $this->load->helper('acl_helper');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('myaccount');
        $this->load->helper('dashboard');
        $this->load->helper('guest_profile');
        $this->load->helper('cookie');
        
        $this->isAccessBack = checkPermission('my_account/access_backoffice', $this->role_id);
    }

    public function myAccount() {

		if($this->user_id && $this->isAccessBack && ($this->role_id != 6 || $this->role_id != 7)){
			redirect( Backend_URL, 'refresh');
		}

		// cookie for admin login attempt
		$cookie= array(
			'name'   => 'user',
			'value'  => 'admin',
			'expire' => '300',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
            $this->viewFrontContent('login');

    }

    public function admin_login() {

        if($this->user_id && $this->isAccessBack && ($this->role_id != 6 || $this->role_id != 7)){
            redirect( Backend_URL, 'refresh');
        } 
        
        // cookie for admin login attempt 
        $cookie= array(
            'name'   => 'user',
            'value'  => 'admin',                            
            'expire' => '300',                                                                                   
            'secure' => FALSE
        );
        $this->input->set_cookie($cookie);
    
        $this->viewFrontContent('frontend/admin-login', []);
    }

    private function add_menu_item($link, $title, $access, $icon = '' ){
        if(checkMenuPermission($access, $this->role_id )){
            $html = '';
            $html .= '<a class="list-group-item" href="my_account?tab=' . htmlspecialchars($link) . '">';
            $html .= '<i class="fa ' . $icon . '"></i> ';
            $html .= $title . '</a>';
            return $html;
        }                
    }

    private function getViewPage($view = null) {
        $filename = dirname(dirname(__FILE__)) . '/views/' . $view . '.php';

        return ($view && file_exists($filename)) ? 'my_account/' . $view : 'my_account/index';
    }

    public function change_password() {
        ajaxAuthorized();
        
        $old_pass = $this->input->post('old_pass');
        $new_pass = $this->input->post('new_pass');
        $con_pass = $this->input->post('con_pass');
 
        if (!$old_pass or !$new_pass or !$con_pass ) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Required all fields</p>');
            exit;           
        } 
        if ($new_pass != $con_pass) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Confirm Password Not Match</p>');
            exit;
        }

        $user = $this->db->select('password')->get_where('users', ['id' => $this->user_id])->row();

        $db_pass = $user->password;
        $verify = password_verify($old_pass, $db_pass);

        if ($verify == true) {
            $hass_pass = password_hash($new_pass, PASSWORD_BCRYPT, ["cost" => 12]);
            $this->db->update('users', ['password' => $hass_pass], ['id' => $this->user_id]);

            echo ajaxRespond('OK', '<p class="ajax_success">Password Reset Successfully</p>');
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_error">Old Password not match, please try again.</p>');                
        }
    }

    public function _rules_for_password() {
        $this->form_validation->set_rules('old_pass', 'Password is required', 'trim|required');
        $this->form_validation->set_rules('new_pass', 'New Password is required', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('con_pass', 'Password Confirmation is required', 'trim|required|min_length[6]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    // update user profile
    public function update_user_profile() {
        ajaxAuthorized();
        $dob = $this->input->post('dob_yy', TRUE).'-'.$this->input->post('dob_mm', TRUE).'-'.$this->input->post('dob_dd', TRUE);
        $data = array(
            'first_name'    => $this->input->post('first_name', TRUE),
            'last_name'     => $this->input->post('last_name', TRUE),
            'occupation'    => $this->input->post('occupation', TRUE),
            'gender'        => $this->input->post('gender', TRUE),
            'birth_location' => $this->input->post('birth_location', TRUE),
            'location'      => $this->input->post('location', TRUE),
            'lat'           => $this->input->post('lat', TRUE),
            'lng'           => $this->input->post('lng', TRUE),
            'dob'           => $dob,
            'born'          => $this->input->post('born', TRUE),
        );
        
        $this->db->where('id', $this->user_id);
        $this->db->update('users', $data);
        
        if($_FILES['profile_photo']['name']){
            $this->updateUserPhoto($_FILES['profile_photo'], $this->user_id);
        }

        echo ajaxRespond('OK', '<p class="ajax_success">Profile Upadte Successfully</p>');      
    }
    
    private function updateUserPhoto($data = [], $user_id){
         if (!empty(['name'])) {
            $img = profile_photo_upload($data, $user_id);
            
            $old_image = $this->db->select('profile_photo, id')->get_where('users', ['id' => $user_id])->row();
            if($old_image){
                removeImage($old_image->profile_photo, 'users_profile');
            }
            
            $this->db->update('users', ['profile_photo' => $img],['id' => $user_id]);
            return true;
        }
    }

 
    // serving data to profile page.....
    public function profile_info_view($user_id = 0) {
        return $this->db->get_where('users', ['id' => $user_id])->row();
    }    
    
    public function getReport($user_id = 0) {
        $reports = $this->db->get_where('mails', ['mail_type' => 'ReportSpam', 'reciever_id' => $user_id ])->result();
        return  $reports; 
    }    
    
    public function getMyMails( $folder = 'sent' ){
        $this->db->from('mails');
        
        if($folder === 'sent'){
           $this->db->where('sender_id', $this->user_id );
        } else {
           $this->db->where('reciever_id', $this->user_id ); 
        }
                
        return $mails =  $this->db->get()->result();         
    }
    
    public function read_mail( $mail_id = 0 ){
        ajaxAuthorized();
                                                
        $data['mail'] = $this->db->get_where('mails', ['id' => $mail_id ])->row();
        $this->db->set('status', 'Read')->where('id', $mail_id)->update('mails');
        $this->load->view('read_mail', $data);
    }
    
    public function getMyPosts() {
        $this->db->from('posts');
        $this->db->order_by('id', 'DESC');
        $this->db->where('user_id', $this->user_id);
        return $my_posts = $this->db->get()->result();
    }
    
    public function approve_now() {
        $mindate = date("Y-m-d H:i:s", strtotime("-1 month", strtotime(date('Y-m-d H:i:s'))));
        
        $results = $this->db->select('post_id')->get_where('post_like_unlike', ['user_id' => $this->user_id, 'like_unlike' => 0])->result();

        if($results){
            $array = [];
            foreach ($results as $value){
                $array2 = [$value->post_id];
                $array = array_merge($array, $array2);
            }
            
            $this->db->select('id, title, post_url, post_image');
            $this->db->order_by('id', 'DESC');
            
            $this->db->where('modified >=', $mindate);
            $this->db->where('modified <=', date('Y-m-d H:i:s'));
            
            $this->db->where('journalist !=', 0);
            $this->db->where_in('id', $array);
            $this->db->from('posts');
            $my_posts = $this->db->get()->result();
            return $my_posts;
            
        }else{
            return FALSE;
        }
    }

    public function menu($active = '') {        
        $html = '<div class="my_sidebar">';
            $html .= '<div class="list-group" role="group">';
                $html .= $this->add_menu_item('db', 'Dashboard', 'my_account', 'fa-dashboard');
                $html .= $this->add_menu_item('my_posts', 'My Posts <span style="float: right;" class="badge">'.count_my_posts().'</span>', 'my_account/my_posts', 'fa-bars' ); 
                $html .= $this->add_menu_item('add_post', 'Add New Post', 'my_account/add_post', 'fa-plus' ); 
                $html .= $this->add_menu_item('approve_now', 'Approve Now <span class="badge">'.count_approve_now($this->user_id).'</span>', 'my_account/approve_now', 'fa-bars' );
                $html .= $this->add_menu_item('mails', 'Mail box' . unread_notifications_with_badge() , 'my_account/mails', 'fa-comments');
                $html .= $this->add_menu_item('profile', 'My Profile', 'my_account', 'fa-bars' );
                $html .= $this->add_menu_item('pwd', 'Change Password', 'my_account', 'fa-random' );
                
                $html .= '<a class="list-group-item" href="auth/logout"><i class="fa fa-sign-out"></i> Logout</a>';                
            $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    public function edit_post () {
        $id = $_REQUEST['id'];
        echo $id;
    }
    
    public function remove_photo(){
        $old_img = $this->input->post('photo');
        removeImage($old_img, 'posts');
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('posts', array( 'post_image' => null ));
        echo ajaxRespond('OK', '<p class="ajax_success">Photo Remove Success.</p>');
    }
    
    public function remove_user_photo(){
        $user_id = $this->input->post('id');
        
        $old_image = $this->db->select('profile_photo, id')->get_where('users', ['id' => $user_id])->row();
        if($old_image){
            removeImage($old_image->profile_photo, 'users_profile');
        }
        $this->db->update('users', ['profile_photo' => null], ['id' => $user_id]);
        
        echo ajaxRespond('OK', '<p class="ajax_success">Photo Remove Success.</p>');
    }   

    public function forget_password_form() {
        $data = [];
        $this->db->where('post_url', 'forget-password');
        $this->db->from('cms');
        $cdata =  $this->db->get()->row();
        if (!empty($cdata)){
            $data =  [
                'meta_title'    => ($cdata->seo_title) ? $cdata->seo_title : $cdata->name,
                'meta_description'  => $cdata->seo_keyword,
                'meta_keywords'     => $cdata->seo_description,
            ];
        }
        $this->viewFrontContent('frontend/forget-password', $data);
    }

    public function profile() {
        $user_id = getLoginUserData('user_id');

        $user_data = $this->db->get_where('users', ['id' => $user_id])->row();
        $user_meta_data = $this->db->get_where('user_meta', ['user_id' => $user_id])->row();
        $data = (object) array_merge((array) $user_data, (array) $user_meta_data);
//        pp( $data );
        $this->viewMemberContent('profile', $data);
    }

    public function profile_update() {
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
        $hasSlug = $this->db->where('profile_slug', $profileSlug)
            ->where('id !=', $this->user_id)->from('users')->get()->row();
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
            'state_id'      => $this->input->post('state_id', TRUE),
            'facebook_link'      => $this->input->post('facebook_link', TRUE),
            'twitter_link'      => $this->input->post('twitter_link', TRUE),
            'instagram_link'      => $this->input->post('instagram_link', TRUE),
            'profile_slug'      => $profileSlug,
        ];

        $this->db->where('id', $this->user_id)->update('users', $datas);
        echo ajaxRespond('OK', ['msg' => '<p class="ajax_success">Profile Updated Successfully<p>', 'img' => $img]);
    }

    public function password() {
        $this->viewMemberContent('password');
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
