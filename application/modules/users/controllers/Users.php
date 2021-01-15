<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2016-10-05
 */

class Users extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->helper('users');
        $this->load->helper('users/delete');
        $this->load->library('form_validation');
    }

    public function index() {
        $q          = urldecode($this->input->get('q', TRUE));
        $status     = urldecode($this->input->get('status', TRUE));
        $role_id    = intval($this->input->get('role_id', TRUE));
        $start      = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = Backend_URL . 'users?q='.$q.'&role_id='.$role_id.'&status='.$status;
            $config['first_url'] = Backend_URL . 'users?q='.$q.'&role_id='.$role_id.'&status='.$status;
        } else {
            $config['base_url'] = Backend_URL . 'users/';
            $config['first_url'] = Backend_URL . 'users/';
        }

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Users_model->total_rows($q, $status , $role_id);
        $users = $this->Users_model->get_limit_data($config['per_page'], $start, $q, $status , $role_id);
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'users_data' => $users,
            'q' => $q,
            'role_id' => $role_id,
            'status' => $status,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start
        );
        $this->viewAdminContent('users/index', $data);
    }

    public function profile($id) {
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'role_id' => getRoleName($row->role_id),
                'title' => $row->title,
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'email' => $row->email,
                'password' => $row->password,
                'contact' => $row->contact,
                'dob' => $row->dob,
                'add_line1' => $row->add_line1,
                'add_line2' => $row->add_line2,
                'city' => $row->city,
                'state_id' => $row->state_id,
                'postcode' => $row->postcode,
                'country_id' => getCountryName($row->country_id),
                'created' => $row->created,
                'profile_photo' => $row->profile_photo,
                'status' => $row->status
            );
            $row = $this->Users_model->get_by_id($id);
            
            $this->viewAdminContent('users/profile', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url(Backend_URL . 'users'));
        }
    }

    public function mails($id) {
        $data = [];
        $this->viewAdminContent('users/mails', $data);
    }

    public function create() {
        $this->viewAdminContent('users/add_user');
    }

    public function create_action() {
        ajaxAuthorized();
        $yy = $this->input->post('yy');
        $mm = $this->input->post('mm');
        $dd = $this->input->post('dd');               
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            echo ajaxRespond('Fail', form_error('your_email'));
        } else {
            $profileSlug = slugify($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE));
            $hasSlug = $this->db->where('profile_slug', $profileSlug)->from('users')->get()->row();
            if (!empty($hasSlug)) {
                echo ajaxRespond('Fail', 'Please change you name. We already have an user having same name');
                return false;
            }
            $img                = $this->image_upload($_FILES['profile_photo'], time().rand(111, 999));            
            $data = array(
                'role_id'       => intval($this->input->post('role_id', TRUE)),
                'first_name'    => $this->input->post('first_name', TRUE),
                'last_name'     => $this->input->post('last_name', TRUE),
                'email'         => $this->input->post('your_email', TRUE),
                'password'      => password_encription($this->input->post('password', TRUE)),
                'dob'           => $yy.'-'.$mm.'-'.$dd,
                'dob_town'      => $this->input->post('dob_town', TRUE),
                'current_city'  => $this->input->post('current_city', TRUE),
                'school_name'   => $this->input->post('school_name', TRUE),
                'qualification' => $this->input->post('qualification', TRUE),
                'contact'       => $this->input->post('contact', TRUE),
                'location'      => $this->input->post('location', TRUE),
                'lat'           => $this->input->post('lat', TRUE),
                'lng'           => $this->input->post('lng', TRUE),
                'add_line1'     => $this->input->post('add_line1', TRUE),
                'add_line2'     => $this->input->post('add_line2', TRUE),
                'city'          => $this->input->post('city', TRUE),
                'state_id'         => $this->input->post('state_id', TRUE),
                'postcode'      => $this->input->post('postcode', TRUE),
                'country_id'    => $this->input->post('country_id', TRUE),
                'status'        => $this->input->post('status', TRUE),
                'biography'     => $this->input->post('biography', TRUE),
                'profile_photo' => $img,
                'created'       => date("Y-m-d"),
                'profile_slug'      => $profileSlug,
            );

            $this->Users_model->insert($data);
            echo ajaxRespond('OK', '<p class="ajax_success">User Registed Successfully</p>');
        }
    }

    public function update($id) {
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button'        => 'Update',
                'action'        => site_url('users/update_action'),
                'id'            => set_value('id', $row->id),
                'role_id'       => set_value('role_id', $row->role_id),
                'title'         => set_value('title', $row->title),
                'first_name'    => set_value('first_name', $row->first_name),
                'last_name'     => set_value('last_name', $row->last_name),
                'email'         => set_value('email', $row->email),
                'contact'       => set_value('contact', $row->contact),
                'dob'           => set_value('dob', $row->dob),
                'add_line1'     => set_value('add_line1', $row->add_line1),
                'add_line2'     => set_value('add_line2', $row->add_line2),
                'city'          => set_value('city', $row->city),
                'state_id'      => set_value('state_id', $row->state_id),
                'postcode'      => set_value('postcode', $row->postcode),
                'country_id'    => set_value('country_id', $row->country_id),
                'created'       => set_value('created', $row->created),
                'profile_photo' => set_value('profile_photo', $row->profile_photo),
                'old_img'       => set_value('old_img', $row->profile_photo),
                'status'        => set_value('status', $row->status),
                'dob_town'      => set_value('dob_town', $row->dob_town),
                'current_city'  => set_value('current_city', $row->current_city),
                'school_name'   => set_value('school_name', $row->school_name),
                'qualification' => set_value('qualification', $row->qualification),
                'location'      => set_value('location', $row->location),
                'lat'           => set_value('lat', $row->lat),
                'lng'           => set_value('lng', $row->lng),
                'biography'     => set_value('biography', $row->biography),
            );
            
            $this->viewAdminContent('users/edit_user', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('users'));
        }
    }

    public function update_action() {
        ajaxAuthorized();
        $profileSlug = slugify($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE));
        $hasSlug = $this->db->where('profile_slug', $profileSlug)->where('id !=', $this->input->post('id', TRUE))->from('users')->get()->row();
        if (!empty($hasSlug)) {
            echo ajaxRespond('Fail', 'Please change you name. We already have an user having same name');
            return false;
        }
        $date = $this->input->post('yy').'-'.$this->input->post('mm').'-'.$this->input->post('dd');

        if (!empty($_FILES['profile_photo']['name'])) {
            $img = $this->image_upload($_FILES['profile_photo'], time().rand(111, 999));
            
            $file = dirname(BASEPATH) . '/' . $this->input->post('old_img');
            if ($this->input->post('old_img') && file_exists($file)) {
                unlink($file);
            }
        } else {
            $img = $this->input->post('old_img');
        }

        $data = array(
            'role_id'       => intval($this->input->post('role_id', TRUE)),
            'first_name'    => $this->input->post('first_name', TRUE),
            'last_name'     => $this->input->post('last_name', TRUE),
            'dob'           => $date,
            'dob_town'      => $this->input->post('dob_town', TRUE),
            'current_city'  => $this->input->post('current_city', TRUE),
            'school_name'   => $this->input->post('school_name', TRUE),
            'qualification' => $this->input->post('qualification', TRUE),
            'contact'       => $this->input->post('contact', TRUE),
            'location'      => $this->input->post('location', TRUE),
            'lat'           => $this->input->post('lat', TRUE),
            'lng'           => $this->input->post('lng', TRUE),
            'add_line1'     => $this->input->post('add_line1', TRUE),
            'add_line2'     => $this->input->post('add_line2', TRUE),
            'city'          => $this->input->post('city', TRUE),
            'state_id'      => $this->input->post('state_id', TRUE),
            'postcode'      => $this->input->post('postcode', TRUE),
            'country_id'    => $this->input->post('country_id', TRUE),
            'status'        => $this->input->post('status', TRUE),
            'biography'     => $this->input->post('biography', TRUE),
            'profile_photo' => $img,
            'profile_slug'      => $profileSlug,
        );
        $this->Users_model->update($this->input->post('id', TRUE), $data);
        echo ajaxRespond('OK', '<p class="ajax_success">User Update Successfully</p>');
    }

    public function delete($id) {
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'        => $row->id,
                'role_name' => getRoleName($row->role_id),
                'full_name' => $row->title . ' ' .$row->first_name . ' ' . $row->last_name,                
                'email'     => $row->email,                
                'contact'   => $row->contact,
                'created'   => $row->created,
            );
            $this->viewAdminContent('users/delete', $data);
            
        } else {
            $this->session->set_flashdata('message', '<br/><p class="ajax_notice">Record Not Found</p>');
            redirect(site_url( Backend_URL .  'users'));
        }
    }
    
    public function confirm_delete($id) {
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            deleteCmsFiles($id);
            
            deletePostsPhotoAndRecord($id); 
            
            deleteMailAttachmentsAndRecords( $id ); 
                        
            deleteUserProfilePhoto($id);
            
            $this->db->delete('users', array('id' => $id));            
                        
            $this->session->set_flashdata('message', '<br/><p class="ajax_success">User Deleted Successfully</p>');
            redirect(site_url(Backend_URL . 'users'));
        } else {
            $this->session->set_flashdata('message', '<br/><p class="ajax_notice">Record Not Found</p>');
            redirect(site_url( Backend_URL . 'users'));
        }
    }

    public function _rules() {
        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('your_email', 'your email', 'trim|valid_email|required|is_unique[users.email]', [ 'is_unique' => 'This email already in used', 'valid_email' => 'Enter a valide email address']);

        $this->form_validation->set_rules('role_id', 'role_id', 'required');
        $this->form_validation->set_rules('password', 'password field', 'required');
        $this->form_validation->set_error_delimiters('<p class="ajax_error">', '</p>');
    }

    public function image_upload($photo, $name = 0) {        
        $photo_path  = '';
        $handle = new  Verot\Upload\Upload($photo);
        if ($handle->uploaded) {
            $handle->file_overwrite = true;
            $handle->file_new_name_body = $name;
            $handle->image_resize   = true;
            $handle->file_force_extension = true;
            $handle->file_new_name_ext = 'jpg';
            $handle->image_ratio    = true;
            $handle->image_x        = 350;
            $handle->image_y        = 350;
            $handle->jpeg_quality   = 100;
            $handle->Process('uploads/users_profile/');
            $photo_path = stripslashes($handle->file_dst_name);
            if ( $handle->processed ) {
                $handle->clean();
            }
        }  
        return $photo_path;
    }

    public function usernameById($id = null) {
        $user_name = $this->Users_model->get_name_by_id($id);
        return isset($user_name->first_name) ? $user_name->first_name : 'Unknown';
    }

    public function _menu() {
        return buildMenuForMoudle([
            'module' => 'Users',
            'icon' => 'fa-users',
            'href' => 'users',
            'children' => [
                [
                    'title' => 'All User',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'users'
                ], [
                    'title' => 'Add New User',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'users/create'
                ], [
                    'title' => 'Vendor Bills',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'users/vendor_bills'
                ], [
                    'title' => 'Role / ACL',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'users/roles'
                ]
            ]
        ]);
    }

    public function user_update_status() {
        $user_id = intval($this->input->post('user_id'));
        $status = $this->input->post('status');

        $this->db->set('status', $status)->where('id', $user_id)->update('users');

        switch ($status) {
            case 'Active':
                $status = '<i class="fa fa-hourglass-end"></i> Active';
                $class = 'btn-success';
                break;
            case 'Inactive':
                $status = '<i class="fa fa-check"></i> Inactive';
                $class = 'btn-info';
                break;
            default :
                $status = '<i class="fa fa-ban" ></i> Pending';
                $class = 'btn-danger';
                break;
        }

        echo json_encode(['Status' => $status . ' &nbsp; <i class="fa fa-angle-down"></i>', 'Class' => $class]);
    }

    function force_logout($user_id)
    {
        $this->db->insert('force_logout', ['user_id' => $user_id]);
        $this->session->set_flashdata('message', '<p class="ajax_success">Forced Logout Saved successfully</p>');
        redirect($_SERVER['HTTP_REFERER']);
    }

}
